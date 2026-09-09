# Dokumentasi Sistem — Shipment App

## 1. Gambaran Umum

**Shipment App** adalah aplikasi berbasis web untuk mengelola alur pengiriman barang (shipment) perusahaan — mulai dari pembuatan permintaan pengiriman oleh departemen, tracking status, hingga verifikasi kedatangan barang oleh tim IMC (Import Material Control) di warehouse.

- **Framework:** Laravel 10 (PHP ^8.1)
- **Autentikasi:** Laravel Breeze
- **Otorisasi:** Spatie Laravel-Permission (role & permission based)
- **Tabel data:** Yajra DataTables (server-side processing)
- **Frontend:** Blade + Alpine.js + Tailwind CSS, Vite sebagai bundler
- **Library UI tambahan:** ApexCharts (grafik dashboard), FullCalendar, Flatpickr (date picker), SweetAlert2 (notifikasi), Font Awesome
- **PDF:** barryvdh/laravel-dompdf
- **Export:** maatwebsite/excel
- **HTTP Client:** Guzzle (dipakai untuk konsumsi API HSCODE eksternal)

## 2. Arsitektur

Aplikasi mengikuti pola **Controller → Service → Repository (Eloquent)** agar logika bisnis terpisah dari akses data:

```
Request
  → Controller (app/Http/Controllers)
      → Service (app/Services)
          → Repository Interface (app/Repositories/Interfaces)
              → Repository Eloquent Implementation (app/Repositories/Eloquent)
                  → Model (app/Models)
```

- **Controller**: menangani request HTTP, validasi (via Form Request), memanggil service, dan mengembalikan view/response.
- **Service**: berisi logika bisnis (mis. transaksi DB, snapshot history, resolve item baru/lama).
- **Repository**: abstraksi query terhadap model, di-bind lewat interface (lihat `RepositoryServiceProvider` / provider terkait) agar mudah di-mock saat testing.
- **DataTables (Yajra)**: sebagian modul master (Department dsb.) memakai class DataTable khusus di `app/DataTables`, sebagian lain (mis. Shipment) membentuk response DataTables langsung di controller memakai `Yajra\DataTables\Facades\DataTables`.

## 3. Modul & Fitur

### 3.1 Autentikasi & Profil
- Login, register, lupa password, verifikasi email, konfirmasi password — hasil scaffold **Laravel Breeze** (`app/Http/Controllers/Auth/*`).
- Halaman profil user (`ProfileController`): update profil & hapus akun.

### 3.2 Dashboard (`DashboardController`)
- Menampilkan ringkasan shipment per bulan/tahun (filter `month` & `year` dari query string):
  - Total shipment berstatus **Draft**, **Pending**, **Process**, dan total keseluruhan.
  - 5 shipment terbaru beserta supplier & status.
  - 5 supplier dengan jumlah shipment terbanyak.
  - Shipment milik departemen user yang login (10 terakhir + total).
- Untuk role selain `Admin`, `Import`, `Buyer`, data dibatasi hanya untuk `department_id` milik user yang login.

### 3.3 Shipment (`ShipmentController`, `ShipmentService`)
Modul inti aplikasi — mengelola pengiriman barang antar departemen/supplier.

**Alur data:**
1. **Create** (`store`): user mengisi PO, No. Invoice, No. BL, supplier, departemen, ETD, ETA, catatan, dan daftar item.
   - Supplier baru bisa dibuat on-the-fly jika user memilih opsi "Other" (`supplier_id === 'other'`) → `Supplier::firstOrCreate`.
   - Item dapat diambil dari data referensi HSCODE (API eksternal via `HscodeDataService`) atau item baru (`Item::firstOrCreate`).
   - Status awal shipment otomatis di-set ke **Draft**.
   - Setiap create/update dibungkus **DB transaction**, dan sistem otomatis membuat **snapshot history** (`ShipmentHistory` + `ShipmentHistoryItems`) untuk audit trail.
2. **Update** (`update`): dapat mengubah field shipment maupun status (jika user memiliki permission), serta melakukan sinkronisasi item (tambah/ubah/hapus item berdasarkan kolom `rf`). Setiap perubahan menghasilkan snapshot history baru.
3. **Index** (`index`): ditampilkan via Yajra DataTables (ajax), dengan aturan akses:
   - Role `Admin`, `Import`, `IMC`, `Buyer` → melihat **semua** shipment.
   - Role lain → hanya shipment milik **departemennya sendiri**.
   - Bisa difilter berdasarkan status (`?status=`).
   - Tombol **Edit** hanya tampil jika: (a) user punya permission `shipment.edit` **dan** ETD belum lewat, **atau** (b) user berperan `Admin`/`Import` (bisa edit kapan pun).
4. **Show**: detail shipment lengkap dengan relasi (supplier, departemen, status, item, history, hasil verifikasi IMC).
5. **History** (`shipments.history`): membandingkan snapshot history satu-per-satu (diff per field shipment dan per item) untuk menunjukkan apa saja yang berubah antar revisi.

