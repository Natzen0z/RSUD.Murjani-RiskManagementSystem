<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SsoCallbackController extends Controller
{
    /**
     * Handle the SSO Callback from Auth Portal
     */
    public function handle(Request $request)
    {
        $token = $request->query('token');
        $redirect = $request->query('redirect', route('dashboard')); // fallback to dashboard if not provided

        if (!$token) {
            return redirect('/')->with('error', 'SSO Token tidak ditemukan.');
        }

        // 1. Verifikasi Token ke Auth Portal
        $portalUrl = config('services.auth_portal.url');
        $appSecret = config('services.auth_portal.app_secret');

        try {
            // Sesuai standar: Bearer = APP_SECRET_TOKEN, Body = { "sso_token": "..." }
            $response = Http::withToken($appSecret)->post("{$portalUrl}/api/sso/verify", [
                'sso_token' => $token
            ]);

            if (!$response->successful()) {
                return redirect('/')->with('error', 'Verifikasi SSO gagal: Token tidak valid atau kadaluarsa.');
            }

            $data = $response->json();

            if (!$data['success']) {
                return redirect('/')->with('error', 'Verifikasi SSO gagal: ' . ($data['message'] ?? ''));
            }

            $userData = $data['data']['user'];

            // 2. Cari atau Buat User secara Lokal
            $user = User::firstOrCreate(
                ['username' => $userData['username']],
                [
                    'name'  => $userData['name'],
                    'nip'   => $userData['nip'] ?? $userData['username'],
                    'email' => $userData['email'] ?? null,
                    'role'  => $userData['role'] ?? 'User',
                    'password' => bcrypt($userData['username']), // default password, won't be used for SSO
                ]
            );

            // Update nama terbaru sesuai dengan data dari Portal (opsional)
            $user->update(['name' => $userData['name']]);

            // Buat session lokal
            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended($redirect);

        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Terjadi kesalahan saat terhubung ke Auth Portal.');
        }
    }

    /**
     * Handle Logout dari Aplikasi Klien
     */
    public function logout(Request $request)
    {
        // 1. Hapus session lokal di aplikasi klien
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // 2. Redirect kembali ke Auth Portal
        $portalUrl = config('services.auth_portal.url');
        return redirect($portalUrl);
    }
}
