# Epic 10 — Panduan Integrasi SSO untuk Aplikasi Klien

> **Auth Portal URL (Lokal):** `http://127.0.0.1:8000` (atau `http://auth-portal.test` jika via Laragon)
>
> Dokumen ini adalah panduan langkah demi langkah untuk menghubungkan aplikasi Laravel Anda (misalnya: SPJMurjani, HR App) ke Auth Portal sebagai Identity Provider (IdP).

---

## Pilih Pendekatan Integrasi

Ada **2 pendekatan** yang bisa Anda pilih berdasarkan kebutuhan:

| Pendekatan | Kompleksitas | Keamanan | Cocok Untuk |
|---|---|---|---|
| **A – Link Only** | Rendah | Rendah | Aplikasi tidak butuh verifikasi user |
| **B – SSO Token Callback** | Sedang | Tinggi | Aplikasi butuh mengenali siapa yang login |

---

## Pendekatan A — Link Only (Paling Sederhana)

User diklik dari Portal → langsung diarahkan ke URL aplikasi tanpa verifikasi token.

### Kapan Digunakan?
- Aplikasi sudah punya sistem login sendiri dan tidak perlu SSO.
- Anda hanya ingin Portal Auth menjadi *launcher hub*.

### Langkah-Langkah:

**Langkah 1 — Daftarkan aplikasi di Admin Portal**
1. Login ke Auth Portal sebagai Admin.
2. Buka menu **Kelola Aplikasi** → **Tambah Aplikasi**.
3. Isi:
   - Nama: `SPJ Murjani`
   - Slug: `spj-murjani`
   - Local URL: `http://spj-murjani.test`
   - Production URL: *(opsional)*
   - SSO Callback URL: **biarkan kosong**
4. Simpan.

**Langkah 2 — Tugaskan user ke aplikasi**
1. Buka menu **Kelola Pengguna** → pilih user → **Detail & Akses**.
2. Pilih aplikasi `SPJ Murjani` → klik **Tambahkan**.

**Langkah 3 — Selesai!**
User yang sudah ditugaskan akan melihat kartu aplikasi di Dashboard. Saat diklik, Portal akan redirect langsung ke `local_url` aplikasi.

> **Catatan:** Tidak ada kode yang perlu ditambahkan di aplikasi klien.

---

## Pendekatan B — SSO Token Callback (Direkomendasikan)

User diklik dari Portal → Portal mengirim token ke aplikasi klien → Aplikasi klien memverifikasi token ke Auth Portal → Buat session lokal.

### Kapan Digunakan?
- Aplikasi perlu tahu **siapa** yang sedang login (nama, NIP, role).
- Anda ingin user **tidak perlu login ulang** di aplikasi klien.
- Anda sedang membangun **SPJMurjani** atau aplikasi internal lainnya.

### Langkah-Langkah:

---

### Langkah 1 — Daftarkan Aplikasi di Admin Portal

1. Login ke Auth Portal → **Kelola Aplikasi** → **Tambah Aplikasi**.
2. Isi:
   - Nama: `SPJ Murjani`
   - Slug: `spj-murjani`
   - Local URL: `http://spj-murjani.test`
   - SSO Callback URL: `http://spj-murjani.test/login/sso/callback`
3. Simpan → **Salin nilai `App Secret`** dari halaman Detail Aplikasi.

---

### Langkah 2 — Tambahkan Config ke `.env` Aplikasi Klien

Buka file `.env` di project SPJMurjani (atau aplikasi klien Anda) dan tambahkan:

```env
# Auth Portal SSO Config
AUTH_PORTAL_URL=http://127.0.0.1:8000
AUTH_PORTAL_APP_SECRET=<paste_app_secret_disini>
```

---

### Langkah 3 — Buat Route SSO Callback di Aplikasi Klien

Di file `routes/web.php` aplikasi klien, tambahkan:

```php
// SSO Callback dari Auth Portal
Route::get('/login/sso/callback', [App\Http\Controllers\SsoCallbackController::class, 'handle'])
    ->name('sso.callback');
```

---

### Langkah 4 — Buat SsoCallbackController di Aplikasi Klien

