<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Sekolah;
use App\Models\PengajuanPemusnahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class PengajuanPemusnahanController extends Controller
{
    /** Jumlah maksimal percobaan ulang saat terjadi bentrok nomor_pengajuan */
    private const MAX_NOMOR_RETRY = 3;

    // ── INDEX ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = PengajuanPemusnahan::with(['aset', 'sekolah', 'pengaju']);

        if (Auth::user()->hasRole('operator_sekolah')) {
            $query->where('diajukan_oleh', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sekolah_id') && !Auth::user()->hasRole('operator_sekolah')) {
            $query->where('sekolah_id', $request->sekolah_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nomor_pengajuan', 'like', "%{$search}%")
                  ->orWhere('alasan_penghapusan', 'like', "%{$search}%")
                  ->orWhereHas('aset', fn($a) => $a->where('nama_aset', 'like', "%{$search}%"));
            });
        }

        $pengajuans = $query->latest()->paginate(10)->withQueryString();
        $sekolahs   = Sekolah::orderBy('nama_sekolah')->get();

        return view('dashboard.pengajuan.index', compact('pengajuans', 'sekolahs'));
    }

    // ── CREATE ─────────────────────────────────────────────────────────
    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('operator_sekolah')) {
            $sekolah = Sekolah::where('operator_id', $user->id)->first();
            $asets   = Aset::where('sekolah_id', $sekolah?->id)
                           ->whereNull('deleted_at')
                           ->orderBy('nama_aset')
                           ->get();
            $sekolahs = collect([$sekolah]);
        } else {
            $asets    = Aset::whereNull('deleted_at')->orderBy('nama_aset')->get();
            $sekolahs = Sekolah::orderBy('nama_sekolah')->get();
        }

        $metodes = [
            'pemusnahan'   => 'Pemusnahan',
            'lelang'       => 'Lelang',
            'hibah'        => 'Hibah',
            'tukar_tambah' => 'Tukar Tambah',
        ];

        return view('dashboard.pengajuan.create', compact('asets', 'sekolahs', 'metodes'));
    }

    // ── STORE ──────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'aset_id'             => 'required|integer|exists:aset,id',
            'sekolah_id'          => 'required|integer|exists:sekolah,id',
            'alasan_penghapusan'  => 'required|string|max:255',
            'metode_penghapusan'  => 'required|in:pemusnahan,lelang,hibah,tukar_tambah',
            'jumlah_diajukan'     => 'required|integer|min:1',
            'keterangan'          => 'nullable|string',
            'dokumen_pendukung'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Enforce operator_sekolah can only submit for their own school
        if ($user->hasRole('operator_sekolah')) {
            $ownSekolah = Sekolah::where('operator_id', $user->id)->first();
            if (!$ownSekolah || (int) $request->sekolah_id !== (int) $ownSekolah->id) {
                abort(403, 'Anda hanya dapat mengajukan penghapusan untuk sekolah Anda sendiri.');
            }
        }

        // Load asset (not soft-deleted) and validate it belongs to the submitted sekolah,
        // and that requested quantity does not exceed available stock.
        $aset = Aset::where('id', $request->aset_id)
            ->whereNull('deleted_at')
            ->first();

        if (!$aset) {
            throw ValidationException::withMessages([
                'aset_id' => 'Aset tidak ditemukan atau sudah dihapus.',
            ]);
        }

        if ((int) $aset->sekolah_id !== (int) $request->sekolah_id) {
            throw ValidationException::withMessages([
                'aset_id' => 'Aset yang dipilih tidak sesuai dengan sekolah yang dipilih.',
            ]);
        }

        if ((int) $request->jumlah_diajukan > (int) $aset->jumlah) {
            throw ValidationException::withMessages([
                'jumlah_diajukan' => "Jumlah diajukan ({$request->jumlah_diajukan}) melebihi stok tersedia ({$aset->jumlah}).",
            ]);
        }

        // Simpan file dulu di luar transaction/retry-loop supaya tidak
        // terupload berkali-kali jika terjadi retry akibat bentrok nomor_pengajuan.
        $dokumenPath = null;
        if ($request->hasFile('dokumen_pendukung')) {
            $dokumenPath = $request->file('dokumen_pendukung')
                ->store('pengajuan-penghapusan', 'public');
        }

        $attempt = 0;

        while (true) {
            $attempt++;
            DB::beginTransaction();
            try {
                PengajuanPemusnahan::create([
                    'nomor_pengajuan'    => PengajuanPemusnahan::generateNomor(),
                    'aset_id'            => $request->aset_id,
                    'sekolah_id'         => $request->sekolah_id,
                    'diajukan_oleh'      => Auth::id(),
                    'alasan_penghapusan' => $request->alasan_penghapusan,
                    'metode_penghapusan' => $request->metode_penghapusan,
                    'jumlah_diajukan'    => $request->jumlah_diajukan,
                    'keterangan'         => $request->keterangan,
                    'dokumen_pendukung'  => $dokumenPath,
                    'status'             => 'menunggu',
                ]);

                DB::commit();

                return redirect()->route('pengajuan-penghapusan-asset.index')
                    ->with('success', 'Pengajuan penghapusan aset berhasil dibuat.');
            } catch (QueryException $e) {
                DB::rollBack();

                $isDuplicateNomor = ($e->errorInfo[1] ?? null) == 1062
                    && str_contains($e->getMessage(), 'nomor_pengajuan');

                if ($isDuplicateNomor && $attempt < self::MAX_NOMOR_RETRY) {
                    Log::warning('Bentrok nomor_pengajuan, mencoba ulang', [
                        'attempt' => $attempt,
                        'user_id' => Auth::id(),
                    ]);
                    continue; // coba lagi dengan nomor baru
                }

                if ($dokumenPath) {
                    Storage::disk('public')->delete($dokumenPath);
                }

                Log::error('Gagal membuat pengajuan penghapusan', [
                    'user_id' => Auth::id(),
                    'error'   => $e->getMessage(),
                ]);

                return back()->withInput()
                    ->with('error', 'Gagal membuat pengajuan. Silakan coba lagi atau hubungi administrator.');
            } catch (\Throwable $e) {
                DB::rollBack();

                if ($dokumenPath) {
                    Storage::disk('public')->delete($dokumenPath);
                }

                Log::error('Gagal membuat pengajuan penghapusan', [
                    'user_id' => Auth::id(),
                    'error'   => $e->getMessage(),
                ]);

                return back()->withInput()
                    ->with('error', 'Gagal membuat pengajuan. Silakan coba lagi atau hubungi administrator.');
            }
        }
    }

    // ── SHOW ───────────────────────────────────────────────────────────
    public function show(PengajuanPemusnahan $pengajuanPemusnahan)
    {
        if (Auth::user()->hasRole('operator_sekolah') &&
            $pengajuanPemusnahan->diajukan_oleh !== Auth::id()) {
            abort(403);
        }

        $pengajuanPemusnahan->load(['aset', 'sekolah', 'pengaju', 'validator']);
        return view('dashboard.pengajuan.show', [
            'pengajuan' => $pengajuanPemusnahan,
        ]);
    }

    // ── EDIT ───────────────────────────────────────────────────────────
    public function edit(PengajuanPemusnahan $pengajuanPemusnahan)
    {
        if ($pengajuanPemusnahan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat diubah.');
        }

        if (Auth::user()->hasRole('operator_sekolah') &&
            $pengajuanPemusnahan->diajukan_oleh !== Auth::id()) {
            abort(403);
        }

        $user = Auth::user();
        if ($user->hasRole('operator_sekolah')) {
            $sekolah  = Sekolah::where('operator_id', $user->id)->first();
            $asets    = Aset::where('sekolah_id', $sekolah?->id)->whereNull('deleted_at')->orderBy('nama_aset')->get();
            $sekolahs = collect([$sekolah]);
        } else {
            $asets    = Aset::whereNull('deleted_at')->orderBy('nama_aset')->get();
            $sekolahs = Sekolah::orderBy('nama_sekolah')->get();
        }

        $metodes = [
            'pemusnahan'   => 'Pemusnahan',
            'lelang'       => 'Lelang',
            'hibah'        => 'Hibah',
            'tukar_tambah' => 'Tukar Tambah',
        ];

        return view('dashboard.pengajuan.edit', [
            'pengajuan' => $pengajuanPemusnahan,
            'asets'     => $asets,
            'sekolahs'  => $sekolahs,
            'metodes'   => $metodes,
        ]);
    }

    // ── UPDATE ─────────────────────────────────────────────────────────
    public function update(Request $request, PengajuanPemusnahan $pengajuanPemusnahan)
    {
        if ($pengajuanPemusnahan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat diubah.');
        }

        if (Auth::user()->hasRole('operator_sekolah') &&
            $pengajuanPemusnahan->diajukan_oleh !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'aset_id'             => 'required|integer|exists:aset,id',
            'sekolah_id'          => 'required|integer|exists:sekolah,id',
            'alasan_penghapusan'  => 'required|string|max:255',
            'metode_penghapusan'  => 'required|in:pemusnahan,lelang,hibah,tukar_tambah',
            'jumlah_diajukan'     => 'required|integer|min:1',
            'keterangan'          => 'nullable|string',
            'dokumen_pendukung'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Enforce operator_sekolah can only edit into their own school
        if ($user->hasRole('operator_sekolah')) {
            $ownSekolah = Sekolah::where('operator_id', $user->id)->first();
            if (!$ownSekolah || (int) $request->sekolah_id !== (int) $ownSekolah->id) {
                abort(403, 'Anda hanya dapat mengubah pengajuan untuk sekolah Anda sendiri.');
            }
        }

        $aset = Aset::where('id', $request->aset_id)
            ->whereNull('deleted_at')
            ->first();

        if (!$aset) {
            throw ValidationException::withMessages([
                'aset_id' => 'Aset tidak ditemukan atau sudah dihapus.',
            ]);
        }

        if ((int) $aset->sekolah_id !== (int) $request->sekolah_id) {
            throw ValidationException::withMessages([
                'aset_id' => 'Aset yang dipilih tidak sesuai dengan sekolah yang dipilih.',
            ]);
        }

        if ((int) $request->jumlah_diajukan > (int) $aset->jumlah) {
            throw ValidationException::withMessages([
                'jumlah_diajukan' => "Jumlah diajukan ({$request->jumlah_diajukan}) melebihi stok tersedia ({$aset->jumlah}).",
            ]);
        }

        DB::beginTransaction();
        try {
            $dokumenPath = $pengajuanPemusnahan->dokumen_pendukung;

            if ($request->hasFile('dokumen_pendukung')) {
                if ($dokumenPath) {
                    Storage::disk('public')->delete($dokumenPath);
                }
                $dokumenPath = $request->file('dokumen_pendukung')
                    ->store('pengajuan-penghapusan', 'public');
            }

            $pengajuanPemusnahan->update([
                'aset_id'            => $request->aset_id,
                'sekolah_id'         => $request->sekolah_id,
                'alasan_penghapusan' => $request->alasan_penghapusan,
                'metode_penghapusan' => $request->metode_penghapusan,
                'jumlah_diajukan'    => $request->jumlah_diajukan,
                'keterangan'         => $request->keterangan,
                'dokumen_pendukung'  => $dokumenPath,
            ]);

            DB::commit();
            return redirect()->route('pengajuan-penghapusan-asset.index')
                ->with('success', 'Pengajuan berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Gagal memperbarui pengajuan penghapusan', [
                'pengajuan_id' => $pengajuanPemusnahan->id,
                'user_id'      => Auth::id(),
                'error'        => $e->getMessage(),
            ]);
            return back()->withInput()
                ->with('error', 'Gagal memperbarui pengajuan. Silakan coba lagi atau hubungi administrator.');
        }
    }

    // ── DESTROY ────────────────────────────────────────────────────────
    public function destroy(PengajuanPemusnahan $pengajuanPemusnahan)
    {
        if ($pengajuanPemusnahan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat dihapus.');
        }

        if (Auth::user()->hasRole('operator_sekolah') &&
            $pengajuanPemusnahan->diajukan_oleh !== Auth::id()) {
            abort(403);
        }

        $pengajuanPemusnahan->delete();

        return back()->with('success', 'Pengajuan berhasil dihapus.');
    }

    // ── VALIDASI (Admin: proses | Kepala Dinas: setujui/tolak) ────────
    public function validasi(Request $request, PengajuanPemusnahan $pengajuanPemusnahan)
    {
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            if ($pengajuanPemusnahan->status !== 'menunggu') {
                return back()->with('error', 'Hanya pengajuan berstatus "Menunggu" yang dapat diproses.');
            }

            $pengajuanPemusnahan->update([
                'status'           => 'diproses',
                'divalidasi_oleh'  => $user->id,
                'catatan_validasi' => $request->catatan_validasi,
                'tanggal_validasi' => now(),
            ]);

            return back()->with('success', 'Pengajuan berhasil diproses dan diteruskan ke Kepala Dinas.');
        }

        // Only kepala_dinas may approve/reject — explicit allow-list instead of
        // an implicit "not admin" fallthrough.
        if (!$user->hasRole('kepala_dinas')) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }

        $request->validate([
            'status'           => 'required|in:disetujui,ditolak',
            'catatan_validasi' => 'nullable|string|max:500',
        ]);

        if ($pengajuanPemusnahan->status !== 'diproses') {
            return back()->with('error', 'Hanya pengajuan berstatus "Diproses" yang dapat disetujui atau ditolak.');
        }

        $pengajuanPemusnahan->update([
            'status'           => $request->status,
            'divalidasi_oleh'  => $user->id,
            'catatan_validasi' => $request->catatan_validasi,
            'tanggal_validasi' => now(),
        ]);

        $label = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Pengajuan berhasil {$label}.");
    }

    // ── EXPORT EXCEL ───────────────────────────────────────────────────
    public function export(Request $request)
    {
        $request->validate([
            'mode' => 'required|in:minggu,bulan,tahun,rentang',
        ]);

        [$start, $end, $label] = $this->resolvePeriode($request);

        $query = PengajuanPemusnahan::with(['aset', 'sekolah', 'pengaju', 'validator'])
            ->whereBetween('created_at', [$start, $end]);

        if (Auth::user()->hasRole('operator_sekolah')) {
            $query->where('diajukan_oleh', Auth::id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('sekolah_id') && !Auth::user()->hasRole('operator_sekolah')) {
            $query->where('sekolah_id', $request->sekolah_id);
        }

        $pengajuans = $query->latest()->get();

        $filename = 'pengajuan-penghapusan-aset-' . Str::slug($label) . '.xls';

        return response()
            ->view('dashboard.pengajuan.export', compact('pengajuans', 'label', 'start', 'end'))
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Tentukan rentang tanggal & label berdasarkan mode export.
     *
     * @return array{0: Carbon, 1: Carbon, 2: string}
     */
    private function resolvePeriode(Request $request): array
    {
        $bulanId = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        switch ($request->mode) {
            case 'minggu':
                $request->validate([
                    'minggu' => ['nullable', 'regex:/^\d{4}-W(0[1-9]|[1-4][0-9]|5[0-3])$/'],
                ]);

                $val = $request->input('minggu') ?: now()->format('o-\WW');
                [$y, $w] = array_pad(explode('-W', $val), 2, null);

                if (!$y || !$w || (int) $w < 1 || (int) $w > 53) {
                    throw ValidationException::withMessages([
                        'minggu' => 'Format minggu tidak valid.',
                    ]);
                }

                $start = Carbon::now()->setISODate((int) $y, (int) $w)->startOfWeek();
                $end   = $start->copy()->endOfWeek();
                $label = "Minggu ke-{$w} Tahun {$y} ({$start->format('d/m/Y')} - {$end->format('d/m/Y')})";
                break;

            case 'bulan':
                $request->validate([
                    'bulan' => ['nullable', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
                ]);

                $val = $request->input('bulan') ?: now()->format('Y-m');

                try {
                    $start = Carbon::createFromFormat('Y-m', $val)->startOfMonth();
                } catch (\Throwable $e) {
                    throw ValidationException::withMessages([
                        'bulan' => 'Format bulan tidak valid.',
                    ]);
                }

                $end   = $start->copy()->endOfMonth();
                $label = 'Bulan ' . $bulanId[(int) $start->format('n')] . ' ' . $start->format('Y');
                break;

            case 'tahun':
                $request->validate([
                    'tahun' => ['nullable', 'integer', 'min:2000', 'max:2100'],
                ]);

                $y     = (int) ($request->input('tahun') ?: now()->year);
                $start = Carbon::create($y, 1, 1)->startOfDay();
                $end   = Carbon::create($y, 12, 31)->endOfDay();
                $label = "Tahun {$y}";
                break;

            case 'rentang':
            default:
                $request->validate([
                    'tanggal_mulai'   => 'required|date',
                    'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
                ]);
                $start = Carbon::parse($request->tanggal_mulai)->startOfDay();
                $end   = Carbon::parse($request->tanggal_selesai)->endOfDay();
                $label = "Periode {$start->format('d/m/Y')} s/d {$end->format('d/m/Y')}";
                break;
        }

        return [$start, $end, $label];
    }
}
