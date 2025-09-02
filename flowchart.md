# FLOWCHART SISTEM MANAJEMEN ESKUL DENGAN IMPLEMENTASI K-MEANS

## OVERVIEW SISTEM
Sistem manajemen ekstrakurikuler yang menggunakan algoritma K-means untuk mengelompokkan siswa berdasarkan performa (kehadiran, partisipasi, dan prestasi).

---

## 1. FLOW AUTHENTICATION & LOGIN

```mermaid
flowchart TD
    A[User Mengakses Website] --> B{User Sudah Login?}
    B -->|Ya| C[Redirect ke Dashboard]
    B -->|Tidak| D[Tampilkan Halaman Login]
    
    D --> E[User Input Email & Password]
    E --> F[LoginController@authenticate]
    F --> G{Validasi Credentials}
    
    G -->|Gagal| H[Kembali ke Login + Error Message]
    G -->|Berhasil| I[Generate Session]
    
    I --> J{Check Role User}
    J -->|Admin| K[Admin Dashboard]
    J -->|Pembimbing| L[Pembimbing Dashboard]
    J -->|Pelatih| M[Pelatih Dashboard]
    J -->|Siswa| N[Siswa Dashboard]
    
    H --> E
    K --> O[Menu Management User]
    L --> P[Menu Monitoring Eskul]
    M --> Q[Menu Kelola Eskul + Analisis]
    N --> R[Menu Lihat Performa Pribadi]
```

**File yang Digunakan:**
- **Route:** `routes/web.php` (line 25-30)
- **Controller:** `app/Http/Controllers/Auth/LoginController.php`
- **View:** `resources/views/auth/login.blade.php`
- **Middleware:** `app/Http/Middleware/Authenticate.php`

---

## 2. FLOW DASHBOARD BERDASARKAN ROLE

```mermaid
flowchart TD
    A[User Login Berhasil] --> B[Livewire Dashboard Component]
    B --> C{Check Role User}
    
    C -->|Admin| D[Admin Dashboard Partial]
    C -->|Pembimbing| E[Pembimbing Dashboard Partial]
    C -->|Pelatih| F[Pelatih Dashboard Partial]
    C -->|Siswa| G[Siswa Dashboard Partial]
    
    D --> H[Statistik Global Sistem]
    E --> I[Monitoring Eskul yang Dibina]
    F --> J[Kelola Eskul + Analisis Performa]
    G --> K[Lihat Performa Pribadi]
    
    H --> L[Menu Manage Users]
    I --> M[Menu Monitoring & Approval]
    J --> N[Menu Analisis K-means]
    K --> O[Menu Lihat Prestasi]
```

**File yang Digunakan:**
- **Controller:** `app/Livewire/Dashboard/Dashboard.php`
- **Views:** 
  - `resources/views/livewire/dashboard/dashboard.blade.php`
  - `resources/views/livewire/dashboard/partials/admin-dashboard.blade.php`
  - `resources/views/livewire/dashboard/partials/pembina-dashboard.blade.php`
  - `resources/views/livewire/dashboard/partials/pelatih-dashboard.blade.php`
  - `resources/views/livewire/dashboard/partials/siswa-dashboard.blade.php`

---

## 3. FLOW ANALISIS K-MEANS (UNTUK PELATIH)

```mermaid
flowchart TD
    A[Pelatih Dashboard] --> B[Klik Menu Analisis Eskul]
    B --> C[EskulAnalisis Component]
    C --> D[Pilih Tahun & Semester]
    D --> E[Klik Tombol Analyze]
    
    E --> F[KmeansService@analyze]
    F --> G[Ambil Semua Siswa Aktif di Eskul]
    G --> H[Loop: Hitung Metrik Setiap Siswa]
    
    H --> I[calculateMetrics untuk Setiap Siswa]
    I --> J[Simpan ke student_performance_metrics]
    J --> K[performClustering]
    
    K --> L[Inisialisasi 3 Centroid]
    L --> M[Iterasi K-means Algorithm]
    M --> N{Converged?}
    
    N -->|Tidak| O[Update Centroid Positions]
    O --> P[Reassign Clusters]
    P --> M
    
    N -->|Ya| Q[Simpan Hasil Clustering]
    Q --> R[Tampilkan Hasil di UI]
    R --> S[Generate Charts & Reports]
```

**File yang Digunakan:**
- **Component:** `app/Livewire/EksulApps/EskulAnalisis.php`
- **Service:** `app/Services/KmeansService.php`
- **View:** `resources/views/livewire/eksul-apps/eskul-analisis.blade.php`
- **Database:** `student_performance_metrics` table

---

## 4. FLOW PERHITUNGAN METRIK SISWA

