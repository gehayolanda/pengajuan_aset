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
            'password' => 'required',
        ], [
            'login_id.required' => 'Login gagal, silahkan cek kembali User ID dan Password',
            'password.required' => 'Login gagal, silahkan cek kembali User ID dan Password',
        ]);

        $login = trim((string) $request->login_id);
        $credentials = [
            ['login_id' => $login, 'password' => $request->password],
            ['email' => $login, 'password' => $request->password],
        ];

        foreach ($credentials as $attempt) {
            if (Auth::attempt($attempt, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()
                    ->intended(route('dashboard'))
                    ->with('success', 'Login berhasil, selamat datang di SI-PPASET.');
            }
        }

        return back()->withErrors([
            'login_id' => 'Login gagal, silahkan cek kembali User ID dan Password',
        ])->withInput($request->only('login_id', 'remember'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
