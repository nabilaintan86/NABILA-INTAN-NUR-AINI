<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Redirect ke halaman yang sesuai berdasarkan role user.
     */
    private function redirectByRole(): \Illuminate\Http\RedirectResponse
    {
        $role = Auth::user()->role ?? 'staff';

        return match ($role) {
            'super_admin', 'admin' => redirect()->route('admin.dashboard'),
            default                => redirect()->route('admin.monitoring'),
        };
    }

    public function showLoginForm()
    {
        // Jika sudah login, langsung redirect ke dashboard sesuai role
        if (Auth::check()) {
            return $this->redirectByRole();
        }

        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return redirect()->route('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();

            // Jika ada intended URL (dari middleware auth), gunakan itu
            // Jika tidak ada, arahkan sesuai role
            $intended = session()->pull('url.intended');
            if ($intended) {
                return redirect($intended);
            }

            return $this->redirectByRole();
        }

        return back()
            ->withErrors([
                'email' => 'Email atau password salah.',
            ])
            ->withInput($request->only('email'))
            ->with('auth_tab', 'login');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        $request->session()->regenerate();

        return $this->redirectByRole()
            ->with('success', 'Akun berhasil dibuat. Selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
public function showForgotForm()
{
    return view('auth.forgot-password');
}

public function sendResetLink(Request $request)
{
    $request->validate(['email' => 'required|email']);

    // Untuk saat ini, tidak kirim email beneran.
    // Cukup tampilkan pesan ke user.
    return back()->with(
        'status',
        'Permintaan reset password sudah tercatat. Silakan hubungi Admin IT untuk proses lebih lanjut.'
    );
}
}