<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::with('kabupaten')->orderBy('id', 'asc')->paginate(10);
        return view('dashboard.kecamatan.index', compact('kecamatan'));
    }

    public function create()
    {
        $kecamatan = Kecamatan::with('kabupaten')->orderBy('id', 'asc')->get();
        return view('dashboard.kecamatan.create', compact('kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan' => 'required|string',
            'kabupaten_id'   => 'required|exists:kabupaten,id',
        ], [
            'nama_kecamatan.required' => 'Please fill out this field',
            'nama_kecamatan.string'   => 'Nama kecamatan harus berupa teks',
            'kabupaten_id.required'   => 'Please fill out this field',
            'kabupaten_id.exists'     => 'Kabupaten tidak ditemukan',
        ]);

        $kecamatan = new Kecamatan();
        $kecamatan->nama_kecamatan = $request->nama_kecamatan;
        $kecamatan->kabupaten_id   = $request->kabupaten_id;
        $kecamatan->save();

        return redirect()->route('kecamatan')->with('success', 'Kecamatan Berhasil Ditambahkan');
    }

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();
        return redirect()->route('kecamatan')->with('success', 'Kecamatan Berhasil Dihapus');
    }
}
