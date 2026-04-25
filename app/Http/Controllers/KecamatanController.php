<?php

namespace App\Http\Controllers;

use App\Models\Kecamatan;
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        $kecamatan = Kecamatan::with("kabupaten")->orderBy('id', 'asc')->get();
        return view('dashboard.kecamatan.index', compact('kecamatan'));
    }

    public function create()
    {
        $kecamatan = Kecamatan::with('kabupaten')->orderBy('id','asc')->get();
        return view('dashboard.kecamatan.create', compact('kecamatan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kecamatan'=>'required|string',
            'kabupaten_id' => 'required|exists:kabupaten,id'
        ]);

      $kecamatan = new Kecamatan();
      $kecamatan->nama_kecamatan = $request->nama_kecamatan;
      $kecamatan->kabupaten_id = $request->kabupaten_id;
      $kecamatan->save();

      return redirect()->route('kecamatan')->with('success','Data berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $kecamatan = Kecamatan::findOrFail($id);
        $kecamatan->delete();

        return redirect()->route('kecamatan')->with('success', 'Data berhasil dihapus');
    }
}

