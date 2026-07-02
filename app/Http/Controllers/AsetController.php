<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{
    // -------------------------------------------------------
    // Daftar aset aktif
    // -------------------------------------------------------
    public function index()
    {
        $user  = Auth::user();
        $query = Aset::with('sekolah')->latest();

        if ($user->hasRole('operator_sekolah')) {
            $sekolah = \App\Models\Sekolah::where('operator_id', $user->id)->first();
            $query->where('sekolah_id', $sekolah?->id);
        }

        $aset = $query->paginate(15);

        return view('dashboard.data_asset.index', compact('aset'));
    }

    // -------------------------------------------------------
    // Daftar aset yang sudah dihapus (trash)
    // -------------------------------------------------------
    public function trash()
    {
        $aset = Aset::onlyTrashed()->with('sekolah')->latest('deleted_at')->paginate(15);

        return view('dashboard.data_asset.trash', compact('aset'));
    }

    // -------------------------------------------------------
    // Form tambah aset
    // -------------------------------------------------------
    public function create()
    {
        $user = Auth::user();

        if ($user->hasRole('operator_sekolah')) {
            $sekolahOperator = \App\Models\Sekolah::where('operator_id', $user->id)->first();
            $sekolahList     = collect();
        } else {
            $sekolahOperator = null;
            $sekolahList     = \App\Models\Sekolah::orderBy('nama_sekolah')->get();
        }

        return view('dashboard.data_asset.create', compact('sekolahList', 'sekolahOperator'));
    }

    // -------------------------------------------------------
    // Simpan aset baru
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $user = Auth::user();

        // Operator: paksa sekolah_id dari profil, tidak dari form
        if ($user->hasRole('operator_sekolah')) {
            $sekolah = \App\Models\Sekolah::where('operator_id', $user->id)->firstOrFail();
            $request->merge(['sekolah_id' => $sekolah->id]);
        }

        $validated = $request->validate([
            'sekolah_id'        => 'required|exists:sekolah,id',
            'nama_aset'         => 'required|string|max:255',
            'kategori'          => 'required|in:mebel,elektronik,sarana_pembelajaran',
            'tipe_barang'       => 'nullable|string|max:100',
            'kode_aset'         => 'nullable|string|max:100|unique:aset,kode_aset',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat',
            'jumlah'            => 'required|integer|min:1',
            'satuan'            => 'required|string|max:50',
            'tahun_pengadaan'   => 'nullable|digits:4|integer',
            'harga_perolehan'   => 'nullable|numeric|min:0',
            'lokasi'            => 'nullable|string|max:255',
            'keterangan'        => 'nullable|string',
            'foto_bukti'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Kode barang: pakai yang diisi manual, kalau kosong dibuat otomatis dari kategori
        if (empty($validated['kode_aset'])) {
            $validated['kode_aset'] = Aset::generateKode($validated['kategori']);
        }
        $validated['foto_bukti'] = $request->hasFile('foto_bukti')
            ? $request->file('foto_bukti')->store('aset/foto', 'public')
            : '';

        Aset::create($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    // -------------------------------------------------------
    // Form edit aset
    // -------------------------------------------------------
    public function edit(Aset $aset)
    {
        $user = Auth::user();

        if ($user->hasRole('operator_sekolah')) {
            $sekolah = \App\Models\Sekolah::where('operator_id', $user->id)->firstOrFail();
            if ($aset->sekolah_id !== $sekolah->id) abort(403);
            $sekolahOperator = $sekolah;
            $sekolahList     = collect();
        } else {
            $sekolahOperator = null;
            $sekolahList     = \App\Models\Sekolah::orderBy('nama_sekolah')->get();
        }

        return view('dashboard.data_asset.edit', compact('aset', 'sekolahList', 'sekolahOperator'));
    }

    // -------------------------------------------------------
    // Update aset
    // -------------------------------------------------------
    public function update(Request $request, Aset $aset)
    {
        $user = Auth::user();

        if ($user->hasRole('operator_sekolah')) {
            $sekolah = \App\Models\Sekolah::where('operator_id', $user->id)->firstOrFail();
            if ($aset->sekolah_id !== $sekolah->id) abort(403);
            $request->merge(['sekolah_id' => $sekolah->id]);
        }

        $validated = $request->validate([
            'sekolah_id'        => 'required|exists:sekolah,id',
            'nama_aset'         => 'required|string|max:255',
            'kategori'          => 'required|in:mebel,elektronik,sarana_pembelajaran',
            'tipe_barang'       => 'nullable|string|max:100',
            'kode_aset'         => 'nullable|string|max:100|unique:aset,kode_aset,' . $aset->id,
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat',
            'jumlah'            => 'required|integer|min:1',
            'satuan'            => 'required|string|max:50',
            'tahun_pengadaan'   => 'nullable|digits:4|integer',
            'harga_perolehan'   => 'nullable|numeric|min:0',
            'lokasi'            => 'nullable|string|max:255',
            'keterangan'        => 'nullable|string',
            'foto_bukti'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Kode barang: pakai yang diisi manual, kalau dikosongkan dibuat otomatis dari kategori
        if (empty($validated['kode_aset'])) {
            $validated['kode_aset'] = Aset::generateKode($validated['kategori']);
        }

        if ($request->hasFile('foto_bukti')) {
            if ($aset->foto_bukti) {
                Storage::disk('public')->delete($aset->foto_bukti);
            }
            $validated['foto_bukti'] = $request->file('foto_bukti')->store('aset/foto', 'public');
        } else {
            unset($validated['foto_bukti']);
        }

        $aset->update($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }

    // -------------------------------------------------------
    // Soft delete aset (pindah ke trash)
    // -------------------------------------------------------
    public function destroy(Aset $aset)
    {
        $aset->delete(); // hanya mengisi kolom deleted_at, data tetap ada di DB

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil dihapus dan dipindahkan ke tempat sampah.');
    }

    // -------------------------------------------------------
    // Pulihkan aset dari trash
    // -------------------------------------------------------
    public function restore($id)
    {
        $aset = Aset::onlyTrashed()->findOrFail($id);
        $aset->restore(); // mengosongkan kembali kolom deleted_at

        return redirect()->route('aset.trash')
            ->with('success', 'Aset berhasil dipulihkan.');
    }

    // -------------------------------------------------------
    // Hapus permanen dari database
    // -------------------------------------------------------
    public function forceDelete($id)
    {
        $aset = Aset::onlyTrashed()->findOrFail($id);
        $aset->forceDelete(); // benar-benar dihapus dari tabel

        return redirect()->route('aset.trash')
            ->with('success', 'Aset berhasil dihapus secara permanen.');
    }

    // -------------------------------------------------------
    // Kosongkan semua trash (hapus permanen semua aset terhapus)
    // -------------------------------------------------------
    public function emptyTrash()
    {
        Aset::onlyTrashed()->forceDelete();

        return redirect()->route('aset.trash')
            ->with('success', 'Semua aset di tempat sampah telah dihapus permanen.');
    }
}