```mermaid
flowchart TD
    A[KmeansService@calculateMetrics] --> B{Month Parameter?}
    
    B -->|Ada Bulan| C[Set Periode 1 Bulan]
    B -->|Tidak Ada| D[Set Periode Semester Penuh]
    
    C --> E[calculateAttendanceScore]
    D --> F[calculateSemesterAggregateMetrics]
    
    E --> G[Count Kehadiran dalam Periode]
    F --> H[Loop Semua Bulan dalam Semester]
    
    H --> I[Set Periode per Bulan]
    I --> J[Hitung Metrik Bulan Ini]
    J --> K[Akumulasi Total]
    K --> L{Ada Bulan Lain?}
    
    L -->|Ya| I
    L -->|Tidak| M[Return Total Aggregat]
    
    G --> N[Return Attendance Score]
    M --> O[Return Aggregat Scores]
    
    N --> P[Return Metrics Object]
    O --> P
```

**Metrik yang Dihitung:**
1. **Attendance Score:** Jumlah kehadiran dalam periode
2. **Participation Score:** Jumlah partisipasi event dalam periode  
3. **Achievement Score:** Jumlah prestasi dalam periode

---

## 5. FLOW ALGORITMA K-MEANS CLUSTERING

```mermaid
flowchart TD
    A[performClustering] --> B[Ambil Data Metrik Siswa]
    B --> C[Persiapkan Data Points Array]
    C --> D[Inisialisasi 3 Centroid]
    
    D --> E[Pilih 3 Siswa sebagai Centroid Awal]
    E --> F[Loop Iterasi Max 50x]
    
    F --> G[Assign Clusters: Hitung Jarak Euclidean]
    G --> H[Setiap Siswa ke Centroid Terdekat]
    H --> I[Update Centroid Positions]
    
    I --> J{Hitung Rata-rata Cluster}
    J --> K{Converged? < 0.01}
    
    K -->|Tidak| L[Lanjut Iterasi]
    L --> G
    
    K -->|Ya| M[Mapping Cluster Labels]
    M --> N[Cluster 0: Performa Tertinggi]
    M --> O[Cluster 1: Performa Sedang]
    M --> P[Cluster 2: Performa Terendah]
    
    N --> Q[Update Database]
    O --> Q
    P --> Q
    Q --> R[Return Results]
```

**Algoritma K-Means:**
- **K = 3** (Tinggi, Sedang, Rendah)
- **Max Iterations:** 50
- **Convergence Tolerance:** 0.01
- **Distance Metric:** Euclidean Distance
- **Centroid Initialization:** Deterministic (Tertinggi, Tengah, Terendah)

---

## 6. FLOW VISUALISASI & REPORTING

```mermaid
flowchart TD
    A[Hasil Clustering] --> B[Calculate Cluster Statistics]
    B --> C[Count per Cluster]
    B --> D[Average Metrics per Cluster]
    
    C --> E[Generate Pie Chart]
    D --> F[Generate Bar Chart]
    
    E --> G[Chart.js Rendering]
    F --> H[Chart.js Rendering]
    
    G --> I[Convert to Base64]
    H --> J[Convert to Base64]
    
    I --> K[Save Chart Images]
    J --> K
    
    K --> L[Display Results in UI]
    L --> M[Show Cluster Distribution]
    L --> N[Show Student List per Cluster]
    
    M --> O[Export to PDF]
    N --> P[Export to Excel]
    
    O --> Q[Download Report]
    P --> R[Download Data]
```

**File yang Digunakan:**
- **Export:** `app/Exports/AnalysisReport.php`
- **PDF:** `Barryvdh\DomPDF\Facade\Pdf`
- **Excel:** `Maatwebsite\Excel\Facades\Excel`
- **Charts:** Chart.js via Livewire

---

## 7. FLOW DETAIL ANALISIS SISWA (GLOBAL)

```mermaid
flowchart TD
    A[Menu Analisis Global] --> B[DetailSiswa Component]
    B --> C[Pilih Filter: Tahun, Semester, Bulan]
    C --> D[Pilih Filter: Kelas, Eskul, Cluster]
    
    D --> E[Load Results from Database]
    E --> F[Query student_performance_metrics]
    F --> G[Apply Filters]
    
    G --> H[Calculate Additional Metrics]
    H --> I[Attendance Rate per Student]
    H --> J[Participation Rate per Student]
    H --> K[Total Score per Student]
    
    I --> L[Display Results Table]
    J --> L
    K --> L
    
    L --> M[Sort by Performance]
    M --> N[Generate Awards for Top Performers]
    N --> O[Show Performance Trends]
```

**File yang Digunakan:**
- **Component:** `app/Livewire/AnalisisApps/DetailSiswa.php`
- **View:** `resources/views/livewire/analisis-apps/detail-siswa.blade.php`
- **Database:** `student_performance_metrics`, `users`, `user_details`, `eskuls`

---

## 8. FLOW DATABASE & DATA FLOW

