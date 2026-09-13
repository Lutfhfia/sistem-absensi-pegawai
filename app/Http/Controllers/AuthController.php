<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_id' => 'required|string',
            'device_name' => 'required|string|max:255',
            'device_platform' => 'required|string|max:100',
            'device_browser' => 'required|string|max:100',
            'device_latitude' => 'required|numeric|between:-90,90',
            'device_longitude' => 'required|numeric|between:-180,180',
        ]);

        $remember = $request->has('remember');

        if (!Auth::attempt(
            [
                'email' => $credentials['email'],
                'password' => $credentials['password'],
            ],
            $remember
        )) {
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Email atau password salah!'
                ]);
        }

        $user = Auth::user();

        // GPS wajib untuk pegawai
        if ($user->role === 'pegawai') {

            if (
                empty($credentials['device_latitude']) ||
                empty($credentials['device_longitude'])
            ) {
                Auth::logout();

                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'Lokasi wajib diaktifkan untuk login. Silakan aktifkan GPS dan izinkan browser mengakses lokasi.'
                    ]);
            }


            // Device lock
            if (empty($user->device_id)) {

                $user->update([
                    'device_id' => $credentials['device_id'],
                    'device_name' => $credentials['device_name'],
                    'device_platform' => $credentials['device_platform'],
                    'device_browser' => $credentials['device_browser'],
                    'device_ip' => $request->ip(),
                    'device_latitude' => $credentials['device_latitude'],
                    'device_longitude' => $credentials['device_longitude'],
                    'device_last_login' => now(),
                ]);

            } elseif ($user->device_id !== $credentials['device_id']) {

                Auth::logout();

                $request->session()->invalidate();

                return back()
                    ->withInput($request->only('email', 'remember'))
                    ->withErrors([
                        'email' => 'AKUN DIKUNCI! Anda hanya boleh login dari perangkat yang sudah terdaftar. Hubungi Admin untuk reset perangkat.'
                    ]);
            } else {

                $user->update([
                    'device_name' => $credentials['device_name'],
                    'device_platform' => $credentials['device_platform'],
                    'device_browser' => $credentials['device_browser'],
                    'device_ip' => $request->ip(),
                    'device_latitude' => $credentials['device_latitude'],
                    'device_longitude' => $credentials['device_longitude'],
                    'device_last_login' => now(),
                ]);
            }
        }

        $request->session()->regenerate();

        if ($user->role === 'super_admin') {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/absen');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
