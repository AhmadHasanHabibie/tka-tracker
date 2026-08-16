<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $throttleKey = 'login:' . $request->ip() . '|' . mb_strtolower($request->input('username'));

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            \App\Services\ActivityLogger::log('Rate Limit Login', 'Percobaan login melebihi batas dari IP ' . $request->ip() . ' untuk username: ' . $request->username);
            
            return back()
                ->withErrors(['login' => 'Terlalu banyak percobaan login gagal. Silakan coba lagi dalam ' . $seconds . ' detik.'])
                ->withInput($request->only('username'));
        }

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            $user = Auth::user();

            \App\Services\ActivityLogger::log('User Login', 'User ' . $user->username . ' berhasil login (Role: ' . ($user->role ?? 'siswa') . ')');

            if ($user->isAdmin()) {
                return redirect()->route('admin.pin.show');
            }

            return redirect()->route('dashboard');
        }

        RateLimiter::hit($throttleKey, 60);
        \App\Services\ActivityLogger::log('Gagal Login', 'Percobaan login gagal dengan username: ' . $request->username);

        return back()
            ->withErrors(['login' => 'Username atau password salah.'])
            ->withInput($request->only('username'));
    }

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        $username = Auth::user()?->username;
        \App\Services\ActivityLogger::log('User Logout', 'User ' . ($username ?? '') . ' melakukan logout');

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
