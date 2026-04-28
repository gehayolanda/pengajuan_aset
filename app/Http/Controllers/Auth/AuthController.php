<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.index');
    }

    public function loginProses(Request $request)
    {
        $request->validate([
            'login_id' => 'required',
            'password'=> 'required',
        ]);

        $login = $request->login_id;

        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'login_id';

        if (Auth::attempt([$field => $login, 'password' => $request->password,], $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->route('dashboard');

        }

        return back()->withErrors([
            'login_id' => 'User ID atau Password salah',
        ]);
    }

}
