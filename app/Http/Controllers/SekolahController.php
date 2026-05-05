<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;

class SekolahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sekolah = Sekolah::with(['operator'])->orderBy('id', 'desc')->get();
        return view("dashboard.sekolah.index", compact("sekolah"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::all();
        $operators = User::with('roles')->get();
        return view("dashboard.sekolah.create", compact('kabupaten', 'kecamatan', 'operators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nama_sekolah"=> "required|string",
            "npsn_sekolah" => "required|string|unique:sekolah,npsn_sekolah",
            "alamat_sekolah" => "required|string",
            "kecamatan_id" => "nullable|exists:kecamatan,id",
            "kabupaten_id" => "nullable|exists:kabupaten,id",
            "jenjang_sekolah" => "nullable|in:PAUD,SD,SMP",
            "scope_pengelola" => "nullable|in:kecamatan,kabupaten",
            "operator_id" => "nullable|exists:users,id",
        ]);

        $data = $request->all();
        $sekolah = new Sekolah();

        $sekolah->create($data);

        return redirect()->route("sekolah")->with("success","Sekolah berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
