# ESKULAPPS - SISTEM MANAJEMEN EKSTRAKURIKULER DENGAN ALGORITMA K-MEANS

## 📋 OVERVIEW PROYEK

**EskulApps** adalah sistem manajemen ekstrakurikuler sekolah yang menggunakan algoritma **K-Means Clustering** untuk menganalisis performa siswa berdasarkan 3 metrik utama:
- **Kehadiran** (Attendance Score)
- **Partisipasi Event** (Participation Score) 
- **Prestasi** (Achievement Score)

Sistem ini mengelompokkan siswa ke dalam 3 cluster: **Tinggi**, **Sedang**, dan **Rendah** untuk membantu pembimbing dan pelatih dalam evaluasi dan pengambilan keputusan.

---

## 🎯 SPESIFIKASI TEKNIS

### **Framework & Teknologi**
- **Backend**: Laravel 11.31 (PHP 8.2+)
- **Frontend**: Livewire 3.5 + Tailwind CSS
- **Database**: SQLite (dapat diupgrade ke MySQL/PostgreSQL)
- **Algoritma**: K-Means Clustering (Custom Implementation)
- **UI Components**: Filament Tables & Forms
- **Export**: PDF (DomPDF) + Excel (Maatwebsite)

### **Fitur Utama**
1. **Manajemen User & Role** (Admin, Pembina, Pelatih, Siswa)
2. **Manajemen Biodata Lengkap** (Detail profil siswa/orang tua)
3. **Manajemen Eskul** (CRUD + Jadwal + Materi + Galeri)
4. **Sistem Absensi** (Manual + Verifikasi + Widget)
5. **Manajemen Event & Partisipasi**
6. **Sistem Test/Quiz** (Soal + Jawaban + Skoring)
7. **Sistem Prestasi & Pencapaian**
8. **Sistem Pengumuman** (Global + Per Eskul)
9. **Analisis K-Means** (Clustering Otomatis)
10. **Laporan Motivasi Siswa** (Follow-up cluster rendah)
11. **Dashboard Berdasarkan Role** (5 jenis dashboard)
12. **Export Laporan** (PDF/Excel)
13. **Import Data** (User + Absensi via Excel)
14. **Halaman Guest** (Lihat eskul tanpa login)

---

## 🏗️ STRUKTUR PROGRAM

### **1. Arsitektur MVC + Livewire**

