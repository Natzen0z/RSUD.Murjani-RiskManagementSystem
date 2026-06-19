# Standarisasi Akun Pengguna — PortalMurjani IdP

> **Versi:** 1.0 | **Tanggal:** Mei 2026  
> **Berlaku untuk:** Semua aplikasi internal yang terintegrasi dengan PortalMurjani sebagai Identity Provider (IdP)

---

## 1. Tujuan

Dokumen ini mendefinisikan standar tunggal untuk data akun pengguna agar semua aplikasi internal (SPJMurjani, SIMRSMurjani, dll.) mengacu pada satu sumber kebenaran (*single source of truth*) yang dikelola oleh **PortalMurjani**.

---

## 2. Skema Data Master User

Tabel `users` di PortalMurjani adalah master data pengguna. Berikut kolom-kolom wajib:

| Kolom        | Tipe       | Wajib | Keterangan                                      |
|-------------|-----------|-------|-------------------------------------------------|
| `id`        | bigint PK | ✔     | Auto-increment, ID unik internal                |
| `name`      | string    | ✔     | Nama lengkap sesuai dokumen resmi               |
| `username`  | string    | ✔     | **Sama dengan NIP/NIK** — unik, tanpa spasi     |
| `nip`       | string    | ✔     | NIP/NIK pegawai — unik, tanpa awalan `'`        |
| `email`     | string    | ✗     | Email dinas (nullable, diisi saat SSO pertama)  |
| `password`  | string    | ✔     | Bcrypt hash — *default: hash dari NIP*          |
| `role`      | string    | ✔     | `Admin` atau `User` (kapital pertama)           |
| `is_active` | boolean   | ✔     | `true` = aktif, `false` = dinonaktifkan         |

---

## 3. Aturan Format Data

### 3.1 NIP / NIK (Nomor Induk)

- **Format:** String numerik, **tanpa awalan apostrof** (`'`).
  - Benar: `197610312006042013`
  - Salah: `'197610312006042013`
- Untuk NIK (non-ASN), gunakan 16 digit NIK KTP.
- Untuk tenaga kontrak dengan NIK lokal (kode format `62020...`), tetap gunakan nilai tersebut.

### 3.2 Username

- **Identik dengan NIP/NIK** — tidak ada format tambahan.
- Tidak boleh mengandung spasi atau karakter khusus.
- Contoh: `197610312006042013`

### 3.3 Password Default

**Password default = NIP/NIK pengguna.** Setiap pengguna **wajib mengganti password** setelah login pertama kali.

- Di-hash menggunakan `bcrypt` (Laravel `Hash::make()`).
- Aplikasi yang terintegrasi **tidak boleh** menyimpan password pengguna secara lokal; semua autentikasi dilakukan via SSO ke PortalMurjani.

### 3.4 Role

| Nilai   | Deskripsi                                                  |
|--------|------------------------------------------------------------|
| `Admin` | Memiliki akses penuh ke manajemen sistem di PortalMurjani |
| `User`  | Pengguna umum — akses ditentukan oleh aplikasi masing-masing |

> **Catatan:** Role di PortalMurjani hanya mendefinisikan hak akses di portal itu sendiri. Otorisasi fitur di aplikasi terintegrasi dikelola secara internal oleh masing-masing aplikasi berdasarkan `user_id` atau `nip` dari token SSO.

### 3.5 Akun Admin Universal (Cross-App Admin)

Untuk memudahkan pemeliharaan (*maintenance*), pengecekan, dan *troubleshooting* lintas aplikasi oleh tim IT, ditetapkan satu **Akun Admin Universal** yang seragam dan wajib ada di setiap aplikasi yang terintegrasi.

- **Nama:** Administrator Sistem
- **Username / NIP:** `admin_master`
- **Kewajiban Aplikasi Klien:** Setiap aplikasi terintegrasi wajib mendaftarkan `admin_master` pada database lokalnya melalui *Seeder* dan memberikannya **Hak Akses (Role) Tertinggi** di aplikasi tersebut.
- Dengan cara ini, tim IT cukup login menggunakan akun `admin_master` di PortalMurjani, dan dapat membuka aplikasi SPJMurjani, SIMRSMurjani, dll. dengan status sebagai Admin di aplikasi-aplikasi tersebut secara otomatis.

---

## 4. Sumber Data & Format CSV Import

### 4.1 Format File CSV

File sumber: `doc/migrasi akun.csv`

```
NAMA;NIP/NIK;role
dr. Yulia Nofiany, M.Kes;'197610312006042013;user
Muhammad Suriadi, SE;'197101142007011015;user
```

- **Delimiter:** titik koma (`;`)
- **Encoding:** UTF-8
- **Baris pertama:** Header (dilewati saat import)
- **Awalan `'` pada NIP:** Artefak ekspor Excel — dibersihkan secara otomatis saat import

### 4.2 Menjalankan Import