**Relasi model `Shipment`:**
- `belongsTo` Supplier, Department, Status (`status_id`), User (`created_by` → creator)
- `hasMany` ShipmentItem, ShipmentHistory
- `hasOne` ImcVerif

**Status shipment (seed default):** `Draft` → `Pending` → `Process` → `Delivered` (ada juga status `Rejected` yang direferensikan di UI meskipun tidak ada di seeder default).

### 3.4 IMC Verification (`ImcVerifController`, model `ImcVerif` & `ImcVerifItem`)
- Merepresentasikan proses verifikasi fisik barang oleh tim IMC di warehouse setelah barang tiba: siapa yang verifikasi (`verified_by`), kapan (`verified_at`), di warehouse mana, kuantitas aktual per item (`quantity_actual`), kondisi barang (`condition`), dan catatan (`remarks`).
- Relasi: `ImcVerif belongsTo Shipment, Warehouse, User (verifiedBy), Status`; `hasMany ImcVerifItem`.
- **Status implementasi saat ini:** route `GET /imc` terdaftar dan permission (`imc.view`, `imc.verify`) sudah didefinisikan, namun `ImcVerifController` masih kosong (belum ada logic index/store) — modul ini baru berupa skeleton/struktur data, logic bisnis belum diimplementasikan.

### 3.5 Tracking (`TrackingController`)
- Route `GET /tracking` terdaftar dengan permission `tracking.view` (diberikan ke role `User`), namun controller-nya juga masih kosong — belum ada logic.

### 3.6 Master Data (`app/Http/Controllers/Master/*`)
CRUD data master, masing-masing dengan Service + Repository sendiri, dan sebagian pakai Yajra DataTable class (`app/DataTables`):

| Modul | Deskripsi |
|---|---|
| **Department** | Data departemen perusahaan; digunakan untuk mengelompokkan user & shipment. |
| **Warehouse** | Data gudang tujuan pengiriman/verifikasi IMC. |
| **Supplier** | Data pemasok barang; punya relasi ke shipment. |
| **Item** | Master barang (nama, kode, satuan/UOM, deskripsi); di-lookup/`firstOrCreate` otomatis saat input shipment. |
| **Status** | Master status shipment/IMC (Draft, Pending, Process, Delivered, dst). |
| **User** | Manajemen user, terhubung ke Department dan Role (Spatie). |
| **Role** | Manajemen role & permission (Spatie Laravel-Permission). |

### 3.7 Integrasi HSCODE eksternal (`HscodeDataService`)
- Mengambil daftar referensi item/HSCODE dari API eksternal (`config('services.native_api.url')`, env `HSCODE_API_URL`).
- `getAll()` di-**cache 1 jam** (`Cache::remember('hscode_data', 1h)`) agar tidak membebani API eksternal setiap kali form create/edit shipment dibuka.
- `findByRf($rf)` mengambil satu data spesifik berdasarkan kode referensi (RF), tanpa cache.
- Data ini dipakai di form Create/Edit Shipment sebagai sumber pilihan item + auto-fill HSCODE.

## 4. Model Data (Eloquent) & Relasi

| Model | Relasi Utama |
|---|---|
| `User` | `belongsTo Department`; role/permission via `HasRoles` (Spatie) |
| `Department` | punya banyak User & Shipment |
| `Supplier` | `hasMany Shipment` |
| `Warehouse` | dipakai oleh `ImcVerif` |
| `Item` | `hasMany ShipmentItem` |
| `Status` | dipakai oleh `Shipment` & `ImcVerif` sebagai status |
| `Shipment` | `belongsTo` Supplier, Department, Status, User(creator); `hasMany` ShipmentItem, ShipmentHistory; `hasOne` ImcVerif |
| `ShipmentItem` | item dalam satu shipment (rf, hscode, quantity, uom, notes) |
| `ShipmentHistory` / `ShipmentHistoryItems` | snapshot histori perubahan shipment & itemnya (audit trail) |
| `ImcVerif` | `belongsTo` Shipment, Warehouse, User(verifiedBy), Status; `hasMany ImcVerifItem` |
| `ImcVerifItem` | hasil verifikasi per item (`quantity_actual`, `condition`, `remarks`) |
| `Role` | representasi role tambahan (di luar model Spatie `Role`) |

Sebagian besar model menggunakan **SoftDeletes** (hapus data tidak permanen, hanya menandai `deleted_at`).

## 5. Otorisasi (Roles & Permissions)

Menggunakan **Spatie Laravel-Permission**. Permission didefinisikan per-modul dengan format `modul.aksi`, contoh: `master.department.view`, `shipment.create`, `imc.verify`, `tracking.view`.

**Role default (seeder `RolePermissionSeeder`):**