```
EskulApps/
├── app/
│   ├── Http/Controllers/          # Controller Laravel
│   │   ├── Auth/                 # Login & Register
│   │   │   ├── LoginController.php
│   │   │   └── RegisterController.php
│   │   ├── GuestController.php   # Halaman Guest
│   │   ├── GuestDetailEskul.php  # Detail Eskul untuk Guest
│   │   └── EskulSchedulePdfController.php # Export PDF Jadwal
│   ├── Livewire/                 # Komponen Livewire
│   │   ├── Dashboard/           # Dashboard per Role
│   │   │   ├── Dashboard.php
│   │   │   ├── PimpinanDashboard.php
│   │   │   └── ReportDetailModal.php
│   │   ├── EksulApps/          # Manajemen Eskul
│   │   │   ├── DashboardEskul.php
│   │   │   ├── DetailEskul.php
│   │   │   ├── DetailEvent.php
│   │   │   ├── EskulAnalisis.php
│   │   │   ├── ScheduleEskul.php
│   │   │   └── AttedanceWidgetTable.php
│   │   ├── AnalisisApps/       # Analisis K-Means
│   │   │   └── DetailSiswa.php
│   │   ├── Announcements/      # Manajemen Pengumuman
│   │   │   └── Dashboard.php
│   │   ├── Manageuser/         # Manajemen User
│   │   │   └── Managementuser.php
│   │   └── UserProfile/        # Profil User
│   │       └── ProfileDetail.php
│   ├── Models/                   # Model Database
│   │   ├── User.php            # User & Authentication
│   │   ├── UserDetail.php      # Detail Biodata User
│   │   ├── eskul.php           # Data Eskul
│   │   ├── EskulMember.php     # Keanggotaan Eskul
│   │   ├── EskulSchedule.php   # Jadwal Eskul
│   │   ├── EskulEvent.php      # Event Eskul
│   │   ├── EskulEventParticipant.php # Partisipan Event
│   │   ├── EskulGallery.php    # Galeri Eskul
│   │   ├── EskulMaterial.php   # Materi Eskul
│   │   ├── EskulTest.php       # Test/Quiz Eskul
│   │   ├── TestQuestion.php    # Soal Test
│   │   ├── TestAnswer.php      # Jawaban Test
│   │   ├── TestAttempt.php     # Percobaan Test
│   │   ├── Attendance.php      # Data Absensi
│   │   ├── Achievement.php     # Data Prestasi
│   │   ├── Announcements.php   # Data Pengumuman
│   │   └── StudentMotivationReport.php # Laporan Motivasi Siswa
│   ├── Services/
│   │   └── KmeansService.php   # Algoritma K-Means
│   ├── Helpers/
│   │   └── HashHelper.php      # Enkripsi URL
│   ├── Imports/                # Import Data
│   │   ├── UsersImport.php
│   │   └── AttendanceImport.php
│   └── Exports/                # Export Data
│       └── AnalysisReport.php
├── resources/views/              # Template Blade
│   ├── auth/                   # Login & Register
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── livewire/              # View Livewire
│   │   ├── dashboard/         # Dashboard Views
│   │   │   ├── dashboard.blade.php
│   │   │   ├── partials/      # Partial Dashboard
│   │   │   │   ├── admin-dashboard.blade.php
│   │   │   │   ├── pelatih-dashboard.blade.php
│   │   │   │   ├── pembina-dashboard.blade.php
│   │   │   │   ├── pimpinan-dashboard.blade.php
│   │   │   │   └── siswa-dashboard.blade.php
│   │   │   └── report-detail-modal.blade.php
│   │   ├── eksul-apps/        # Views Eskul
│   │   │   ├── dashboard-eskul.blade.php
│   │   │   ├── detail-eskul.blade.php
│   │   │   ├── detail-event.blade.php
│   │   │   ├── eskul-analisis.blade.php
│   │   │   └── schedule-eskul.blade.php
│   │   ├── analisis-apps/     # Views Analisis
│   │   │   └── detail-siswa.blade.php
│   │   ├── manageuser/        # Views User Management
│   │   │   └── managementuser.blade.php
│   │   └── user-profile/      # Views Profil
│   │       └── profile-detail.blade.php
│   ├── components/            # Komponen UI
│   │   ├── layouts/           # Layout Templates
│   │   │   ├── app.blade.php
│   │   │   └── guest.blade.php
│   │   ├── Login/             # Komponen Login
│   │   │   ├── index.blade.php
│   │   │   └── detail-eskul.blade.php
│   │   ├── partials/          # Partial Components
│   │   │   ├── adminstats.blade.php
│   │   │   ├── mentorstats.blade.php
│   │   │   ├── quick-actions.blade.php
│   │   │   ├── schedule-annoucement.blade.php
│   │   │   └── userstats.blade.php
│   │   └── sidebar.blade.php
│   └── pdf/                   # Template PDF
│       ├── analysis-report.blade.php
│       └── schedule.blade.php
├── database/
│   ├── migrations/            # Struktur Database
│   └── seeders/              # Data Awal
└── public/                   # Asset & File Upload
```

### **2. Database Schema**

**Tabel Utama:**
- `users` - Data pengguna (Admin, Pembina, Pelatih, Siswa)
- `user_details` - Detail biodata lengkap user
- `eskuls` - Data ekstrakurikuler
- `eskul_members` - Keanggotaan siswa di eskul
- `eskul_schedules` - Jadwal pertemuan eskul
- `eskul_events` - Data event/kompetisi
- `eskul_event_participants` - Partisipan event
- `eskul_galleries` - Galeri foto/video eskul
- `eskul_materials` - Materi pembelajaran eskul
- `eskul_tests` - Test/Quiz eskul
- `test_questions` - Soal-soal test
- `test_answers` - Jawaban siswa
- `test_attempts` - Percobaan test siswa
- `attendances` - Data absensi
- `achievements` - Data prestasi
- `announcements` - Data pengumuman
- `student_performance_metrics` - Metrik performa (hasil K-Means)
- `student_motivation_reports` - Laporan motivasi siswa

---

## 🔧 CARA MENGOPERASIKAN SISTEM

### **A. INSTALASI & SETUP**

#### **1. Persyaratan Sistem**
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & NPM
- SQLite (default) atau MySQL/PostgreSQL

#### **2. Langkah Instalasi**
```bash
# 1. Clone repository
git clone [repository-url]
cd EskulApps

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate
php artisan db:seed

# 5. Build assets
npm run build

# 6. Jalankan server
php artisan serve
```

#### **3. Akses Sistem**
- **URL**: `http://localhost:8000`
- **Login Admin**: Email & Password dari seeder
- **Default Role**: Admin (dapat mengelola semua)

### **B. CARA LOGIN & AKSES**

#### **1. Halaman Login**
- **URL**: `http://localhost:8000/login`
- **Controller**: `LoginController@show`
- **View**: `resources/views/auth/login.blade.php`