#### Cara A — Artisan Command (Direkomendasikan)

```bash
# Simulasi terlebih dahulu (tidak ada data yang disimpan)
php artisan users:import --dry-run

# Import sesungguhnya
php artisan users:import

# Import dengan path kustom
php artisan users:import --path="doc/migrasi akun.csv"

# Reset semua user (non-Admin) lalu import ulang
php artisan users:import --fresh
```

#### Cara B — Database Seeder

```bash
# Jalankan hanya UserImportSeeder
php artisan db:seed --class=UserImportSeeder

# Jalankan semua seeder (termasuk Admin dan Aplikasi)
php artisan db:seed
```

> **Peringatan:** Seeder **tidak akan menimpa** user yang sudah ada (dicek berdasarkan `nip` dan `username`). Gunakan `--fresh` pada artisan command jika ingin reset total.

---

## 5. Integrasi SSO untuk Aplikasi Lain

Semua aplikasi internal harus menggunakan mekanisme SSO Token Callback yang disediakan PortalMurjani.

### 5.1 Alur Autentikasi

```
[Pengguna] → Login di PortalMurjani
     ↓
[PortalMurjani] → Redirect ke Aplikasi dengan ?sso_token=<TOKEN>
     ↓
[Aplikasi] → POST /api/sso/verify ke PortalMurjani
     ↓
[PortalMurjani] → Kembalikan data user
     ↓
[Aplikasi] → Buat sesi lokal untuk pengguna
```

> **Catatan Skenario A (Auto-Provisioning):** Saat aplikasi menerima data user yang berhasil diverifikasi dari Portal, sangat disarankan aplikasi menggunakan metode **Auto-Provisioning**. Artinya, jika `nip` atau `username` tersebut belum terdaftar di database lokal aplikasi, aplikasi otomatis menyisipkan data user baru dengan *role* akses terendah (misal: `Guest`). Selanjutnya, Admin di aplikasi terintegrasi tersebut yang bertugas mengubah *role* untuk memberikan hak fitur yang sebenarnya.

### 5.2 Endpoint Verifikasi Token

```
POST {PORTAL_URL}/api/sso/verify
Authorization: Bearer {APP_SECRET_TOKEN}
Content-Type: application/json

Body:
{
  "sso_token": "<token_dari_redirect>"
}
```

**Response sukses (200):**
```json
{
  "user": {
    "id": 42,
    "name": "dr. Yulia Nofiany, M.Kes",
    "username": "197610312006042013",
    "nip": "197610312006042013",
    "email": null,
    "role": "User",
    "is_active": true
  }
}
```

**Response gagal (401):**
```json
{
  "message": "Token tidak valid atau sudah kadaluarsa."
}
```

### 5.3 Konfigurasi `.env` di Aplikasi Terintegrasi

```dotenv
PORTAL_MURJANI_URL=https://portal.murjani.internal
PORTAL_MURJANI_APP_TOKEN=<token_rahasia_dari_halaman_aplikasi_di_portal>
```

---

## 6. Pengelolaan Akun

### 6.1 Menambah User Baru

1. Tambahkan baris ke `doc/migrasi akun.csv`
2. Jalankan: `php artisan users:import`
3. Atau gunakan halaman **Manajemen User** di PortalMurjani.

### 6.2 Menonaktifkan User

Jangan hapus user dari database — ubah `is_active` menjadi `false`:

```sql
UPDATE users SET is_active = false WHERE nip = '197610312006042013';
```

Atau via UI Admin → Manajemen User → Toggle Status Aktif.

### 6.3 Reset Password

Password direset ke NIP secara default:

```bash
php artisan tinker
>>> \App\Models\User::where('nip','197610312006042013')
        ->update(['password' => \Hash::make('197610312006042013')]);
```

---

## 7. Checklist Onboarding Aplikasi Baru

Sebelum aplikasi baru dapat menggunakan SSO PortalMurjani:

- [ ] Daftarkan aplikasi di **PortalMurjani → Manajemen Aplikasi**
- [ ] Salin `App Secret Token` yang digenerate
- [ ] Tambahkan konfigurasi `.env` seperti di bagian 5.3
- [ ] Implementasikan endpoint callback SSO (`/auth/sso/callback`)
- [ ] Hubungkan `nip` dari response SSO ke entitas user lokal di aplikasi (jangan simpan password)
- [ ] Uji alur login dengan akun test sebelum go-live

---

## 8. Catatan Penting

- **JANGAN** menyimpan password pengguna di database aplikasi terintegrasi. Semua autentikasi harus melalui PortalMurjani.
- Token SSO bersifat satu kali pakai (*single-use*) dan kadaluarsa dalam 5 menit.
- NIP yang diawali dengan `'` di file Excel/CSV harus dibersihkan sebelum disimpan ke database.

---

*Dikelola oleh: Tim IT RSUD Murjani Sampit*
