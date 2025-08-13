SISTEM MANAJEMEN ESKUL - WORKFLOW DEVELOPMENT

1. SISTEM DASAR (Core System)
   A. Authentication & User Management
      - Login system ✓ = Sistem login untuk semua user (admin, pembimbing, pelatih, siswa)
      - Role & Permission ✓ = Pengaturan hak akses untuk setiap role
      - User CRUD ✓  = Create, Read, Update, Delete data user
      - Profile Management ✓ = Pengelolaan profil user (foto, biodata, kontak, password)

   B. Eskul Management
      - Eskul CRUD ✓  = Pengelolaan data eskul (nama, deskripsi, kuota, pelatih)
      - Schedule Management ✓  = Pengaturan jadwal latihan eskul
      - Material Management ✓  = Upload dan kelola materi pembelajaran
      - Gallery Management ✓  = Pengelolaan foto dan video kegiatan

2. SISTEM PENDAFTARAN & KEGIATAN
   A. Manajemen Anggota Eskul
      - Penambahan siswa ✓  = Admin/Pelatih menambahkan siswa ke eskul
      - Pengelolaan status ✓  = Aktif/non-aktif keanggotaan
      - Riwayat keanggotaan ✓  = Tracking masa keanggotaan

   B. Kegiatan Eskul
      - Event Management = Pengelolaan acara/kegiatan eskul
      - Attendance System ✓  = Sistem absensi digital untuk setiap pertemuan
      - Material Distribution ✓  = Pembagian materi pembelajaran
      - Test/Quiz System = Sistem ujian/kuis untuk evaluasi

3. SISTEM PENCATATAN PERFORMA
   A. Absensi
      - Digital attendance ✓ = Absensi digital via sistem
      - Izin/Sakit management = Pengelolaan ketidakhadiran
      - Report kehadiran = Laporan kehadiran per siswa/eskul
      - Export data = Download data kehadiran (PDF/Excel)

   B. Partisipasi
      - Record aktivitas harian = Pencatatan keaktifan siswa
      - Event participation = Keikutsertaan dalam acara
      - Material interaction = Interaksi dengan materi pembelajaran
      - Discussion participation = Keaktifan dalam diskusi
      - Point system = Sistem poin untuk setiap partisipasi

   C. Prestasi
      - Achievement recording = Pencatatan prestasi lomba/kompetisi
      - Performance evaluation = Evaluasi kinerja berkala
      - Test results = Hasil ujian/kuis
      - Competition records = Catatan kompetisi yang diikuti

4. SISTEM ANALISIS K-MEANS
   A. Data Collection
      - Aggregasi data absensi = Pengumpulan data kehadiran
      - Aggregasi data partisipasi = Pengumpulan data keaktifan
      - Aggregasi data prestasi = Pengumpulan data prestasi
      - Data normalization = Standarisasi data untuk analisis

   B. K-means Implementation
      - Perhitungan metrics = Kalkulasi nilai untuk clustering
      - Clustering process = Proses pengelompokan siswa
      - Result storage = Penyimpanan hasil clustering
      - History tracking = Tracking perubahan cluster

   C. Visualisasi
      - Dashboard charts = Grafik performa
      - Cluster visualization = Visualisasi kelompok
      - Performance trends = Tren perkembangan
      - Export reports = Download laporan analisis

5. DASHBOARD & REPORTING
   A. Admin Dashboard
      - Overview semua eskul = Ringkasan semua eskul
      - Statistik global = Statistik keseluruhan sistem
      - Management tools = Tools untuk kelola sistem
      - System monitoring = Monitoring aktivitas sistem

   B. Pembimbing Dashboard
      - Monitoring eskul = Pemantauan aktivitas eskul
      - Performance overview = Overview performa eskul
      - Approval system = Sistem persetujuan kegiatan
      - Report generation = Pembuatan laporan

   C. Pelatih Dashboard
      - Eskul management = Kelola eskul yang diampu
      - Student tracking = Tracking perkembangan siswa
      - Performance analysis = Analisis performa siswa
      - K-means results = Hasil pengelompokan siswa

   D. Siswa Dashboard
      - Personal performance = Performa pribadi
      - Achievement tracking = Tracking prestasi
      - Schedule view = Lihat jadwal kegiatan
      - Material access = Akses materi pembelajaran

# Dashboard Sistem Manajemen Ekstrakurikuler

## Overview
Dashboard ini telah diorganisir ulang menjadi layout yang lebih modular berdasarkan role pengguna. Setiap role memiliki tampilan yang disesuaikan dengan kebutuhan dan hak aksesnya.

## Struktur File

### 1. Dashboard Utama
- **File**: `resources/views/livewire/dashboard/dashboard.blade.php`
- **Fungsi**: Layout utama yang memanggil partial berdasarkan role

### 2. Partial Views (Role-based)
- **Admin**: `resources/views/livewire/dashboard/partials/admin-dashboard.blade.php`
- **Pembimbing**: `resources/views/livewire/dashboard/partials/pembina-dashboard.blade.php`
- **Pelatih**: `resources/views/livewire/dashboard/partials/pelatih-dashboard.blade.php`
- **Siswa**: `resources/views/livewire/dashboard/partials/siswa-dashboard.blade.php`

### 3. Controller
- **File**: `app/Livewire/Dashboard/Dashboard.php`
- **Fungsi**: Logic untuk generate data berdasarkan role

## Fitur Dashboard per Role

