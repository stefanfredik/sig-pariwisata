# Software Requirements Specification (SRS)

## Sistem Informasi Geografis Pemetaan Lokasi Wisata di Kabupaten Manggarai Barat Berbasis Web

**Versi: 1.0**  
**Tanggal: 18 Januari 2026**  
**Disusun oleh: Tim Pengembang SIG Pariwisata**

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Deskripsi Umum](#2-deskripsi-umum)
3. [Kebutuhan Antarmuka Eksternal](#3-kebutuhan-antarmuka-eksternal)
4. [Fitur Sistem](#4-fitur-sistem)
5. [Kebutuhan Non-Fungsional](#5-kebutuhan-non-fungsional)
6. [Kebutuhan Lainnya](#6-kebutuhan-lainnya)

---

## 1. Pendahuluan

### 1.1 Tujuan Dokumen
Dokumen Software Requirements Specification (SRS) ini menjelaskan secara detail spesifikasi kebutuhan fungsional dan non-fungsional untuk Sistem Informasi Geografis (SIG) Pemetaan Lokasi Wisata di Kabupaten Manggarai Barat. Dokumen ini ditujukan untuk:
- Tim pengembang (developer)
- Stakeholder (Dinas Pariwisata Kabupaten Manggarai Barat)
- Tim quality assurance
- Pengguna sistem (admin dan user)

### 1.2 Konvensi Dokumen
- **Harus (Must)**: Kebutuhan yang wajib diimplementasikan
- **Seharusnya (Should)**: Kebutuhan yang sangat disarankan
- **Dapat (May)**: Kebutuhan opsional

### 1.3 Audience yang Dituju
- **Developer**: Untuk implementasi sistem
- **Tester**: Untuk perencanaan testing
- **Project Manager**: Untuk perencanaan dan monitoring
- **Stakeholder**: Untuk validasi kebutuhan

### 1.4 Ruang Lingkup Produk
**Nama Produk**: SIG Wisata Manggarai Barat

**Tujuan**:
- Menyediakan platform terpadu untuk informasi pariwisata Kabupaten Manggarai Barat
- Memudahkan wisatawan dalam merencanakan perjalanan
- Membantu pemerintah daerah dalam pengelolaan data pariwisata
- Meningkatkan visibilitas UMKM lokal

**Manfaat**:
- Peningkatan kunjungan wisata
- Promosi destinasi wisata lebih efektif
- Pengelolaan data pariwisata yang terstruktur
- Dukungan ekonomi lokal melalui promosi UMKM

### 1.5 Referensi
- Dokumen Proposal Penelitian Kornelianus Ladu (2201010529)
- UU No.10 Tahun 2009 tentang Kepariwisataan
- Data Dinas Pariwisata, Ekonomi Kreatif, dan Kebudayaan Kabupaten Manggarai Barat
- Laravel Documentation v10.x
- OpenStreetMap API Documentation

---

## 2. Deskripsi Umum

### 2.1 Perspektif Produk
Sistem ini merupakan aplikasi web mandiri yang dikembangkan menggunakan:
- **Backend Framework**: Laravel 12
- **Frontend**: Livewire dan Tailwind CSS, Inertia JS
- **Database**: MySQL 8.0
- **Mapping**: Leaflet.js dengan OpenStreetMap
- **Server**: Apache/Nginx dengan PHP 8.3

Sistem ini berinteraksi dengan:
- OpenStreetMap API untuk peta interaktif
- Browser pengguna (desktop dan mobile)
- Database MySQL untuk penyimpanan data

### 2.2 Fungsi Produk
**Untuk Admin**:
- Manajemen data objek wisata
- Manajemen data event
- Manajemen data kecamatan
- Manajemen data fasilitas wisata
- Moderasi review user
- Dashboard statistik

**Untuk User/Pengunjung**:
- Melihat peta interaktif lokasi wisata
- Melihat detail informasi wisata
- Pencarian rute perjalanan
- Melihat informasi event
- Memberikan review dan rating
- Melihat fasilitas dan UMKM sekitar

### 2.3 Karakteristik Pengguna

| Tipe Pengguna | Karakteristik | Hak Akses |
|---------------|---------------|-----------|
| **Admin** | Staff Dinas Pariwisata, memiliki pengetahuan IT dasar | Full access (CRUD semua data) |
| **User Terdaftar** | Wisatawan yang membuat akun | Lihat data, buat review |
| **Guest** | Pengunjung umum | Lihat data saja |

### 2.4 Batasan Sistem
- Sistem hanya mencakup 10 objek wisata utama di Kabupaten Manggarai Barat
- Tidak menyediakan fitur pemesanan tiket atau pembayaran online
- Rute perjalanan menggunakan data OpenStreetMap yang mungkin tidak selalu update
- Memerlukan koneksi internet untuk akses peta

### 2.5 Asumsi dan Ketergantungan
**Asumsi**:
- User memiliki akses internet
- Browser mendukung HTML5 dan JavaScript
- Data koordinat objek wisata akurat

**Ketergantungan**:
- Ketersediaan OpenStreetMap API
- Kompatibilitas browser modern
- Server hosting yang stabil

---

## 3. Kebutuhan Antarmuka Eksternal

### 3.1 Antarmuka Pengguna

**Prinsip Desain**:
- Responsive design (mobile-first approach)
- Minimalis dan mudah dipahami
- Konsisten dalam warna dan layout
- Aksesibilitas tinggi

**Komponen UI**:
- **Navbar**: Navigasi utama (sticky)
- **Sidebar**: Menu admin (collapsible)
- **Card Components**: Untuk menampilkan objek wisata/event
- **Modal**: Untuk form input dan konfirmasi
- **Toast Notifications**: Untuk feedback user
- **Map Container**: Peta interaktif fullscreen

**Color Scheme** (Optimasi):
```
Primary: #2563EB (Blue)
Secondary: #7C3AED (Purple)
Success: #10B981 (Green)
Warning: #F59E0B (Amber)
Danger: #EF4444 (Red)
Neutral: #6B7280 (Gray)
```

### 3.2 Antarmuka Hardware
- **Minimum Server**: 
  - CPU: 2 cores
  - RAM: 4 GB
  - Storage: 50 GB SSD
  - Bandwidth: 100 Mbps

- **Client**:
  - Desktop/Laptop: Resolusi minimal 1366x768
  - Mobile: Resolusi minimal 360x640
  - Koneksi internet minimal 1 Mbps

### 3.3 Antarmuka Software

**Server-side**:
- OS: Ubuntu 22.04 LTS / CentOS 8
- Web Server: Nginx 1.24+ atau Apache 2.4+
- PHP: 8.1+ dengan extension: mbstring, xml, pdo, mysql, gd
- MySQL: 8.0+
- Composer: 2.5+

**Client-side**:
- Browser: Chrome 100+, Firefox 100+, Safari 15+, Edge 100+
- JavaScript enabled

### 3.4 Antarmuka Komunikasi
- **Protokol**: HTTPS (TLS 1.3)
- **API Format**: RESTful JSON
- **Authentication**: Laravel Sanctum (Token-based)
- **External API**: OpenStreetMap Nominatim API

---

## 4. Fitur Sistem

### 4.1 Modul Autentikasi

#### 4.1.1 Login Admin
**Deskripsi**: Admin dapat masuk ke sistem menggunakan username dan password.

**Functional Requirements**:
- **FR-AUTH-01**: Sistem HARUS menyediakan form login dengan field username dan password
- **FR-AUTH-02**: Sistem HARUS memvalidasi kredensial dengan database
- **FR-AUTH-03**: Sistem HARUS memberikan notifikasi jika login gagal
- **FR-AUTH-04**: Sistem HARUS redirect ke dashboard jika login berhasil
- **FR-AUTH-05**: Sistem HARUS membuat session untuk admin yang login
- **FR-AUTH-06**: Sistem HARUS menggunakan password hash (bcrypt)
- **FR-AUTH-07**: Sistem SEHARUSNYA implementasi rate limiting (5 percobaan/menit)
- **FR-AUTH-08**: Sistem DAPAT implementasi 2FA (future enhancement)

**Input**: Username (string, required), Password (string, required)  
**Output**: Session token, redirect ke dashboard  
**Error Handling**: "Username atau password salah", "Akun terkunci"

#### 4.1.2 Login User
**Deskripsi**: User dapat mendaftar dan login untuk memberikan review.

**Functional Requirements**:
- **FR-AUTH-09**: Sistem HARUS menyediakan registrasi user baru
- **FR-AUTH-10**: Sistem HARUS validasi email unique
- **FR-AUTH-11**: Sistem HARUS kirim email verifikasi (optional)
- **FR-AUTH-12**: Sistem HARUS menyimpan password terenkripsi

#### 4.1.3 Logout
**Functional Requirements**:
- **FR-AUTH-13**: Sistem HARUS hapus session saat logout
- **FR-AUTH-14**: Sistem HARUS redirect ke halaman utama

---

### 4.2 Modul Manajemen Kecamatan

#### 4.2.1 Daftar Kecamatan
**Functional Requirements**:
- **FR-KEC-01**: Sistem HARUS menampilkan semua kecamatan
- **FR-KEC-02**: Sistem HARUS menampilkan jumlah objek wisata per kecamatan
- **FR-KEC-03**: Sistem SEHARUSNYA pagination (10 items/page)

#### 4.2.2 Tambah Kecamatan
**Functional Requirements**:
- **FR-KEC-04**: Sistem HARUS validasi nama kecamatan (unique, max 50 karakter)
- **FR-KEC-05**: Sistem HARUS simpan data ke database

**Validasi**:
```
nama_kecamatan: required|string|max:50|unique:kecamatans
```

#### 4.2.3 Edit Kecamatan
**Functional Requirements**:
- **FR-KEC-06**: Sistem HARUS load data kecamatan yang dipilih
- **FR-KEC-07**: Sistem HARUS update data ke database

#### 4.2.4 Pencarian Kecamatan
**Functional Requirements**:
- **FR-KEC-08**: Sistem HARUS implementasi live search
- **FR-KEC-09**: Sistem SEHARUSNYA tampilkan hasil dalam 500ms

---

### 4.3 Modul Manajemen Objek Wisata

#### 4.3.1 Daftar Objek Wisata (Admin)
**Functional Requirements**:
- **FR-OBJ-01**: Sistem HARUS tampilkan tabel dengan kolom: No, Nama, Kecamatan, Alamat, Koordinat, Aksi
- **FR-OBJ-02**: Sistem HARUS implementasi pagination (10 items/page)
- **FR-OBJ-03**: Sistem HARUS menyediakan filter berdasarkan kecamatan
- **FR-OBJ-04**: Sistem HARUS menyediakan pencarian

#### 4.3.2 Tambah Objek Wisata
**Functional Requirements**:
- **FR-OBJ-05**: Sistem HARUS validasi semua field required
- **FR-OBJ-06**: Sistem HARUS upload multiple foto (max 5, max 2MB each)
- **FR-OBJ-07**: Sistem HARUS validasi format koordinat (latitude/longitude)
- **FR-OBJ-08**: Sistem HARUS resize foto otomatis
- **FR-OBJ-09**: Sistem SEHARUSNYA preview foto sebelum upload

**Data Fields**:
```php
- id_kecamatan: required|exists:kecamatans,id
- nama_objek: required|string|max:100
- alamat: required|string|max:255
- no_telepon: nullable|string|max:15
- keterangan: required|string|max:5000
- jam_operasional: nullable|string
- harga_tiket: nullable|string
- latitude: required|numeric|between:-90,90
- longitude: required|numeric|between:-180,180
- foto[]: required|array|max:5
- foto.*: image|mimes:jpg,jpeg,png|max:2048
```

#### 4.3.3 Edit Objek Wisata
**Functional Requirements**:
- **FR-OBJ-10**: Sistem HARUS load data existing
- **FR-OBJ-11**: Sistem HARUS tampilkan foto existing
- **FR-OBJ-12**: Sistem DAPAT hapus foto individual
- **FR-OBJ-13**: Sistem HARUS update data

#### 4.3.4 Hapus Objek Wisata
**Functional Requirements**:
- **FR-OBJ-14**: Sistem HARUS tampilkan konfirmasi sebelum hapus
- **FR-OBJ-15**: Sistem HARUS hapus foto terkait dari storage
- **FR-OBJ-16**: Sistem HARUS soft delete (recommended) atau hard delete
- **FR-OBJ-17**: Sistem HARUS check foreign key constraints

#### 4.3.5 Detail Objek Wisata (User)
**Functional Requirements**:
- **FR-OBJ-18**: Sistem HARUS tampilkan foto gallery (carousel/grid)
- **FR-OBJ-19**: Sistem HARUS tampilkan informasi lengkap
- **FR-OBJ-20**: Sistem HARUS tampilkan peta lokasi
- **FR-OBJ-21**: Sistem HARUS tampilkan rating rata-rata
- **FR-OBJ-22**: Sistem HARUS tampilkan daftar review (paginated)
- **FR-OBJ-23**: Sistem HARUS tampilkan fasilitas tersedia
- **FR-OBJ-24**: Sistem SEHARUSNYA tampilkan UMKM terdekat
- **FR-OBJ-25**: Sistem DAPAT tampilkan cuaca real-time

---

### 4.4 Modul Peta Interaktif

#### 4.4.1 Peta Objek Wisata
**Functional Requirements**:
- **FR-MAP-01**: Sistem HARUS tampilkan peta menggunakan Leaflet.js + OpenStreetMap
- **FR-MAP-02**: Sistem HARUS tampilkan marker untuk setiap objek wisata
- **FR-MAP-03**: Sistem HARUS tampilkan popup info saat marker diklik
- **FR-MAP-04**: Sistem HARUS cluster marker jika berdekatan (Marker Cluster)
- **FR-MAP-05**: Sistem HARUS auto-center berdasarkan bounds markers
- **FR-MAP-06**: Sistem SEHARUSNYA custom marker icon per kategori
- **FR-MAP-07**: Sistem DAPAT filter marker by kecamatan

**Popup Content**:
- Foto thumbnail
- Nama objek
- Rating
- Link "Lihat Detail"

#### 4.4.2 Rute Wisata
**Functional Requirements**:
- **FR-MAP-08**: Sistem HARUS input lokasi awal (geolocation atau manual)
- **FR-MAP-09**: Sistem HARUS pilih destinasi tujuan
- **FR-MAP-10**: Sistem HARUS tampilkan rute menggunakan Routing Machine
- **FR-MAP-11**: Sistem HARUS tampilkan estimasi jarak dan waktu
- **FR-MAP-12**: Sistem SEHARUSNYA multiple waypoints (multi-destinasi)
- **FR-MAP-13**: Sistem DAPAT pilih mode transportasi (mobil/motor/jalan kaki)

**Technology**: Leaflet Routing Machine (OSRM backend)

---

### 4.5 Modul Manajemen Event

#### 4.5.1 Daftar Event (Admin)
**Functional Requirements**:
- **FR-EVT-01**: Sistem HARUS tampilkan tabel event
- **FR-EVT-02**: Sistem HARUS filter: Upcoming, Ongoing, Past
- **FR-EVT-03**: Sistem HARUS pagination

#### 4.5.2 Tambah Event
**Functional Requirements**:
- **FR-EVT-04**: Sistem HARUS validasi tanggal (tanggal_selesai >= tanggal_mulai)
- **FR-EVT-05**: Sistem HARUS upload foto event (max 5)
- **FR-EVT-06**: Sistem HARUS relasikan ke objek wisata (optional)

**Data Fields**:
```php
- nama_event: required|string|max:100
- tanggal_mulai: required|date|after_or_equal:today
- tanggal_selesai: required|date|after_or_equal:tanggal_mulai
- alamat: required|string|max:255
- keterangan: required|string|max:5000
- id_objek: nullable|exists:objek_wisatas,id
- foto[]: required|array|max:5
```

#### 4.5.3 Daftar Event (User)
**Functional Requirements**:
- **FR-EVT-07**: Sistem HARUS tampilkan card layout
- **FR-EVT-08**: Sistem HARUS highlight upcoming events
- **FR-EVT-09**: Sistem HARUS countdown untuk event terdekat

#### 4.5.4 Detail Event (User)
**Functional Requirements**:
- **FR-EVT-10**: Sistem HARUS tampilkan foto gallery
- **FR-EVT-11**: Sistem HARUS tampilkan informasi lengkap
- **FR-EVT-12**: Sistem DAPAT add to calendar (iCal format)

---

### 4.6 Modul Manajemen Fasilitas Wisata

#### 4.6.1 Daftar Fasilitas (Admin)
**Functional Requirements**:
- **FR-FAS-01**: Sistem HARUS tampilkan fasilitas per objek wisata
- **FR-FAS-02**: Sistem HARUS filter by objek wisata

#### 4.6.2 Tambah Fasilitas
**Functional Requirements**:
- **FR-FAS-03**: Sistem HARUS pilih objek wisata
- **FR-FAS-04**: Sistem HARUS input nama fasilitas (toilet, parkir, musholla, dll)
- **FR-FAS-05**: Sistem HARUS input koordinat fasilitas
- **FR-FAS-06**: Sistem DAPAT upload foto fasilitas

**Data Fields**:
```php
- id_objek: required|exists:objek_wisatas,id
- nama_fasilitas: required|string|max:50
- deskripsi: nullable|string|max:500
- latitude: required|numeric
- longitude: required|numeric
- foto: nullable|image|max:2048
```

#### 4.6.3 Daftar Fasilitas (User)
**Functional Requirements**:
- **FR-FAS-07**: Sistem HARUS tampilkan icon fasilitas di peta
- **FR-FAS-08**: Sistem HARUS detail popup fasilitas

---

### 4.7 Modul Review dan Rating

#### 4.7.1 Tambah Review (User)
**Functional Requirements**:
- **FR-REV-01**: Sistem HARUS require login untuk review
- **FR-REV-02**: Sistem HARUS validasi 1 review per user per objek
- **FR-REV-03**: Sistem HARUS upload foto (max 5, optional)
- **FR-REV-04**: Sistem HARUS rating 1-5 bintang
- **FR-REV-05**: Sistem HARUS moderate sebelum publish (pending approval)

**Data Fields**:
```php
- id_user: required|exists:users,id
- id_objek: required|exists:objek_wisatas,id
- rating: required|numeric|min:1|max:5
- judul: nullable|string|max:100
- komentar: required|string|max:1000
- foto[]: nullable|array|max:5
- status: enum(pending, approved, rejected)
```

#### 4.7.2 Moderasi Review (Admin)
**Functional Requirements**:
- **FR-REV-06**: Sistem HARUS tampilkan review pending
- **FR-REV-07**: Sistem HARUS approve/reject review
- **FR-REV-08**: Sistem DAPAT edit komentar (moderate)

#### 4.7.3 Daftar Review (User)
**Functional Requirements**:
- **FR-REV-09**: Sistem HARUS tampilkan review approved saja
- **FR-REV-10**: Sistem HARUS sorting: Terbaru, Rating Tertinggi
- **FR-REV-11**: Sistem HARUS pagination (5 items/page)
- **FR-REV-12**: Sistem SEHARUSNYA helpful count (like review)

---

### 4.8 Modul Dashboard

#### 4.8.1 Dashboard Admin
**Functional Requirements**:
- **FR-DASH-01**: Sistem HARUS tampilkan total: kecamatan, objek wisata, event, review, user, fasilitas
- **FR-DASH-02**: Sistem HARUS chart: Visitor trends (monthly)
- **FR-DASH-03**: Sistem HARUS top 5 objek wisata (by rating/review count)
- **FR-DASH-04**: Sistem HARUS pending reviews count
- **FR-DASH-05**: Sistem HARUS upcoming events list

**Technology**: Chart.js atau ApexCharts

#### 4.8.2 Dashboard User
**Functional Requirements**:
- **FR-DASH-06**: Sistem HARUS hero section dengan CTA
- **FR-DASH-07**: Sistem HARUS featured destinations (top rated)
- **FR-DASH-08**: Sistem HARUS upcoming events
- **FR-DASH-09**: Sistem HARUS recent reviews

---

### 4.9 Modul Pencarian

#### 4.9.1 Global Search
**Functional Requirements**:
- **FR-SEARCH-01**: Sistem HARUS search by: nama objek, kecamatan, kategori
- **FR-SEARCH-02**: Sistem HARUS auto-suggest (debounced 300ms)
- **FR-SEARCH-03**: Sistem SEHARUSNYA search full-text (MySQL FULLTEXT index)

---

### 4.10 Fitur Tambahan (Optimasi)

#### 4.10.1 Multi-bahasa
**Functional Requirements**:
- **FR-LANG-01**: Sistem DAPAT support Bahasa Indonesia dan English
- **FR-LANG-02**: Sistem DAPAT switch language

**Technology**: Laravel Localization

#### 4.10.2 SEO Optimization
**Functional Requirements**:
- **FR-SEO-01**: Sistem HARUS meta tags dynamic per page
- **FR-SEO-02**: Sistem HARUS sitemap.xml auto-generate
- **FR-SEO-03**: Sistem HARUS robots.txt
- **FR-SEO-04**: Sistem SEHARUSNYA structured data (JSON-LD)

#### 4.10.3 Share Social Media
**Functional Requirements**:
- **FR-SHARE-01**: Sistem DAPAT share objek wisata ke Facebook, Twitter, WhatsApp
- **FR-SHARE-02**: Sistem HARUS Open Graph tags

---

## 5. Kebutuhan Non-Fungsional

### 5.1 Performance
- **NFR-PERF-01**: Halaman HARUS load dalam < 3 detik (3G connection)
- **NFR-PERF-02**: API response time < 500ms (95th percentile)
- **NFR-PERF-03**: Sistem HARUS handle 100 concurrent users
- **NFR-PERF-04**: Database query HARUS optimized (max 50ms per query)
- **NFR-PERF-05**: Image lazy loading untuk gallery

**Optimization**:
- Laravel Query Cache
- Redis/Memcached caching
- CDN untuk assets
- Image optimization (WebP format)

### 5.2 Security
- **NFR-SEC-01**: HTTPS only (SSL/TLS)
- **NFR-SEC-02**: CSRF protection (Laravel default)
- **NFR-SEC-03**: XSS protection (escaped output)
- **NFR-SEC-04**: SQL Injection prevention (Eloquent ORM)
- **NFR-SEC-05**: Rate limiting: 60 requests/minute per IP
- **NFR-SEC-06**: Input validation dan sanitization
- **NFR-SEC-07**: Password minimal 8 karakter (kombinasi huruf, angka, simbol)
- **NFR-SEC-08**: File upload validation (type, size, extension)
- **NFR-SEC-09**: Secure session management
- **NFR-SEC-10**: Regular security updates

### 5.3 Usability
- **NFR-USE-01**: Responsive design (mobile, tablet, desktop)
- **NFR-USE-02**: Intuitive navigation (max 3 clicks to destination)
- **NFR-USE-03**: Consistent UI/UX
- **NFR-USE-04**: Error messages yang jelas dan helpful
- **NFR-USE-05**: Loading indicators untuk async operations
- **NFR-USE-06**: Accessibility (WCAG 2.1 Level AA)

### 5.4 Reliability
- **NFR-REL-01**: Uptime 99.5%
- **NFR-REL-02**: Database backup daily
- **NFR-REL-03**: Graceful error handling
- **NFR-REL-04**: Transaction rollback on error

### 5.5 Maintainability
- **NFR-MAIN-01**: Clean code (PSR-12 standard)
- **NFR-MAIN-02**: Documentation (inline comments, API docs)
- **NFR-MAIN-03**: Modular architecture (Service Repository Pattern)
- **NFR-MAIN-04**: Version control (Git)
- **NFR-MAIN-05**: Automated testing (unit, feature)

### 5.6 Scalability
- **NFR-SCAL-01**: Horizontal scaling support
- **NFR-SCAL-02**: Database indexing untuk query optimization
- **NFR-SCAL-03**: Queue untuk heavy tasks (email, image processing)

### 5.7 Compatibility
- **NFR-COMP-01**: Browser support: Chrome, Firefox, Safari, Edge (latest 2 versions)
- **NFR-COMP-02**: Mobile OS: Android 8+, iOS 13+
- **NFR-COMP-03**: Screen size: 360px - 1920px

---

## 6. Kebutuhan Lainnya

### 6.1 Database Schema (Optimasi)

#### Tabel: `users`
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    avatar VARCHAR(255) NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabel: `kecamatans`
```sql

CREATE TABLE kecamatans (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    nama_kecamatan VARCHAR(50) NOT NULL UNIQUE,

    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_nama (nama_kecamatan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabel: `objek_wisatas`
```sql
CREATE TABLE objek_wisatas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_kecamatan BIGINT UNSIGNED NOT NULL,
    nama_objek VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    alamat VARCHAR(255) NOT NULL,
    no_telepon VARCHAR(15) NULL,
    keterangan TEXT NOT NULL,
    jam_operasional VARCHAR(100) NULL,
    harga_tiket VARCHAR(100) NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    rating_avg DECIMAL(3, 2) DEFAULT 0,
    review_count INT DEFAULT 0,
    view_count INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (id_kecamatan) REFERENCES kecamatans(id) ON DELETE CASCADE,
    INDEX idx_kecamatan (id_kecamatan),
    INDEX idx_slug (slug),
    INDEX idx_rating (rating_avg),
    FULLTEXT idx_fulltext (nama_objek, alamat, keterangan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabel: `fotos`
```sql
CREATE TABLE fotos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fotoable_id BIGINT UNSIGNED NOT NULL,
    fotoable_type VARCHAR(50) NOT NULL,
    path VARCHAR(255) NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    INDEX idx_fotoable (fotoable_id, fotoable_type)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabel: `events`
```sql
CREATE TABLE events (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_objek BIGINT UNSIGNED NULL,
    nama_event VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    keterangan TEXT NOT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (id_objek) REFERENCES objek_wisatas(id) ON DELETE SET NULL,
    INDEX idx_tanggal (tanggal_mulai, tanggal_selesai),
    INDEX idx_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabel: `fasilitas_wisatas`
```sql

CREATE TABLE fasilitas_wisatas (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_objek BIGINT UNSIGNED NOT NULL,
    nama_fasilitas VARCHAR(50) NOT NULL,
    deskripsi VARCHAR(500) NULL,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    icon VARCHAR(50) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (id_objek) REFERENCES objek_wisatas(id) ON DELETE CASCADE,
    INDEX idx_objek (id_objek)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

#### Tabel: `reviews`
```sql
CREATE TABLE reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_user BIGINT UNSIGNED NOT NULL,
    id_objek BIGINT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    judul VARCHAR(100) NULL,
    komentar TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    approved_by BIGINT UNSIGNED NULL,
    approved_at TIMESTAMP NULL,
    helpful_count INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,
    FOREIGN KEY (id_user) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (id_objek) REFERENCES objek_wisatas(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    UNIQUE KEY unique_user_objek (id_user, id_objek),
    INDEX idx_status (status),
    INDEX idx_objek (id_objek),
    INDEX idx_rating (rating)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### 6.2 API Endpoints (Laravel Routes)

**Authentication**:
```
POST   /api/login          - User/Admin login
POST   /api/register       - User registration
POST   /api/logout         - Logout
GET    /api/user           - Get authenticated user
```

**Objek Wisata**:
```
GET    /api/objek-wisata              - List (public)
GET    /api/objek-wisata/{slug}       - Detail (public)
POST   /api/objek-wisata              - Create (admin)
PUT    /api/objek-wisata/{id}         - Update (admin)
DELETE /api/objek-wisata/{id}         - Delete (admin)
GET    /api/objek-wisata/search       - Search

```

**Event**:
```
GET    /api/events              - List (public)
GET    /api/events/{slug}       - Detail (public)
POST   /api/events              - Create (admin)
PUT    /api/events/{id}         - Update (admin)
DELETE /api/events/{id}         - Delete (admin)
```

**Review**:
```
GET    /api/reviews/{objek_id}        - List by objek (public)
POST   /api/reviews                   - Create (user)
PUT    /api/reviews/{id}/approve      - Approve (admin)
DELETE /api/reviews/{id}              - Delete (admin)
```

**Dashboard**:
```

GET    /api/dashboard/stats           - Statistics (admin)
GET    /api/dashboard/charts          - Chart data (admin)
```

### 6.3 Technology Stack

**Backend**:
- PHP 8.1+
- Laravel 10.x
- MySQL 8.0
- Redis (caching, queue)

**Frontend**:
- HTML5
- CSS3 (Tailwind CSS 3.x atau Bootstrap 5.3)
- JavaScript (Alpine.js 3.x untuk interaktivitas)
- Leaflet.js 1.9+ (maps)
- Leaflet Routing Machine (routing)
- Chart.js (dashboard charts)

**Development Tools**:
- Composer
- NPM/Yarn
- Laravel Mix/Vite
- Git

**Testing**:
- PHPUnit (unit testing)
- Laravel Dusk (browser testing - optional)
- Postman/Insomnia (API testing)

### 6.4 Deployment
- **Hosting**: VPS (DigitalOcean, AWS, Azure) atau Shared Hosting dengan PHP support
- **Domain**: Custom domain dengan SSL
- **CI/CD**: GitHub Actions atau GitLab CI (optional)

### 6.5 Documentation
- API Documentation (Postman Collection atau Swagger/OpenAPI)
- User Manual (PDF)
- Admin Manual (PDF)
- Technical Documentation (Code comments, README)

### 6.6 Testing Scenarios

**Black Box Testing**:
1. Login dengan kredensial valid/invalid
2. CRUD operations untuk setiap modul
3. Form validation
4. File upload
5. Search functionality
6. Map rendering dan routing
7. Review submission dan approval flow

**User Acceptance Testing (UAT)**:
1. User flow: Cari wisata → Lihat detail → Lihat rute
2. Admin flow: Login → Tambah objek wisata → Approve review
3. Performance testing (load time)
4. Usability testing (interview dengan user)

---

## Appendix A: Glossary

- **SIG**: Sistem Informasi Geografis
- **CRUD**: Create, Read, Update, Delete
- **ORM**: Object-Relational Mapping
- **API**: Application Programming Interface
- **HTTPS**: Hypertext Transfer Protocol Secure
- **CSRF**: Cross-Site Request Forgery
- **XSS**: Cross-Site Scripting
- **UMKM**: Usaha Mikro Kecil Menengah
- **PSR**: PHP Standard Recommendation

---

## Appendix B: Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | 18 Jan 2026 | Tim SRS | Initial document |

---

**Catatan Optimasi**:
1. Menggunakan **slug** untuk URL SEO-friendly
2. **Soft delete** untuk data sensitif
3. **Polymorphic relationships** untuk foto (dapat digunakan oleh objek wisata dan event)
4. **Indexing** database untuk performa query
5. **Fulltext search** untuk pencarian lebih cepat
6. **Rating average** dan **review count** disimpan di tabel objek_wisata (denormalisasi) untuk performa
7. **Alpine.js** dipilih sebagai frontend framework karena ringan dan mudah dipelajari
8. **Tailwind CSS** atau **Bootstrap 5** untuk styling yang konsisten dan responsive

Dokumen SRS ini siap digunakan sebagai panduan pengembangan sistem. Tim developer dapat langsung memulai implementasi berdasarkan spesifikasi yang telah didefinisikan.
