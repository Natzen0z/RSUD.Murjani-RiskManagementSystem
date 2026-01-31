# Risk Management System - RSUD dr. Murjani

Sistem Manajemen Risiko berbasis web untuk RSUD dr. Murjani Sampit.

## Fitur

- Dashboard Eksekutif dengan statistik risiko
- Daftar Risiko & Validasi Unit
- Matriks Analisis Risiko (Heatmap)
- Pengendalian & Evaluasi
- Export ke Excel
- Multi-user dengan role admin dan user biasa

## Instalasi

```bash
# Clone repository
git clone <repo-url>
cd RSUD.Murjani-RiskManagementSystem

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Run server
php artisan serve
```

## Akun Default

### Super Admin (Akses Semua Unit)
| Email | Password |
|-------|----------|
| kusnadijaya@rsudmurjani.id | kusnadijaya1 |

### Admin Per Unit (Akses Hanya Unit Masing-masing)

| No | Unit | Email | Password |
|----|------|-------|----------|
| 1 | UGD | ugd@rsudmurjani.id | ugd12345 |
| 2 | Poliklinik | poliklinik@rsudmurjani.id | poliklinik12345 |
| 3 | PONEK | ponek@rsudmurjani.id | ponek12345 |
| 4 | OK Cyto | okcyto@rsudmurjani.id | okcyto12345 |
| 5 | Lab Patologi Klinik | labpk@rsudmurjani.id | labpk12345 |
| 6 | Lab Patologi Anatomis | labpa@rsudmurjani.id | labpa12345 |
| 7 | Bank Darah | bankdarah@rsudmurjani.id | bankdarah12345 |
| 8 | Radiologi | radiologi@rsudmurjani.id | radiologi12345 |
| 9 | Depo UGD | depougd@rsudmurjani.id | depougd12345 |
| 10 | Depo Rawat Jalan | deporj@rsudmurjani.id | deporj12345 |
| 11 | Dialisis | dialisis@rsudmurjani.id | dialisis12345 |
| 12 | ICU | icu@rsudmurjani.id | icu12345 |
| 13 | OK Elektif | okelektif@rsudmurjani.id | okelektif12345 |
| 14 | SIM-RS | simrs@rsudmurjani.id | simrs12345 |
| 15 | Rawat Inap Cempaka | ricempaka@rsudmurjani.id | ricempaka12345 |
| 16 | Rawat Inap Seruni | riseruni@rsudmurjani.id | riseruni12345 |
| 17 | Rawat Inap Asoka | riasoka@rsudmurjani.id | riasoka12345 |
| 18 | Rawat Inap Perinatologi | riperina@rsudmurjani.id | riperina12345 |
| 19 | VK | vk@rsudmurjani.id | vk12345 |
| 20 | Rawat Inap Bogenvile | ribogen@rsudmurjani.id | ribogen12345 |
| 21 | Rawat Inap Seroja | riseroja@rsudmurjani.id | riseroja12345 |
| 22 | Rawat Inap Tulip | ritulip@rsudmurjani.id | ritulip12345 |
| 23 | Rawat Inap Teratai | riteratai@rsudmurjani.id | riteratai12345 |
| 24 | Pemulasaran Jenazah | jenazah@rsudmurjani.id | jenazah12345 |
| 25 | IPS-RS | ipsrs@rsudmurjani.id | ipsrs12345 |
| 26 | Security | security@rsudmurjani.id | security12345 |
| 27 | Depo Rawat Inap | depori@rsudmurjani.id | depori12345 |
| 28 | Loundry | laundry@rsudmurjani.id | laundry12345 |
| 29 | Dapur Gizi | gizi@rsudmurjani.id | gizi12345 |
| 30 | Gudang Farmasi | farmasi@rsudmurjani.id | farmasi12345 |
| 31 | Anggrek Tewu | anggrektewu@rsudmurjani.id | anggrektewu12345 |
| 32 | Rekam Medik | rekammedik@rsudmurjani.id | rekammedik12345 |
| 33 | Costumer Service | cs@rsudmurjani.id | cs12345 |
| 34 | Sanitasi | sanitasi@rsudmurjani.id | sanitasi12345 |
| 35 | Pengantar Pasien | pengantar@rsudmurjani.id | pengantar12345 |
| 36 | Code Blue | codeblue@rsudmurjani.id | codeblue12345 |
| 37 | Ambulance Rujukan External | ambulance@rsudmurjani.id | ambulance12345 |
| 38 | CSSD | cssd@rsudmurjani.id | cssd12345 |
| 39 | Depo OK Cyto | depookcyto@rsudmurjani.id | depookcyto12345 |
| 40 | Depo IBS | depoibs@rsudmurjani.id | depoibs12345 |

## Teknologi

- Laravel 11
- Alpine.js
- Tailwind CSS
- Chart.js
- ExcelJS

---

## Catatan Teknis: Dynamic Date Picker

### Fitur
Pemilih periode laporan sekarang **otomatis** mendeteksi tanggal hari ini.

### Cara Kerja

Di `resources/views/risk.blade.php`:

```javascript
init() {
    // Auto-detect current date
    const today = new Date();
    this.periodDate = today.toISOString().split('T')[0]; // Format: YYYY-MM-DD
    this.updatePeriod();
}

updatePeriod() {
    const date = new Date(this.periodDate);
    const options = { day: 'numeric', month: 'long', year: 'numeric' };
    this.globalPeriod = date.toLocaleDateString('id-ID', options);
}
```

Di `resources/views/partials/register.blade.php`:

```html
<input type="date" x-model="periodDate" @change="updatePeriod()">
<span x-text="globalPeriod"></span>
```

### Hasil
- **Default**: Tanggal hari ini (contoh: "26 Januari 2026")
- **Format**: Bahasa Indonesia (hari bulan tahun)
- User dapat mengubah tanggal sesuai kebutuhan
