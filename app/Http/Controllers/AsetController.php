<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsetController extends Controller
{
    // -------------------------------------------------------
    // Daftar aset aktif
    // -------------------------------------------------------
    public function index()
    {
        $aset = Aset::with('sekolah')->latest()->paginate(15);

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
        $sekolahList = \App\Models\Sekolah::orderBy('nama_sekolah')->get();

        return view('dashboard.data_asset.create', compact('sekolahList'));
    }

    // -------------------------------------------------------
    // Simpan aset baru
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sekolah_id'        => 'required|exists:sekolah,id',
            'nama_aset'         => 'required|string|max:255',
            'kode_aset'         => 'nullable|string|max:100|unique:aset,kode_aset',
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat',
            'jumlah'            => 'required|integer|min:1',
            'satuan'            => 'required|string|max:50',
            'tahun_pengadaan'   => 'nullable|digits:4|integer',
            'harga_perolehan'   => 'nullable|numeric|min:0',
            'lokasi'            => 'nullable|string|max:255',
            'keterangan'        => 'nullable|string',
        ]);

        Aset::create($validated);

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    // -------------------------------------------------------
    // Form edit aset
    // -------------------------------------------------------
    public function edit(Aset $aset)
    {
        $sekolahList = \App\Models\Sekolah::orderBy('nama_sekolah')->get();

        return view('dashboard.data_asset.edit', compact('aset', 'sekolahList'));
    }

    // -------------------------------------------------------
    // Update aset
    // -------------------------------------------------------
    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'sekolah_id'        => 'required|exists:sekolah,id',
            'nama_aset'         => 'required|string|max:255',
            'kode_aset'         => 'nullable|string|max:100|unique:aset,kode_aset,' . $aset->id,
            'kondisi'           => 'required|in:baik,rusak_ringan,rusak_berat',
            'jumlah'            => 'required|integer|min:1',
            'satuan'            => 'required|string|max:50',
            'tahun_pengadaan'   => 'nullable|digits:4|integer',
            'harga_perolehan'   => 'nullable|numeric|min:0',
            'lokasi'            => 'nullable|string|max:255',
            'keterangan'        => 'nullable|string',
        ]);

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
