<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class OperatorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        $operators = User::role('operator_sekolah')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->paginate(10);
        return view('dashboard.operator.index', compact('operators'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'admin')->get();
        return view('dashboard.operator.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'login_id' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'login_id' => $validated['login_id'],
            'password' => bcrypt($validated['password']),
        ]);

        $user->assignRole('operator_sekolah');

        return redirect()->route('operator')->with('success', 'Berhasil menambahkan data');
    }

    public function edit(Request $request, $id)
    {
        $operator = User::role('operator_sekolah')->findOrFail($id);
        return view('dashboard.operator.edit', compact('operator'));
    }

    public function update(Request $request, $id)
    {
        $operator = User::role('operator_sekolah')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'login_id' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $operator->id,
            'password' => 'nullable|min:8|confirmed',
        ]);

        $operator->update([
            'name' => $validated['name'],
            'login_id' => $validated['login_id'],
            'email' => $validated['email'],
        ]);

        if ($request->filled('password')) {
            $operator->update([
                'password' => bcrypt($request->password),
            ]);
        }

        return redirect()->route('operator')->with('success', 'Data operator berhasil diperbarui.');
    }

    public function destroy(Request $request, $id)
    {
        $operator = User::role('operator_sekolah')->findOrFail($id);

        $operator->delete();

        return redirect()->route('operator')->with('success', 'Berhasil hapus user');
    }
}
