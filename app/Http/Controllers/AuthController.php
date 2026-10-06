<?php

namespace App\Http\Controllers;

use App\Models\TblUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLogin()
    {
        if (Auth::check() || session('user_id')) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    /**
     * Handle login authentication.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = TblUser::where('username', $credentials['username'])->first();

        $passwordValid = false;
        if ($user) {
            // Check bcrypt
            if (Hash::check($credentials['password'], $user->password)) {
                $passwordValid = true;
            } elseif (md5($credentials['password']) === $user->password) {
                // Upgrade MD5 to bcrypt automatically
                $user->password = Hash::make($credentials['password']);
                $user->save();
                $passwordValid = true;
            } elseif ($credentials['password'] === $user->password) {
                // Plaintext upgrade to bcrypt
                $user->password = Hash::make($credentials['password']);
                $user->save();
                $passwordValid = true;
            }
        }

        if ($user && $passwordValid) {
            // Check if user is blocked
            if (strtoupper($user->blokir) === 'Y') {
                return back()->withErrors(['username' => 'Akun Anda sedang diblokir. Hubungi administrator.'])->withInput();
            }

            Auth::login($user);
            session([
                'user_id' => $user->iduser,
                'user_name' => $user->nama,
                'username' => $user->username,
                'user_role' => $user->leveluser,
            ]);

            // Record login log and update last login
            try {
                DB::table('log_login')->insert([
                    'iduser' => $user->iduser,
                    'ip' => $request->ip(),
                    'tgl' => now(),
                ]);
                $user->login = now();
                $user->save();
            } catch (\Throwable $e) {
                // Log table optional
            }

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali, ' . ($user->nama ?: $user->username) . '!');
        }

        return back()->withErrors([
            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->withInput($request->only('username'));
    }

    /**
     * Handle sign out / logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar (Sign Out).');
    }

    /**
     * Show Forgot Password view.
     */
    public function showForgotPassword()
    {
        if (Auth::check() || session('user_id')) {
            return redirect()->route('home');
        }

        return view('auth.forgot_password');
    }

    /**
     * Handle Forgot Password / Reset Password request.
     */
    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string',
            'telp' => 'nullable|string',
            'password' => 'required|string|min:4|confirmed',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru minimal 4 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = TblUser::where('username', $request->username)->first();

        if (!$user) {
            return back()->withErrors([
                'username' => 'Akun dengan username tersebut tidak ditemukan dalam sistem.',
            ])->withInput($request->only('username', 'telp'));
        }

        // If user has a registered phone number, verify it
        if (!empty(trim($user->telp))) {
            $cleanedInputPhone = preg_replace('/[^0-9]/', '', (string)$request->telp);
            $cleanedUserPhone = preg_replace('/[^0-9]/', '', (string)$user->telp);

            if (empty($cleanedInputPhone) || (!str_contains($cleanedUserPhone, $cleanedInputPhone) && !str_contains($cleanedInputPhone, $cleanedUserPhone))) {
                return back()->withErrors([
                    'telp' => 'Nomor telepon/ext tidak sesuai dengan data terdaftar untuk akun ini.',
                ])->withInput($request->only('username', 'telp'));
            }
        }

        // Update password with secure bcrypt
        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('login')->with('success', 'Kata sandi akun ' . $user->username . ' berhasil diperbarui! Silakan masuk dengan kata sandi baru Anda.');
    }
}

