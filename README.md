# CargoTrace — Platform Visibilitas & Pelacakan Kargo Maritim Global

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com)
[![Tests](https://img.shields.io/badge/PHPUnit-30%20Passed%20(100%25)-10B981?style=for-the-badge&logo=php&logoColor=white)](https://phpunit.de)

> **Tugas Akhir — Web Programming 2 (UAS)**  
> Platform pemantauan perjalanan pengiriman kargo laut/darat global yang dibangun dengan arsitektur **Service Layer**, **MySQL**, **Peta Interaktif Leaflet.js**, integrasi API cuaca live **Open-Meteo**, dan **REST API Laravel Sanctum**.

---

## 📌 Masalah & Solusi Bisnis

Dalam industri logistik dan transportasi laut:
- Informasi perjalanan kargo sering tersebar dan sulit dipantau secara terpusat oleh dispatcher pelabuhan.
- Pelanggan (*Customer*) tidak mengetahui status dan riwayat terkini barang mereka.
- Keterlambatan akibat badai laut, antrean pelabuhan, atau pemeriksaan bea cukai tidak terdeteksi secara otomatis.

**CargoTrace** menyelesaikan masalah tersebut melalui:
1. **Validasi Alur Status Ketat (*Finite State Machine*)**:
   Alur status harus berurutan: `CREATED` → `BOOKED` → `PICKED_UP` → `IN_TRANSIT` → `DELIVERED`. Status akhir (`DELIVERED`) tidak boleh diubah kembali.
2. **Kalkulasi Keterlambatan Otomatis (*Deterministic Delay Engine*)**:
   Menghitung selisih antara estimasi tiba (*estimated arrival*) dan jadwal awal (*expected arrival*), lalu mengelompokkannya ke dalam tingkat keparahan:
   - **LOW**: Keterlambatan 0 – 6 jam
   - **MEDIUM**: Keterlambatan >6 – 24 jam
   - **HIGH**: Keterlambatan >24 jam
3. **Dispatch Workbench & Radar Peta Interaktif**:
   Visualisasi jalur samudra menggunakan **Leaflet.js** lengkap dengan marker koordinat GPS, kapal berdenyut (*pulsing beacon*), dan fitur fokus zoom otomatis (*flyTo*) saat kartu kargo diklik.
4. **Integrasi Cuaca Pelabuhan Live**:
   Menampilkan suhu, kelembapan, dan kecepatan angin di pelabuhan asal & tujuan via **Open-Meteo API** dengan proteksi batas waktu (*timeout 3 detik*) dan penanganan fallback jika koneksi internet terputus.
5. **Otorisasi Berbasis Peran (*Role-Based Access Control*)**:
   Pemisahan hak akses yang ketat antara **Admin**, **Operator Logistik**, dan **Customer** (dengan proteksi keamanan IDOR).
6. **REST API Standar Industri**:
   Dilindungi dengan token Bearer **Laravel Sanctum** dan format respon JSON standar.

---

## 🏗️ Arsitektur Sistem: Service Layer Pattern

Sesuai panduan rekayasa perangkat lunak, logika bisnis dipisahkan secara tegas dari Controller:

```
[Browser Web / Klien Postman]
              │
              ▼
   [Routes & Middleware (auth, can)]
              │
              ▼
    [Controllers (Hanya Menangani HTTP & Validasi Input)]
              │
              ▼
       [Service Layer (Logika Bisnis Reusable)]
       ├── ShipmentService (Aturan transisi status & penomoran kargo)
       ├── TrackingService (Pencatatan riwayat kronologis & koordinat)
       ├── EtaService (Kalkulasi matematika delay & tingkat severity)
       └── WeatherService (HTTP Client, batas timeout, & cache cuaca)
              │
              ▼
[Eloquent Models & Database MySQL]
```

---

## 🔑 Akun Demo Pengujian

Semua akun pengujian menggunakan password yang sama: **`password`**

| Role / Peran | Alamat Email | Hak Akses Utama |
| :--- | :--- | :--- |
| **Administrator** | `admin@cargotrace.com` | Memiliki akses penuh ke seluruh kargo, armada carrier, dan audit log |
| **Logistics Operator** | `operator@cargotrace.com` | Membuat kargo, menambah checkpoint pelacakan, dan menyelesaikan kendala |
| **Customer** | `budi@agrologistics.com` | Hanya dapat melihat kargo miliknya sendiri (Aman dari kebocoran data) |

---

## 🚀 Panduan Menjalankan Aplikasi di Laptop

### Kebutuhan Sistem
- PHP 8.2 atau 8.3
- Composer 2.x
- MySQL / MariaDB (melalui XAMPP)
- Web Browser modern (Chrome / Edge / Firefox)

### Langkah-Langkah Menjalankan

1. **Clone Repositori dari GitHub**
   ```bash
   git clone https://github.com/zahraism/cargotrace.git
   cd cargotrace
   ```

2. **Install Dependensi Paket**
   ```bash
   composer install
   ```

3. **Konfigurasi Database**
   Pastikan pengaturan database di file `.env` sudah mengarah ke MySQL kamu:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cargotrace
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. **Jalankan Migrasi & Seeder Data**
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Nyalakan Server Lokal**
   ```bash
   php artisan serve
   ```
   Buka browser dan akses alamat: **`http://127.0.0.1:8000`**

---

## 🧪 Pengujian Otomatis (Automated Testing)

Aplikasi telah dilengkapi dengan rangkaian pengujian otomatis Feature & Unit test yang mencakup autentikasi, aturan transisi status, proteksi IDOR, dan REST API:

```bash
php artisan test
```

**Hasil Pengujian: 30 Passed (82 Assertions) — 100% HIJAU (PASS)**

---

## 📡 Dokumentasi Endpoint REST API

URL Dasar: `http://127.0.0.1:8000/api`

### 1. Autentikasi
- `POST /api/register` — Pendaftaran akun Customer baru
- `POST /api/login` — Login dan penerbitan Token Bearer Sanctum
- `POST /api/logout` — Pencabutan (revoke) token aktif (Wajib `Bearer Token`)
- `GET /api/user` — Mengambil data pengguna yang sedang login (Wajib `Bearer Token`)

### 2. Kargo & Pelacakan
- `GET /api/shipments` — Mengambil daftar kargo (dilengkapi filter status & paginasi)
- `POST /api/shipments` — Membuat manifest kargo baru (Khusus Operator / Admin)
- `GET /api/shipments/{id}` — Mengambil rincian lengkap satu kargo
- `GET /api/shipments/{id}/tracking` — Mengambil riwayat titik perjalanan kronologis
- `POST /api/shipments/{id}/tracking` — Menambahkan checkpoint baru beserta koordinat GPS

---

## 📄 Lisensi
Proyek ini dikembangkan untuk keperluan akademik dan dilisensikan di bawah [MIT License](LICENSE).
