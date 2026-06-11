<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\Sekolah;
use App\Models\PengajuanPemusnahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PengajuanPemusnahanController extends Controller
{
    // ── INDEX ──────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = PengajuanPemusnahan::with(['aset', 'sekolah', 'pengaju']);

        // Operator sekolah hanya lihat miliknya sendiri
        if (Auth::user()->hasRole('operator_sekolah')) {
            $query->where('diajukan_oleh', Auth::id());
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter sekolah (admin & kepala_dinas)
        if ($request->filled('sekolah_id') && !Auth::user()->hasRole('operator_sekolah')) {
            $query->where('sekolah_id', $request->sekolah_id);
        }

        // Search
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

        // Operator sekolah: hanya aset sekolahnya sendiri
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
            'aset_id'             => 'required|exists:aset,id',
            'sekolah_id'          => 'required|exists:sekolah,id',
            'alasan_penghapusan'  => 'required|string|max:255',
            'metode_penghapusan'  => 'required|in:pemusnahan,lelang,hibah,tukar_tambah',
            'jumlah_diajukan'     => 'required|integer|min:1',
            'keterangan'          => 'nullable|string',
            'dokumen_pendukung'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $dokumenPath = null;
            if ($request->hasFile('dokumen_pendukung')) {
                $dokumenPath = $request->file('dokumen_pendukung')
                    ->store('pengajuan-penghapusan', 'public');
            }

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
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal membuat pengajuan: ' . $e->getMessage());
        }
    }

    // ── SHOW ───────────────────────────────────────────────────────────
    public function show(PengajuanPemusnahan $PengajuanPemusnahan)
    {
        $PengajuanPemusnahan->load(['aset', 'sekolah', 'pengaju', 'validator']);
        return view('dashboard.pengajuan.show', [
            'pengajuan' => $PengajuanPemusnahan,
        ]);
    }

    // ── EDIT ───────────────────────────────────────────────────────────
    public function edit(PengajuanPemusnahan $PengajuanPemusnahan)
    {
        // Hanya bisa diedit jika masih 'menunggu'
        if ($PengajuanPemusnahan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat diubah.');
        }

        // Operator sekolah hanya bisa edit miliknya sendiri
        if (Auth::user()->hasRole('operator_sekolah') &&
            $PengajuanPemusnahan->diajukan_oleh !== Auth::id()) {
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
            'pengajuan' => $PengajuanPemusnahan,
            'asets'     => $asets,
            'sekolahs'  => $sekolahs,
            'metodes'   => $metodes,
        ]);
    }

    // ── UPDATE ─────────────────────────────────────────────────────────
    public function update(Request $request, PengajuanPemusnahan $PengajuanPemusnahan)
    {
        if ($PengajuanPemusnahan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat diubah.');
        }

        if (Auth::user()->hasRole('operator_sekolah') &&
            $PengajuanPemusnahan->diajukan_oleh !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'aset_id'             => 'required|exists:aset,id',
            'sekolah_id'          => 'required|exists:sekolah,id',
            'alasan_penghapusan'  => 'required|string|max:255',
            'metode_penghapusan'  => 'required|in:pemusnahan,lelang,hibah,tukar_tambah',
            'jumlah_diajukan'     => 'required|integer|min:1',
            'keterangan'          => 'nullable|string',
            'dokumen_pendukung'   => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $dokumenPath = $PengajuanPemusnahan->dokumen_pendukung;

            if ($request->hasFile('dokumen_pendukung')) {
                // Hapus dokumen lama
                if ($dokumenPath) {
                    Storage::disk('public')->delete($dokumenPath);
                }
                $dokumenPath = $request->file('dokumen_pendukung')
                    ->store('pengajuan-penghapusan', 'public');
            }

            $PengajuanPemusnahan->update([
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
            return back()->withInput()->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    // ── DESTROY ────────────────────────────────────────────────────────
    public function destroy(PengajuanPemusnahan $PengajuanPemusnahan)
    {
        if ($PengajuanPemusnahan->status !== 'menunggu') {
            return back()->with('error', 'Pengajuan yang sudah divalidasi tidak dapat dihapus.');
        }

        if (Auth::user()->hasRole('operator_sekolah') &&
            $PengajuanPemusnahan->diajukan_oleh !== Auth::id()) {
            abort(403);
        }

        $PengajuanPemusnahan->delete();

        return back()->with('success', 'Pengajuan berhasil dihapus.');
    }

    // ── VALIDASI (Admin / Kepala Dinas) ────────────────────────────────
    public function validasi(Request $request, PengajuanPemusnahan $PengajuanPemusnahan)
    {
        $request->validate([
            'status'           => 'required|in:disetujui,ditolak',
            'catatan_validasi'  => 'nullable|string|max:500',
        ]);

        $PengajuanPemusnahan->update([
            'status'            => $request->status,
            'divalidasi_oleh'   => Auth::id(),
            'catatan_validasi'  => $request->catatan_validasi,
            'tanggal_validasi'  => now(),
        ]);

        $label = $request->status === 'disetujui' ? 'disetujui' : 'ditolak';
        return back()->with('success', "Pengajuan berhasil {$label}.");
    }
}