**Langkah Login:**
1. Buka browser, ketik `http://localhost:8000/login`
2. Masukkan **Email** dan **Password**
3. Klik tombol **"Masuk"**
4. Sistem akan redirect ke dashboard sesuai role

#### **2. Dashboard Berdasarkan Role**

**Admin Dashboard:**
- **URL**: `/dashboard` (setelah login)
- **Fitur**: Manajemen user, eskul, laporan lengkap
- **Controller**: `Dashboard@generateData()`

**Pembina Dashboard:**
- **Fitur**: Monitoring eskul yang dibina
- **Akses**: Hanya eskul yang ditugaskan

**Pelatih Dashboard:**
- **Fitur**: Kelola eskul + analisis K-Means
- **Akses**: Eskul yang dilatih

**Siswa Dashboard:**
- **Fitur**: Lihat performa pribadi
- **Akses**: Eskul yang diikuti

### **C. CARA MENGGUNAKAN FITUR K-MEANS**

#### **1. Akses Analisis**
- **URL**: `/eskul-analisis/{hash}`
- **Hash**: ID eskul yang dienkripsi
- **Komponen**: `EskulAnalisis.php`

#### **2. Langkah Analisis**
1. **Pilih Periode**: Tahun & Semester
2. **Klik "Analisis"**: Sistem akan:
   - Hitung metrik siswa (kehadiran, partisipasi, prestasi)
   - Jalankan algoritma K-Means
   - Kelompokkan siswa ke 3 cluster
3. **Lihat Hasil**: Tabel siswa dengan cluster assignment
4. **Export**: PDF atau Excel

#### **3. Interpretasi Hasil**
- **Cluster 0**: Performa **Tinggi** (warna hijau)
- **Cluster 1**: Performa **Sedang** (warna kuning)  
- **Cluster 2**: Performa **Rendah** (warna merah)

---

## ⚙️ FUNGSI & TUGAS SETIAP KOMPONEN

### **1. KmeansService.php** - Algoritma K-Means
**Lokasi**: `app/Services/KmeansService.php`

**Fungsi Utama:**
- `calculateMetrics()` - Hitung skor siswa
- `performClustering()` - Jalankan K-Means
- `initializeCentroids()` - Inisialisasi centroid
- `updateCentroids()` - Update posisi centroid
- `hasConverged()` - Cek konvergensi

**Cara Kerja:**
1. Ambil data siswa dari database
2. Hitung 3 metrik: kehadiran, partisipasi, prestasi
3. Inisialisasi 3 centroid secara random
4. Iterasi hingga konvergen (max 50 iterasi)
5. Kelompokkan siswa berdasarkan jarak terdekat
6. Simpan hasil cluster ke database

### **2. LoginController.php** - Autentikasi
**Lokasi**: `app/Http/Controllers/Auth/LoginController.php`

**Fungsi:**
- `show()` - Tampilkan halaman login
- `authenticate()` - Validasi & login user
- `logout()` - Logout user

**Alur Login:**
1. User input email & password
2. Validasi credentials
3. Generate session
4. Redirect ke dashboard sesuai role

### **3. Dashboard.php** - Dashboard Utama
**Lokasi**: `app/Livewire/Dashboard/Dashboard.php`

**Fungsi:**
- `generateData()` - Generate statistik
- `generateStudentStats()` - Statistik siswa
- `calculateProgress()` - Hitung progress

**Tampilan Berdasarkan Role:**
- **Admin**: Statistik lengkap sistem
- **Pembina**: Statistik eskul yang dibina
- **Pelatih**: Statistik eskul yang dilatih
- **Siswa**: Progress pribadi

### **4. EskulAnalisis.php** - Analisis K-Means
**Lokasi**: `app/Livewire/EksulApps/EskulAnalisis.php`

**Fungsi:**
- `analyze()` - Jalankan analisis
- `exportPdf()` - Export ke PDF
- `exportExcel()` - Export ke Excel
- `saveChartImage()` - Simpan chart

**Fitur:**
- Filter berdasarkan tahun/semester
- Sorting data
- Export laporan
- Visualisasi chart

### **5. Models** - Struktur Data

**User.php:**
- Data pengguna & role (Admin, Pembina, Pelatih, Siswa)
- Relasi dengan eskul, detail, test attempts

**UserDetail.php:**
- Biodata lengkap siswa (NIS, NISN, alamat, orang tua)
- Data akademik (kelas, tahun ajaran)
- Informasi kesehatan & kebutuhan khusus

**Eskul.php:**
- Data ekstrakurikuler
- Relasi dengan pelatih, pembina, anggota, jadwal, event