Buat file `app/Http/Controllers/SsoCallbackController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class SsoCallbackController extends Controller
{
    public function handle(Request $request)
    {
        $token    = $request->query('token');
        $redirect = $request->query('redirect', '/dashboard');

        if (!$token) {
            return redirect('/login')->withErrors('Token SSO tidak ditemukan.');
        }

        // Verifikasi token ke Auth Portal
        $response = Http::withToken($token)->post(config('services.auth_portal.url') . '/api/sso/verify', [
            'app_secret' => config('services.auth_portal.app_secret'),
        ]);

        if (!$response->successful() || !$response->json('success')) {
            return redirect('/login')->withErrors('Token SSO tidak valid atau kadaluarsa.');
        }

        $userData = $response->json('data.user');

        // Skenario A (Auto-Provisioning): Cari user lokal, jika tidak ada maka buat baru
        $user = User::firstOrCreate(
            ['username' => $userData['username']],
            [
                'name'  => $userData['name'],
                'nip'   => $userData['nip'] ?? null,
                'email' => $userData['email'] ?? null,
                // Berikan role default untuk user baru (sesuaikan nama kolom dan value dengan aplikasi Anda)
                // 'role'  => 'Guest',
            ]
        );

        // Update nama terbaru sesuai dengan data dari Portal (opsional)
        $user->update(['name' => $userData['name']]);

        // Buat session lokal
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended($redirect);
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
        // (Akan otomatis masuk ke halaman login jika di portal juga sudah logout, 
        // atau kembali ke Dashboard Portal jika masih login di sana)
        return redirect(config('services.auth_portal.url'));
    }
}
```

---

### Langkah 4B — Update Route Logout di Aplikasi Klien

Arahkan tombol/route logout aplikasi Anda ke fungsi logout yang baru saja dibuat.

Di `routes/web.php` aplikasi klien:
```php
// Route logout lokal yang mengarah kembali ke portal
Route::post('/logout', [App\Http\Controllers\SsoCallbackController::class, 'logout'])->name('logout');
```

---

### Langkah 4C — Redirect Halaman Login Lokal ke Portal (Wajib)

Agar user yang mengakses URL aplikasi Anda secara langsung (tanpa melalui *dashboard* portal) tetap harus login via SSO, Anda wajib mengalihkan rute *login* lokal ke PortalMurjani.

Di `routes/web.php` aplikasi klien, ganti atau timpa rute `login` bawaan menjadi:

```php
// Memaksa user yang belum login agar diarahkan ke PortalMurjani
Route::get('/login', function () {
    $portalUrl = config('services.auth_portal.url');
    // Tambahkan path /launch/{slug-aplikasi} agar portal otomatis mengembalikan user ke aplikasi ini
    // Contoh: return redirect()->away($portalUrl . '/launch/spj-murjani');
    return redirect()->away($portalUrl . '/launch/<slug-aplikasi>');
})->name('login');

// PENTING: Pastikan route '/' Anda tidak memaksa redirect ke login jika user sudah login!
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard'); // Sesuaikan dengan route dashboard Anda
    }
    return redirect()->route('login');
});
```
*Catatan: Setelah user login di Portal, mereka akan melihat daftar aplikasi di Dashboard Portal dan bisa mengklik aplikasi Anda untuk masuk. Pastikan root route (`/`) menangani user yang sudah login dengan benar agar tidak terjadi redirect loop kembali ke Portal.*

---

### Langkah 5 — Tambahkan Config `services.php` di Aplikasi Klien

Di `config/services.php` aplikasi klien, tambahkan:

```php
'auth_portal' => [
    'url'        => env('AUTH_PORTAL_URL', 'http://127.0.0.1:8000'),
    'app_secret' => env('AUTH_PORTAL_APP_SECRET'),
],
```

---

### Langkah 6 — Sesuaikan User Model Aplikasi Klien

Pastikan tabel `users` di aplikasi klien memiliki kolom `username` dan `nip` (opsional).

Jika belum ada, buat migration:

```php
// php artisan make:migration add_sso_fields_to_users_table
Schema::table('users', function (Blueprint $table) {
    $table->string('username')->nullable()->unique()->after('name');
    $table->string('nip')->nullable()->after('username');
    // password boleh nullable karena user SSO tidak punya password lokal
    $table->string('password')->nullable()->change();
});
```

---

### Langkah 6B — Migrasi Data untuk Aplikasi yang Sudah Berjalan

Jika aplikasi Anda sudah di-deploy, berjalan, dan memiliki data pengguna (misal: menggunakan login lokal/email sebelumnya), Anda perlu melakukan modifikasi satu kali (*one-time data mapping*) agar data user yang lama tidak hilang atau terduplikasi saat SSO diterapkan.