```mermaid
flowchart TD
    A[User Activities] --> B[Data Collection]
    
    B --> C[Attendance Records]
    B --> D[Event Participation]
    B --> E[Achievement Records]
    
    C --> F[attendances Table]
    D --> G[eskul_event_participants Table]
    E --> H[achievements Table]
    
    F --> I[KmeansService]
    G --> I
    H --> I
    
    I --> J[Calculate Metrics]
    J --> K[student_performance_metrics Table]
    
    K --> L[K-means Clustering]
    L --> M[Update Cluster Column]
    
    M --> N[Livewire Components]
    N --> O[Display Results]
    N --> P[Generate Reports]
    N --> Q[Export Data]
```

**Database Tables:**
- `users` - Data user (admin, pembimbing, pelatih, siswa)
- `eskuls` - Data ekstrakurikuler
- `eskul_members` - Keanggotaan siswa di eskul
- `attendances` - Data kehadiran
- `eskul_events` - Event eskul
- `eskul_event_participants` - Partisipasi event
- `achievements` - Prestasi siswa
- `student_performance_metrics` - Metrik performa + hasil clustering

---

## 9. FLOW EXPORT & REPORTING

```mermaid
flowchart TD
    A[Hasil Analisis] --> B{Export Format?}
    
    B -->|PDF| C[Generate PDF Report]
    B -->|Excel| D[Generate Excel Report]
    
    C --> E[Include Charts Base64]
    C --> F[Include Cluster Statistics]
    C --> G[Include Student Details]
    
    D --> H[Include Raw Data]
    D --> I[Include Summary Statistics]
    
    E --> J[DomPDF Processing]
    F --> J
    G --> J
    
    H --> K[Excel Export]
    I --> K
    
    J --> L[Download PDF]
    K --> M[Download Excel]
```

**File yang Digunakan:**
- **PDF Controller:** `app/Http/Controllers/EskulSchedulePdfController.php`
- **Excel Export:** `app/Exports/AnalysisReport.php`
- **PDF Library:** `barryvdh/laravel-dompdf`
- **Excel Library:** `maatwebsite/excel`

---

## 10. FLOW ERROR HANDLING & VALIDATION

```mermaid
flowchart TD
    A[User Input] --> B[Validation]
    B --> C{Input Valid?}
    
    C -->|Tidak| D[Show Error Message]
    C -->|Ya| E[Process Request]
    
    D --> F[Return to Form]
    F --> A
    
    E --> G[Database Operation]
    G --> H{Operation Success?}
    
    H -->|Tidak| I[Log Error]
    I --> J[Show Error Message]
    J --> F
    
    H -->|Ya| K[Success Response]
    K --> L[Redirect/Update UI]
    
    L --> M[User Feedback]
    M --> N[Continue Flow]
```

---

## SUMMARY FILE UTAMA YANG DIGUNAKAN

### 1. **Authentication & Routes**
- `routes/web.php` - Definisi semua route
- `app/Http/Controllers/Auth/LoginController.php` - Login logic
- `resources/views/auth/login.blade.php` - Login form

### 2. **Dashboard & Role Management**
- `app/Livewire/Dashboard/Dashboard.php` - Main dashboard controller
- `resources/views/livewire/dashboard/` - Dashboard views per role

### 3. **K-means Implementation**
- `app/Services/KmeansService.php` - Core K-means algorithm
- `app/Livewire/EksulApps/EskulAnalisis.php` - Eskul analysis component
- `app/Livewire/AnalisisApps/DetailSiswa.php` - Global analysis component

### 4. **Data Models**
- `app/Models/User.php` - User model
- `app/Models/Attendance.php` - Attendance model
- `app/Models/Achievement.php` - Achievement model
- `app/Models/EskulMember.php` - Eskul membership model

### 5. **Database & Migrations**
- `database/migrations/` - Database structure
- `database/seeders/` - Sample data

### 6. **Views & UI**
- `resources/views/livewire/` - Livewire components
- `resources/views/components/` - Reusable components
- `resources/css/app.css` - Styling
- `resources/js/app.js` - JavaScript functionality

### 7. **Export & Reporting**
- `app/Exports/AnalysisReport.php` - Excel export
- `app/Http/Controllers/EskulSchedulePdfController.php` - PDF generation

---

## ALUR UTAMA SISTEM

1. **Login** → Authentication → Role-based Dashboard
2. **Dashboard** → Menu sesuai Role → Fitur yang Tersedia
3. **Analisis K-means** → Input Parameter → Calculate Metrics → Clustering → Results
4. **Visualization** → Charts → Reports → Export (PDF/Excel)
5. **Data Management** → CRUD Operations → Database Updates → Real-time UI Updates

Sistem ini menggunakan **Livewire** untuk real-time interactions dan **K-means clustering** untuk mengelompokkan siswa berdasarkan performa ekstrakurikuler mereka.