**EskulMember.php:**
- Keanggotaan siswa di eskul
- Status aktif/tidak aktif

**EskulSchedule.php:**
- Jadwal pertemuan eskul
- Hari, waktu, lokasi

**EskulEvent.php:**
- Event/kompetisi eskul
- Tanggal, deskripsi, partisipan

**EskulGallery.php:**
- Galeri foto/video eskul
- Upload oleh pelatih/pembina

**EskulMaterial.php:**
- Materi pembelajaran eskul
- File PDF, dokumen, dll

**EskulTest.php:**
- Test/Quiz eskul
- Durasi, passing score, tipe test

**TestQuestion.php:**
- Soal-soal test
- Pilihan ganda, essay, file upload

**TestAnswer.php:**
- Jawaban siswa
- Skor, feedback, penilaian

**TestAttempt.php:**
- Percobaan test siswa
- Waktu mulai/selesai, skor akhir

**Attendance.php:**
- Data absensi siswa
- Status: hadir, izin, sakit, alpha
- Verifikasi pelatih

**Achievement.php:**
- Data prestasi siswa
- Level, posisi, sertifikat

**Announcements.php:**
- Pengumuman sistem
- Global atau per eskul
- Tanggal publish/expire

**StudentMotivationReport.php:**
- Laporan motivasi siswa
- Alasan cluster rendah
- Rekomendasi tindakan

### **6. Komponen Livewire** - Interface Interaktif

**Dashboard/ (Dashboard per Role):**
- `Dashboard.php` - Dashboard utama dengan statistik
- `PimpinanDashboard.php` - Dashboard khusus pimpinan
- `ReportDetailModal.php` - Modal detail laporan

**EksulApps/ (Manajemen Eskul):**
- `DashboardEskul.php` - Tabel eskul dengan Filament
- `DetailEskul.php` - Detail lengkap eskul
- `DetailEvent.php` - Detail event/kompetisi
- `EskulAnalisis.php` - Analisis K-Means
- `ScheduleEskul.php` - Jadwal eskul
- `AttedanceWidgetTable.php` - Widget tabel absensi

**AnalisisApps/ (Analisis Data):**
- `DetailSiswa.php` - Detail performa siswa

**Announcements/ (Pengumuman):**
- `Dashboard.php` - CRUD pengumuman dengan pagination

**Manageuser/ (Manajemen User):**
- `Managementuser.php` - CRUD user dengan import Excel

**UserProfile/ (Profil User):**
- `ProfileDetail.php` - Form biodata lengkap siswa

### **7. Controller Laravel** - Logic Backend

**Auth/ (Autentikasi):**
- `LoginController.php` - Login/logout user
- `RegisterController.php` - Registrasi user baru

**Guest/ (Halaman Tamu):**
- `GuestController.php` - Halaman utama untuk tamu
- `GuestDetailEskul.php` - Detail eskul untuk tamu

**Export/ (Export Data):**
- `EskulSchedulePdfController.php` - Export PDF jadwal

### **8. Import/Export** - Data Management

**Import/ (Import Data):**
- `UsersImport.php` - Import user dari Excel
- `AttendanceImport.php` - Import absensi dari Excel

**Export/ (Export Data):**
- `AnalysisReport.php` - Export laporan analisis

---

## 📊 CONTOH PENGGUNAAN SISTEM

### **Skenario 1: Login sebagai Admin**
1. Buka `http://localhost:8000/login`
2. Masukkan email admin
3. Klik "Masuk"
4. Dashboard admin muncul dengan statistik lengkap
5. Akses menu "Manajemen User" untuk kelola user
6. Akses menu "Eskul" untuk kelola ekstrakurikuler

### **Skenario 2: Analisis K-Means sebagai Pelatih**
1. Login sebagai pelatih
2. Pilih eskul yang dilatih
3. Klik "Analisis" di menu eskul
4. Pilih tahun 2024, semester 1
5. Klik tombol "Analisis"
6. Lihat hasil clustering:
   - 5 siswa cluster tinggi (hijau)
   - 8 siswa cluster sedang (kuning)
   - 3 siswa cluster rendah (merah)
7. Klik "Export PDF" untuk laporan

### **Skenario 3: Monitoring sebagai Siswa**
1. Login sebagai siswa
2. Dashboard menampilkan eskul yang diikuti
3. Lihat progress kehadiran per eskul
4. Lihat prestasi yang dicapai
5. Lihat jadwal eskul minggu ini

