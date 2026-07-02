<?php

namespace App\Http\Controllers;

use App\Models\Kabupaten;
use App\Models\Kecamatan;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SekolahController extends Controller
{
    public function mySekolah()
    {
        $sekolah = Sekolah::with(['kecamatan', 'kabupaten'])
            ->where('operator_id', Auth::id())
            ->get();

        return view('dashboard.sekolah.my', compact('sekolah'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->search;

        $sekolah = Sekolah::with('operator')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nama_sekolah', 'LIKE', "%{ $search }%")
                        ->orWhere('npsn_sekolah', 'LIKE', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10);
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
            "nama_sekolah" => "required|string",
            "npsn_sekolah" => "required|string|unique:sekolah,npsn_sekolah|unique:users,login_id",
            "alamat_sekolah" => "required|string",
            "kecamatan_id" => "nullable|exists:kecamatan,id",
            "kabupaten_id" => "nullable|exists:kabupaten,id",
            "jenjang_sekolah" => "nullable|in:PAUD,SD,SMP",
            "scope_pengelola" => "nullable|in:kecamatan,kabupaten",
            "operator_id" => "nullable|exists:users,id",
        ]);

        DB::transaction(function () use ($request) {
            $operator = User::create([
                'name' => $request->nama_sekolah,
                'email' => $request->nama_sekolah . 'sch.id',
                'login_id' => $request->npsn_sekolah,
                'password' => bcrypt($request->npsn_sekolah),
            ]);

            $operator->assignRole('operator_sekolah');

            Sekolah::create([
                'nama_sekolah' => $request->nama_sekolah,
                'npsn_sekolah' => $request->npsn_sekolah,
                "alamat_sekolah" => $request->alamat_sekolah,
                "kecamatan_id" =>  $request->kecamatan_id,
                "kabupaten_id" =>  $request->kabupaten_id,
                "jenjang_sekolah" =>  $request->jenjang_sekolah,
                "scope_pengelola" =>  $request->scope_pengelola,
                "operator_id" =>  $operator->id,
            ]);
        });


        return redirect()->route("sekolah")->with("success", "Sekolah berhasil ditambahkan");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::all();
        $operators = User::with('roles')->get();

        return view('dashboard.sekolah.show', compact('sekolah', 'kabupaten', 'kecamatan', 'operators'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $kabupaten = Kabupaten::all();
        $kecamatan = Kecamatan::all();
        $operators = User::with('roles')->get();

        return view('dashboard.sekolah.edit', compact('sekolah', 'kabupaten', 'kecamatan', 'operators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        $request->validate([
            'nama_sekolah' => 'required|string',
            'npsn_sekolah' => 'required|string|unique:sekolah,npsn_sekolah,' . $sekolah->id . '|unique:users,login_id,' . $sekolah->operator_id,
            'alamat_sekolah' => 'required|string',
            'kecamatan_id' => 'nullable|exists:kecamatan,id',
            'kabupaten_id' => 'nullable|exists:kabupaten,id',
            'jenjang_sekolah' => 'required|in:PAUD,SD,SMP',
            'scope_pengelola' => 'required|in:kecamatan,kabupaten',
        ]);

        DB::transaction(function () use ($request, $sekolah) {
            $sekolah->update($request->only([
                'nama_sekolah', 'npsn_sekolah', 'alamat_sekolah',
                'kecamatan_id', 'kabupaten_id', 'jenjang_sekolah', 'scope_pengelolaan',
            ]));

            if ($sekolah->operator) {
                $sekolah->operator->update([
                    'nama' => $request->npsn_sekolah
                ]);
            }
        });


        return redirect()->route('sekolah')->with('success', 'Sekolah berhasil diperbaharui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $sekolah = Sekolah::findOrFail($id);

        DB::transaction(function () use ($sekolah) {
            $sekolah->operator?->delete();
            $sekolah->delete();
        });

        return redirect()->route('sekolah')->with('success', 'Sekolah berhasil dihapus.');
    }
}