### 🎯 Admin Dashboard
- **Statistik Global**: Total user, eskul, prestasi, peserta event
- **Overview Sistem**: Manajemen user, eskul, monitoring sistem
- **Quick Actions**: Akses cepat ke fitur admin
- **Schedule & Announcements**: Jadwal dan pengumuman global

### 📊 Pembimbing Dashboard
- **Monitoring Statistics**: Eskul dibina, total prestasi, peserta event
- **Pending Approvals**: Item yang perlu disetujui
- **Monitoring & Approval**: Tools untuk monitoring aktivitas dan persetujuan
- **Quick Actions**: Akses ke fitur pembimbing

### 🏃 Pelatih Dashboard
- **Coaching Statistics**: Eskul diampu, prestasi, peserta event, siswa aktif
- **Coaching Tools**: 
  - Kelola Eskul
  - Tracking Siswa
  - Analisis Performa (K-means)
- **Quick Actions**: Akses ke fitur pelatih

### 👨‍🎓 Siswa Dashboard (Fitur Lengkap)
- **Overall Statistics**: Total eskul, progress overall, kehadiran, prestasi
- **Monthly Attendance Chart**: Grafik kehadiran 6 bulan terakhir
- **Detailed Eskul Statistics**: Statistik detail per ekstrakurikuler
  - Progress per eskul
  - Rate kehadiran
  - Jumlah prestasi
  - Total sesi dan sesi hadir
  - Tanggal bergabung
  - Terakhir hadir
- **Recent Achievements**: Daftar prestasi terbaru dengan detail
- **Quick Actions & Schedule**: Aksi cepat dan jadwal kegiatan

## Data yang Ditampilkan

### Untuk Siswa
1. **Statistik Overall**
   - Total ekstrakurikuler yang diikuti
   - Progress keseluruhan (berdasarkan kehadiran + prestasi)
   - Rate kehadiran total
   - Total prestasi yang diraih

2. **Statistik per Eskul**
   - Nama ekstrakurikuler
   - Progress individual
   - Rate kehadiran per eskul
   - Jumlah prestasi per eskul
   - Total sesi dan sesi hadir
   - Tanggal bergabung
   - Terakhir hadir

3. **Grafik Kehadiran**
   - Data 6 bulan terakhir
   - Visualisasi dengan bar chart
   - Tooltip detail jumlah sesi

4. **Prestasi Terbaru**
   - Judul prestasi
   - Deskripsi
   - Ekstrakurikuler terkait
   - Tanggal prestasi
   - Level prestasi

## Perhitungan Progress

### Formula Progress per Eskul
```
Progress = (Rate Kehadiran × 0.7) + (Jumlah Prestasi × 10)
```
- **Kehadiran**: 70% bobot
- **Prestasi**: 30% bobot (max 30 poin)

### Formula Progress Overall
```
Progress Overall = Rata-rata progress semua eskul
```

## Keunggulan Layout Baru

1. **Modular**: Setiap role memiliki file terpisah
2. **Maintainable**: Mudah diubah tanpa mempengaruhi role lain
3. **Scalable**: Bisa tambah fitur baru per role dengan mudah
4. **Clean Code**: Kode lebih terorganisir dan mudah dibaca
5. **Performance**: Hanya load data yang diperlukan per role

## Cara Penggunaan

1. **Login** dengan role yang sesuai
2. **Dashboard** akan otomatis menampilkan layout sesuai role
3. **Data** akan di-generate otomatis berdasarkan role
4. **Refresh** data dengan tombol refresh di pojok kanan atas

## Customization

Untuk menambah fitur baru:
1. Tambah property di controller `Dashboard.php`
2. Tambah logic di method `generateData()`
3. Update view partial yang sesuai
4. Pastikan data tersedia untuk role yang dituju

## Troubleshooting

### Data tidak muncul
- Cek apakah user memiliki role yang benar
- Cek apakah data tersedia di database
- Cek log error di `storage/logs/laravel.log`

### Layout tidak sesuai
- Pastikan file partial ada di direktori yang benar
- Cek nama file dan path yang dipanggil
- Clear cache view: `php artisan view:clear`

### Performance lambat
- Optimasi query database
- Implementasi caching untuk data statis
- Lazy loading untuk data yang tidak urgent


DATABASE MIGRATION STATUS:
✓ users table (default Laravel)
✓ eskuls table
✓ eskul_members table
✓ tests table
✓ test_results table
✓ eskul_schedules table
✓ eskul_materials table
✓ achievements table
✓ eskul_events table
✓ event_registrations table
✓ eskul_galleries table
✓ attendances table
✓ attendance_details table
✓ participation_records table
✓ performance_evaluations table

SEEDER STATUS:
✓ RoleAndPermissionSeeder
✓ DummyDataSeeder

COMMAND UNTUK SETUP:
1. Fresh Install:
   php artisan migrate:fresh

2. Seed Data:
   php artisan db:seed --class=RoleAndPermissionSeeder
   php artisan db:seed --class=DummyDataSeeder
   php artisan db:seed --class=AttendanceAndMemberSeeder
   php artisan db:seed --class=EventSeeder

DEFAULT LOGIN:
1. Admin:
   Email: admin@example.com
   Pass: password123

2. Pembimbing (1-3):
   Email: pembina1@example.com
   Pass: password123

3. Pelatih (1-10):
   Email: pelatih1@example.com
   Pass: password123

4. Siswa (1-20):
   Email: siswa1@example.com
   Pass: password123