### **Skenario 4: Manajemen User sebagai Admin**
1. Login sebagai admin
2. Akses menu "Manajemen User"
3. Lihat daftar semua user dengan role
4. Klik "Import Excel" untuk import user baru
5. Upload file Excel dengan format yang benar
6. Sistem akan import user dan assign role otomatis

### **Skenario 5: Kelola Biodata Siswa**
1. Login sebagai admin/pelatih
2. Akses "Manajemen User" → klik "Detail" pada siswa
3. Form biodata lengkap muncul:
   - Informasi pribadi (NIS, NISN, alamat)
   - Data akademik (kelas, tahun ajaran)
   - Informasi orang tua/wali
   - Riwayat kesehatan & kebutuhan khusus
4. Isi data lengkap dan simpan

### **Skenario 6: Manajemen Pengumuman**
1. Login sebagai admin/pembina
2. Akses menu "Pengumuman"
3. Klik "Tambah Pengumuman"
4. Isi judul, konten, pilih eskul (opsional)
5. Set tanggal publish dan expire
6. Tandai "Penting" jika diperlukan
7. Simpan pengumuman

### **Skenario 7: Upload Materi Eskul**
1. Login sebagai pelatih
2. Pilih eskul yang dilatih
3. Akses tab "Materi"
4. Upload file PDF, dokumen, atau video
5. Beri judul dan deskripsi
6. Materi akan tersedia untuk siswa

### **Skenario 8: Buat Test/Quiz**
1. Login sebagai pelatih
2. Pilih eskul → tab "Test"
3. Buat test baru dengan:
   - Judul dan deskripsi
   - Durasi pengerjaan
   - Passing score
   - Tipe test (pilihan ganda/essay)
4. Tambah soal-soal
5. Set jawaban benar dan bobot skor
6. Aktifkan test untuk siswa

### **Skenario 9: Verifikasi Absensi**
1. Login sebagai pelatih
2. Akses "Absensi" di eskul
3. Lihat daftar absensi siswa
4. Klik "Verifikasi" pada absensi yang belum diverifikasi
5. Sistem akan update status verifikasi
6. Absensi terverifikasi akan muncul di analisis K-Means

### **Skenario 10: Halaman Guest**
1. Buka `http://localhost:8000` (tanpa login)
2. Lihat daftar eskul yang tersedia
3. Klik "Lihat Detail" pada eskul
4. Lihat informasi eskul, jadwal, dan galeri
5. Tidak bisa akses fitur yang memerlukan login

---

## 🔍 TROUBLESHOOTING

### **Masalah Umum:**

**1. Error "Class not found"**
- Jalankan: `composer dump-autoload`

**2. Database error**
- Pastikan file `database/database.sqlite` ada
- Jalankan: `php artisan migrate:fresh --seed`

**3. Asset tidak muncul**
- Jalankan: `npm run build`
- Atau: `npm run dev` untuk development

**4. Login tidak berfungsi**
- Cek file `.env` sudah benar
- Jalankan: `php artisan config:clear`

**5. K-Means tidak menghasilkan cluster**
- Pastikan ada data absensi/event/prestasi
- Cek log di `storage/logs/laravel.log`

---

## 📈 FITUR LANJUTAN

### **1. Export & Laporan**
- **PDF**: Laporan analisis dengan chart
- **Excel**: Data mentah untuk analisis lanjut
- **Template**: Format laporan standar

### **2. Visualisasi Data**
- **Pie Chart**: Distribusi cluster
- **Bar Chart**: Perbandingan metrik
- **Progress Bar**: Progress per siswa

### **3. Filter & Sorting**
- Filter berdasarkan periode
- Sorting berdasarkan cluster/metrik
- Pencarian siswa

### **4. Notifikasi**
- Notifikasi event baru
- Pengingat jadwal
- Update prestasi

---

## 🎓 KESIMPULAN

**EskulApps** adalah sistem manajemen ekstrakurikuler yang powerful dengan fitur analisis K-Means untuk evaluasi performa siswa. Sistem ini mudah digunakan oleh pengguna umum tanpa pengetahuan coding, dengan interface yang intuitif dan laporan yang komprehensif.

**Keunggulan:**
- ✅ Algoritma K-Means yang akurat
- ✅ Interface user-friendly
- ✅ Role-based access control
- ✅ Export laporan lengkap
- ✅ Real-time dashboard
- ✅ Scalable architecture

**Cocok untuk:**
- Sekolah menengah (SMP/SMA)
- Perguruan tinggi
- Organisasi ekstrakurikuler
- Lembaga pendidikan non-formal

---

*Dokumentasi ini dibuat untuk memudahkan pengguna umum dalam memahami dan mengoperasikan sistem EskulApps tanpa perlu pengetahuan teknis yang mendalam.*