**Cara Menangani User Lama (Mapping Identity):**
1. **Identifikasi Kunci Unik:** Pastikan Anda telah menambahkan kolom `nip` atau `username` pada tabel `users` lokal (seperti Langkah 6).
2. **Sinkronisasi Data:** Buat sebuah script Artisan Command / Seeder sederhana yang memperbarui (`UPDATE`) kolom `nip`/`username` pada seluruh user yang sudah ada berdasarkan data kepegawaian (bisa di-match via email atau nama jika akurat, atau diinput manual oleh Admin).
3. **Logika Callback SSO:** Pada `SsoCallbackController` (Skenario A di atas), fungsi `User::firstOrCreate(['username' => $userData['username']])` akan mencari user yang `username`-nya cocok. 
   - Jika kolom `username`/`nip` untuk user lama sudah terisi dengan benar di Langkah 2, maka sistem akan **mengenali** user lama tersebut dan langsung membuatkan *session* tanpa membuat baris baru.
   - Semua relasi data yang dimiliki user lama pada aplikasi (misal: data transaksi, log, dll) akan tetap aman karena ID User (*Primary Key*) tidak berubah.

---

### Langkah 6C — Buat Seeder Akun Admin Universal

Berdasarkan standarisasi user, setiap aplikasi yang terintegrasi diwajibkan menyediakan akun **Admin Universal** agar tim IT mudah melakukan pengecekan (*troubleshooting*) lintas aplikasi.

Buat sebuah Seeder di aplikasi klien (`php artisan make:seeder AdminUniversalSeeder`):

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUniversalSeeder extends Seeder
{
    public function run()
    {
        // Pastikan Anda menyesuaikan bagaimana pemberian role Admin di aplikasi Anda
        $admin = User::firstOrCreate(
            ['username' => 'admin_master'],
            [
                'name'  => 'Administrator Sistem',
                'nip'   => 'admin_master',
                'email' => 'it@rsudmurjani.com',
                // 'role'  => 'Super Admin', // Hapus komentar ini dan sesuaikan dengan logic Role Anda
            ]
        );
    }
}
```

Jalankan seeder ini (`php artisan db:seed --class=AdminUniversalSeeder`) saat rilis atau deployment, agar akun IT bisa langsung digunakan masuk.

---

### Langkah 7 — Tugaskan User ke Aplikasi

1. Di Auth Portal → **Kelola Pengguna** → pilih user.
2. Di bagian **Akses Aplikasi** → pilih `SPJ Murjani` → klik **Tambahkan**.

---

### Langkah 8 — Test Alur SSO

1. Login ke Auth Portal dengan user yang sudah ditugaskan.
2. Di Dashboard, klik kartu **SPJ Murjani** → **Buka Aplikasi**.
3. Portal akan redirect ke:
   ```
   http://spj-murjani.test/login/sso/callback?token=<sso_token>&redirect=http://spj-murjani.test
   ```
4. Aplikasi klien memanggil `POST /api/sso/verify` ke Auth Portal.
5. Jika valid, session lokal dibuat dan user masuk ke dashboard SPJMurjani.

---

## API Reference — Auth Portal

### `POST /api/auth/login`
Login dan dapatkan SSO token.
```json
// Request body
{ "username": "admin_123", "password": "password123" }

// Response
{ "success": true, "data": { "access_token": "...", "user": {...} } }
```

### `GET /api/auth/me`
Dapatkan data user dari token (untuk aplikasi klien yang hanya butuh data user).
```
Authorization: Bearer <token>
```

### `POST /api/sso/verify`
Verifikasi token + app_secret secara server-to-server.
```
Authorization: Bearer <user_sso_token>
Body: { "app_secret": "<app_secret>" }

// Response sukses
{
  "success": true,
  "data": {
    "user": { "id": 1, "name": "...", "username": "...", "nip": "...", "role": "Admin" },
    "application": "SPJ Murjani"
  }
}

// Response gagal
{ "success": false, "message": "Token has expired." }
```

---

## Checklist Integrasi untuk SPJMurjani

- [ ] Daftarkan SPJMurjani di Admin Portal (Langkah 1)
- [ ] Salin `App Secret`
- [ ] Tambahkan config `.env` di SPJMurjani (Langkah 2)
- [ ] Buat route `/login/sso/callback` (Langkah 3)
- [ ] Buat `SsoCallbackController` (Langkah 4)
- [ ] Tambahkan `services.auth_portal` di `config/services.php` (Langkah 5)
- [ ] Pastikan tabel `users` memiliki kolom `username` (Langkah 6)
- [ ] Tugaskan user ke SPJMurjani di Admin Portal (Langkah 7)
- [ ] Test alur SSO end-to-end (Langkah 8)