| Role | Permission |
|---|---|
| **Admin** | Semua permission (full access) |
| **Import** | `shipment.view`, `shipment.create`, `shipment.edit` |
| **Buyer** | `shipment.view`, `shipment.create`, `shipment.edit` |
| **User** | `tracking.view` |

> Catatan: role `IMC` disebut di beberapa tempat pada logic controller (`ShipmentController::index`, `DashboardController`) sebagai role dengan akses "lihat semua data", namun role `IMC` **belum didefinisikan** di `RolePermissionSeeder` — perlu ditambahkan manual bila role ini digunakan di production.

Middleware permission diterapkan di constructor controller, contoh pada `ShipmentController`:
- `permission:shipment.view` → index, show
- `permission:shipment.create` → create, store
- `permission:shipment.edit` → edit, update
- `permission:shipment.delete` → destroy

## 6. Routing (`routes/web.php`)

Semua route (kecuali halaman login) dilindungi middleware `auth` (dan `verified` untuk beberapa).

| Route | Controller | Keterangan |
|---|---|---|
| `GET /` | — | Redirect ke halaman login |
| `GET /dashboard` | DashboardController@index | Dashboard utama |
| `resource master/departments` | Master\DepartmentController | CRUD Department |
| `resource master/warehouses` | Master\WarehouseController | CRUD Warehouse |
| `resource master/suppliers` | Master\SupplierController | CRUD Supplier |
| `resource master/items` | Master\ItemController | CRUD Item |
| `resource master/statuses` | Master\StatusController | CRUD Status |
| `resource master/users` | Master\UserController | CRUD User |
| `resource master/roles` | Master\RoleController | CRUD Role & Permission |
| `resource shipments` | ShipmentController | CRUD Shipment |
| `GET shipments/{shipment}/history/{history}` | ShipmentController@history | Detail histori/diff shipment |
| `GET /imc` | ImcVerifController@index | Halaman IMC (route ada, controller belum diimplementasikan) |
| `GET /tracking` | TrackingController@index | Halaman Tracking (route ada, controller belum diimplementasikan) |
| `GET/PATCH/DELETE /profile` | ProfileController | Kelola profil user |
| `routes/auth.php` | Auth\* | Login, register, reset password, verifikasi email, dll (Breeze) |

## 7. Struktur Views (`resources/views`)

```
resources/views/
├── auth/            # Login, register, reset password (Breeze)
├── components/      # Blade components reusable
├── layouts/         # Layout utama aplikasi
├── master/          # View CRUD master data (department, item, role, status, supplier, user, warehouse)
├── pages/shipment/  # View shipment (index, create, edit, show, viewDetailHistory)
├── profile/         # View profil user
└── dashboard.blade.php
```

## 8. Konfigurasi Environment Penting

| Variabel `.env` | Kegunaan |
|---|---|
| `DB_*` | Koneksi database (MySQL) |
| `HSCODE_API_URL` | Base URL API eksternal untuk data referensi item/HSCODE (`config/services.php` → `native_api.url`) |
| `CACHE_DRIVER`, `SESSION_DRIVER`, `QUEUE_CONNECTION` | Driver infrastruktur standar Laravel |
| `MAIL_*` | Konfigurasi email (verifikasi akun, reset password) |

## 9. Menjalankan Aplikasi (Development)

```bash
composer install
npm install

cp .env.example .env
php artisan key:generate

# sesuaikan DB_* dan HSCODE_API_URL di .env, lalu:
php artisan migrate --seed

npm run dev        # compile asset (Vite)
php artisan serve  # jalankan server lokal
```

Seeder default (`DatabaseSeeder`) akan membuat:
- Role & permission awal (`RolePermissionSeeder`)
- User admin awal (`AdminUserSeeder`)
- Master status (`StatusSeeder`)
- Master department (`DepartmentSeeder`)

## 10. Catatan Teknis & Area yang Perlu Perhatian

- **`ImcVerifController` dan `TrackingController` masih kosong** — route dan permission sudah disiapkan, tapi belum ada implementasi logic (index/store/dsb). Model & tabel database untuk IMC (`ImcVerif`, `ImcVerifItem`) sudah lengkap dan siap dipakai.
- **Role `IMC`** direferensikan di kode (akses "lihat semua shipment") tapi tidak dibuat di seeder — perlu ditambahkan agar konsisten.
- Query DataTables shipment saat ini melakukan filter status dengan `->filter()` di collection (bukan di level query) — perlu diperhatikan untuk performa jika data shipment sangat besar.
- Snapshot history (`ShipmentHistory`) dibuat setiap kali create/update — cocok untuk audit trail, tapi volume tabel akan terus bertambah seiring waktu (perlu strategi arsip/retensi bila diperlukan di masa depan).
- Data referensi HSCODE dari API eksternal di-cache 1 jam; jika API eksternal down saat cache kosong, `getAll()` mengembalikan array kosong (form create/edit tidak akan menampilkan pilihan item dari API).
