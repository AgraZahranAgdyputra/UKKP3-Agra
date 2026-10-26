# Portal Pengaduan

Aplikasi latihan sertifikasi kompetensi berbasis Laravel untuk mengelola user, kategori, dan pengaduan dengan role admin, staff, dan customer.

## Persiapan setelah clone

Pastikan PHP dan Composer, Node.js dan npm, serta database MySQL sudah tersedia. Dari folder proyek, jalankan:

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

`composer install` memasang dependency PHP proyek, termasuk Laravel, Breeze, dan Spatie Laravel Permission. `npm install` memasang dependency frontend/Tailwind. Salin `.env.example` untuk membuat konfigurasi lokal; `key:generate` mengisi application key.

Atur koneksi database di `.env` sesuai database lokal, misalnya:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gas1
DB_USERNAME=root
DB_PASSWORD=
```

Buat database `gas1` terlebih dahulu di MySQL/phpMyAdmin, atau sesuaikan nama dan kredensial di atas. Kemudian buat tabel dan data role/permission:

```bash
php artisan migrate
php artisan db:seed --class=RolePermissionSeeder
php artisan storage:link
```

Migration membuat tabel aplikasi. Seeder membuat role dan permission; jalankan lagi bila perlu karena menggunakan `firstOrCreate` dan menyelaraskan permission role. `storage:link` menghubungkan file publik dari `storage/app/public` agar lampiran dapat diakses.

## Menjalankan aplikasi

Buka dua terminal dari folder proyek. Terminal pertama:

```bash
php artisan serve
```

Terminal kedua:

```bash
npm run dev
```

Buka URL yang ditampilkan oleh `php artisan serve` (umumnya `http://127.0.0.1:8000`). Untuk membangun aset frontend tanpa Vite development server:

```bash
npm run build
```

## Role dan akun

- **Admin:** mengelola user dan kategori, serta semua aksi pengaduan.
- **Staff:** membuka dashboard dan melihat semua pengaduan.
- **Customer:** membuka dashboard, melihat pengaduannya sendiri, membuat dan mengedit pengaduannya sendiri.
- Pendaftaran publik otomatis memberikan role `customer`.

Untuk memberikan role kepada akun yang sudah ada, buka Tinker:

```bash
php artisan tinker
```

Lalu jalankan, sesuaikan email dengan akun yang ada:

```php
$user = App\Models\User::where('email', 'admin@example.com')->firstOrFail();
$user->syncRoles('admin');
```

Ganti email dan role sesuai kebutuhan (`admin`, `staff`, atau `customer`). `syncRoles()` mengganti role yang dimiliki akun menjadi role yang disebutkan. Gunakan `$user->assignRole('staff')` jika ingin menambahkan role tanpa menghapus role lainnya. Keluar dari Tinker dengan:

```php
exit
```

## Perintah pengembangan yang berguna

```bash
php artisan route:list
php artisan permission:cache-reset
php artisan view:clear
php artisan test
```

`route:list` memeriksa route yang terdaftar. Reset cache permission setelah mengubah role/permission jika hasil akses belum berubah. `view:clear` menghapus hasil kompilasi Blade. `test` menjalankan test proyek.

> Jangan gunakan `php artisan migrate:fresh` pada database yang berisi data penting: perintah tersebut menghapus semua tabel lalu membuat ulang skema.

## Materi memahami kode proyek

Bagian ini merangkum konsep yang sering dipakai saat membuat CRUD dan RBAC. Setiap konsep disertai contoh yang benar-benar ada di proyek ini.

### 1. Variable (variabel)

Variabel adalah nama untuk menyimpan nilai agar bisa dipakai lagi. Di PHP, nama variabel diawali `$`.

Contoh: di `PengaduanController@index`, `$user` menyimpan akun yang sedang login, sedangkan `$query` menyimpan query pengaduan sebelum dijalankan.

```php
$user = auth()->user();
$query = Pengaduan::with(['user', 'category'])->latest();
```

Nilainya bisa berupa objek, teks, angka, array, atau hasil query. Nama variabel harus konsisten: jika view membutuhkan `$users`, controller juga harus mengirim variabel bernama `$users`.

### 2. Array dan associative array

Array menyimpan beberapa nilai. Associative array menyimpan pasangan kunci dan nilai.

Contoh daftar aturan validasi di `PengaduanController@store`:

```php
[
    'title' => ['required', 'string', 'max:255'],
    'location' => ['nullable', 'string', 'max:255'],
]
```

`title` dan `location` adalah key; array di sebelah kanan berisi aturan untuk masing-masing input. Di controller yang sama, `$validated` adalah associative array berisi data yang sudah lolos validasi.

### 3. Fungsi dan method

Fungsi adalah blok kode bernama yang bisa dijalankan. Method adalah fungsi yang menjadi bagian dari class.

Contoh method controller: `PengaduanController@index`, `@store`, `@edit`, dan `@update`. Laravel memanggil method yang sesuai ketika sebuah route menerima request.

```php
public function index()
{
    // Menyiapkan dan menampilkan daftar pengaduan.
}
```

Method dapat menerima parameter. Misalnya `edit(Pengaduan $pengaduan)` menerima satu data pengaduan.

### 4. Class dan object

Class adalah cetak biru; object adalah nilai yang dibuat dari class tersebut. Model Eloquent seperti `Pengaduan`, `Category`, dan `User` adalah class untuk berinteraksi dengan tabel database.

Contoh di `PengaduanController@create`:

```php
'pengaduan' => new Pengaduan(),
```

`new Pengaduan()` membuat object kosong yang dipakai form untuk mode tambah. Saat mode edit, controller mengirim object yang sudah ada dari database.

### 5. Model, tabel, dan Eloquent

Model mewakili data/tabel. Eloquent adalah ORM Laravel: cara membaca dan mengubah database menggunakan object dan method PHP, bukan menulis SQL untuk setiap operasi.

Contoh:

```php
$categories = Category::orderBy('name')->get();
$pengaduan->update($validated);
```

`orderBy()` menyusun hasil berdasarkan nama; `get()` menjalankan query dan mengambil hasil. `update()` menyimpan perubahan object ke database. Model ada di `app/Models/`.

### 6. Migration dan skema database

Migration adalah catatan perubahan struktur database yang dapat dijalankan dan dilacak Laravel. `up()` menerapkan perubahan; `down()` membatalkannya.

Contoh: `2026_10_25_114440_create_pengaduans_table` membuat tabel pengaduan; `2026_10_26_165700_add_location_to_pengaduans_table` menambahkan kolom lokasi. Setelah migration dijalankan, mengedit file lama tidak mengubah database yang sudah telanjur dibuat—buat migration baru untuk perubahan berikutnya.

```php
$table->foreignId('user_id')->constrained();
$table->string('location')->nullable();
```

`foreignId` bersama `constrained()` membuat foreign key ke tabel terkait. `nullable()` mengizinkan nilai kosong. `php artisan migrate` menjalankan migration yang belum pernah dijalankan.

### 7. Relasi antar tabel

Relasi memberi tahu Eloquent bagaimana data saling berhubungan.

- Satu user dapat mempunyai banyak pengaduan: `User::pengaduans()` memakai `hasMany`.
- Satu pengaduan dimiliki satu user: `Pengaduan::user()` memakai `belongsTo`.
- Satu kategori dapat dipakai banyak pengaduan: `Category::pengaduans()` memakai `hasMany`.
- Satu pengaduan mempunyai satu kategori: `Pengaduan::category()` memakai `belongsTo`.

Foreign key yang dipakai adalah `user_id` dan `category_id` di tabel `pengaduans`. Contoh penggunaan relasi saat membuat pengaduan ada di `PengaduanController@store`:

```php
$request->user()->pengaduans()->create($validated);
```

Relasi user otomatis mengisi `user_id` dengan ID akun yang login.

### 8. `$fillable` dan mass assignment

Mass assignment adalah mengisi beberapa kolom model sekaligus menggunakan array. `$fillable` menentukan kolom mana yang boleh diisi dengan cara tersebut.

Contoh ada di `app/Models/Pengaduan.php`:

```php
protected $fillable = [
    'user_id',
    'category_id',
    'title',
    'description',
    'location',
];
```

Tanpa kolom yang diizinkan di `$fillable`, pemanggilan `create()` atau `update()` tidak akan mengisinya sebagaimana diharapkan. Jangan pernah menerima `user_id` dari form customer untuk menentukan pemilik data; ambil dari user yang login.

### 9. Route dan HTTP method

Route memetakan URL dan HTTP method ke controller/method. Daftarnya ada di `routes/web.php`.

| Tujuan | HTTP method | Contoh route bernama |
|---|---|---|
| Menampilkan daftar | GET | `pengaduans.index` |
| Menampilkan form tambah | GET | `pengaduans.create` |
| Menyimpan data baru | POST | `pengaduans.store` |
| Menampilkan form edit | GET | `pengaduans.edit` |
| Menyimpan perubahan | PUT | `pengaduans.update` |
| Menghapus data | DELETE | `pengaduans.destroy` |

Nama route dipakai di Blade seperti `route('pengaduans.index')`, sehingga link tidak perlu menulis URL secara manual. Route kategori dan user menggunakan `Route::resource`; route pengaduan ditulis satu per satu agar permission tiap aksi berbeda.

### 10. Controller dan alur request

Controller menerima request, menjalankan aturan aplikasi, berinteraksi dengan model, lalu mengembalikan response.

Contoh alur membuat pengaduan di `PengaduanController@store`:

1. Validasi input.
2. Pisahkan file upload dari data kolom.
3. Buat data pengaduan melalui relasi user yang sedang login.
4. Simpan lampiran di storage pada folder yang memakai ID pengaduan.
5. Redirect kembali ke daftar dengan pesan sukses.

Pola serupa terlihat di `CategoryController@store` dan `UserController@store`.

### 11. Request, validasi, dan data tervalidasi

`Request` mewakili data yang dikirim browser ke server. `$request->validate()` memeriksa data dan mengembalikan hanya input tervalidasi. Jika gagal, Laravel kembali ke form dengan pesan error.

Contoh validasi di `PengaduanController@store`:

```php
'category_id' => ['required', 'exists:categories,id'],
'upload_file' => ['nullable', 'array', 'max:10'],
'upload_file.*' => ['file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
```

`required` wajib diisi; `nullable` boleh kosong; `exists` memastikan kategori benar-benar ada; `upload_file.*` memeriksa setiap file dalam array; `max:5120` berarti maksimal 5120 KB (5 MB) per file. Validasi di server tetap diperlukan meskipun form memiliki atribut HTML seperti `required`.

### 12. CRUD

CRUD adalah singkatan dari Create, Read, Update, Delete:

- **Create:** membuat record, misalnya `PengaduanController@store`.
- **Read:** menampilkan daftar, misalnya `PengaduanController@index`.
- **Update:** mengubah record, misalnya `PengaduanController@update`.
- **Delete:** menghapus record, misalnya `PengaduanController@destroy`.

Form tambah dan edit pengaduan memakai satu Blade yang sama, `resources/views/pengaduans/form.blade.php`. Nilai `$pengaduan->exists` menentukan apakah form sedang membuat data baru atau mengedit data.

### 13. Blade dan tampilan

Blade adalah template Laravel untuk HTML. File Blade berada di `resources/views/` dan berakhiran `.blade.php`.

- `{{ $nilai }}` menampilkan nilai dengan escaping HTML.
- `@if` / `@else` / `@endif` membuat kondisi.
- `@foreach` mengulang data.
- `@csrf` menambahkan token keamanan ke form.
- `@method('PUT')` mengirim method PUT dari form HTML yang sebenarnya hanya mendukung GET dan POST.
- `old('title', $pengaduan->title)` mempertahankan input sebelumnya jika validasi gagal.
- `@can('edit pengaduan')` hanya menampilkan bagian tampilan kepada user yang berizin.

Contoh-contoh ini ada di `resources/views/pengaduans/index.blade.php` dan `form.blade.php`. `@can` memperbaiki tampilan, tetapi bukan pengganti middleware pada route.

### 14. Authentication dan authorization

- **Authentication (autentikasi):** memastikan siapa user yang sedang memakai aplikasi, biasanya melalui login. Middleware `auth` meminta user login.
- **Authorization (otorisasi):** memeriksa apa yang boleh dilakukan user. Middleware permission dan role menjalankan pemeriksaan ini.

Contoh dashboard di `routes/web.php`:

```php
->middleware(['auth', 'verified', 'permission:view dashboard'])
```

Artinya, user harus login, email terverifikasi, dan memiliki permission `view dashboard`.

### 15. RBAC: role dan permission

RBAC (Role-Based Access Control) mengatur akses berdasarkan role. Role berisi kumpulan permission; user diberi role, lalu permission role menentukan aksi yang boleh dilakukan.

Aturan proyek ditetapkan di `database/seeders/RolePermissionSeeder.php`:

- Admin: semua permission di seeder.
- Staff: `view dashboard` dan `view pengaduan`.
- Customer: `view dashboard`, `view pengaduan`, `create pengaduan`, `edit pengaduan`.

Route pengaduan di `routes/web.php` memeriksa permission sesuai aksi. Contohnya, route GET daftar memakai `permission:view pengaduan`, sedangkan POST simpan memakai `permission:create pengaduan`. Route harus dilindungi walaupun tombol di Blade sudah disembunyikan.

`syncPermissions()` menyamakan permission role dengan daftar yang diberikan. `syncRoles()` mengganti role user dengan role yang diberikan; `assignRole()` menambahkan role tanpa mengganti role yang sudah ada. Pendaftaran otomatis memberi role customer di `RegisteredUserController@store`.

### 16. Kepemilikan data (row-level authorization)

Permission menjawab “boleh melakukan aksi apa”; kepemilikan data menjawab “boleh mengakses record yang mana”. Karena itu, customer tidak cukup hanya memiliki permission `edit pengaduan`: server juga harus memastikan pengaduan tersebut miliknya.

Di `PengaduanController@index`, query customer difilter memakai `user_id`. Di `ensureCanEdit()`, admin boleh mengedit semua pengaduan, sedangkan customer hanya boleh mengedit jika `user_id` cocok dengan ID akun login. Pemeriksaan dilakukan di controller, bukan hanya dengan menyembunyikan tombol di Blade.

### 17. Upload banyak file

Form `resources/views/pengaduans/form.blade.php` memakai `name="upload_file[]"` dan `multiple`; browser mengirim beberapa file sebagai array. Controller memvalidasi tiap file lalu menyimpannya ke disk `public`, di folder seperti `pengaduan/12/` berdasarkan ID pengaduan.

File fisik berada di `storage/app/public/`; `php artisan storage:link` membuat jalur publik `public/storage`. Halaman `pengaduans/files.blade.php` menampilkan preview gambar atau tautan dokumen. Database menyimpan data pengaduan, sedangkan file disimpan di storage. Kolom `upload_file` lama di record pengaduan tetap diisi string kosong untuk kompatibilitas; daftar file baru dibaca dari folder storage.

Karena disk `public` membuat file dapat diakses melalui URL publik, jangan gunakan pendekatan ini untuk lampiran sensitif tanpa mengubah penyimpanan menjadi privat dan memeriksa akses saat file diminta.

### 18. Flash message dan redirect

Redirect mengirim browser ke halaman lain setelah operasi selesai. Flash message adalah pesan sementara yang tersedia pada request berikutnya.

Contoh di controller:

```php
return redirect()->route('pengaduans.index')
    ->with('success', 'Pengaduan berhasil dibuat.');
```

View membaca pesan dengan `session('success')`. Pola ini menghindari form terkirim ulang saat halaman di-refresh.

### 19. Query scope dan eager loading

Query mengambil record dari database. Contoh `latest()` mengurutkan record terbaru terlebih dahulu. `where()` menyaring record:

```php
$query->where('user_id', $user->id);
```

`with(['user', 'category'])` memuat relasi user dan kategori bersama query utama, mengurangi query berulang saat view membaca nama pelapor dan nama kategori. Ini disebut eager loading.

### 20. Relasi dan penghapusan

Foreign key menjaga hubungan database tetap valid. Pada migration pengaduan, foreign key user dan kategori memakai `restrictOnDelete()`: data user/kategori yang masih digunakan tidak bisa dihapus sembarangan.

`UserController@destroy` juga mengecek apakah user masih memiliki pengaduan dan menolak penghapusan jika iya. Penanganan kategori yang masih dipakai sebaiknya mengikuti pola serupa agar pengguna mendapat pesan yang jelas, bukan error database.

### 21. Cara membaca satu fitur dari awal sampai akhir

Untuk memahami fitur pengaduan, ikuti urutan ini:

1. `routes/web.php`: cari URL dan permission route.
2. `PengaduanController`: lihat validasi, aturan akses, dan operasi model.
3. `Pengaduan.php`: periksa `$fillable` dan relasi.
4. `database/migrations/*pengaduans*`: lihat kolom dan foreign key.
5. `resources/views/pengaduans/`: lihat form, daftar, dan lampiran.

Pola yang sama berlaku untuk kategori dan user. Saat ada error, baca pesan error, cari file dan nomor baris yang disebutkan, lalu ikuti alur request dari route ke controller dan view.

### 22. Operator dan simbol PHP yang sering dipakai

Operator adalah simbol atau kata yang melakukan operasi pada nilai, misalnya memberi nilai ke variabel, membandingkan dua nilai, atau menggabungkan beberapa kondisi.

| Operator | Nama/fungsi | Contoh | Artinya |
|---|---|---|---|
| `=` | Assignment (pemberian nilai) | `$user = auth()->user();` | Simpan nilai di kanan ke variabel di kiri. Ini bukan operator perbandingan. |
| `==` | Sama dengan (longgar) | `$a == $b` | Membandingkan nilai, tetapi PHP dapat mengonversi tipe data terlebih dahulu. |
| `===` | Identik (strict equality) | `$a === $b` | Membandingkan nilai dan tipe data; keduanya harus sama. |
| `!=` atau `<>` | Tidak sama dengan (longgar) | `$a != $b` | Bernilai `true` jika nilainya berbeda setelah aturan konversi PHP. |
| `!==` | Tidak identik | `$a !== $b` | Bernilai `true` jika nilai atau tipe datanya berbeda. |
| `>` / `<` | Lebih besar / lebih kecil | `$jumlah > 0` | Membandingkan urutan atau besar nilai. |
| `>=` / `<=` | Lebih besar/kecil atau sama dengan | `$jumlah >= 1` | Membandingkan nilai sekaligus menerima nilai yang sama. |
| `=>` | Pemisah key dan value array | `'title' => ['required']` | Memasangkan key `title` dengan value aturan validasinya. Bukan operator panah untuk object. |
| `->` | Operator object | `$request->user()` | Mengakses method atau properti milik object. |
| `::` | Scope resolution operator | `Category::orderBy('name')` | Mengakses method atau konstanta class, umumnya method statis. |
| `??` | Null coalescing | `$files = $validated['upload_file'] ?? [];` | Gunakan nilai kiri jika tersedia dan bukan `null`; jika tidak, gunakan nilai kanan. |
| `? :` | Ternary | `$aktif ? 'Aktif' : 'Nonaktif'` | Jika kondisi benar pilih nilai sebelum `:`, jika salah pilih nilai sesudahnya. |
| `&&` | AND (dan) | `$a && $b` | Benar hanya jika kedua kondisi benar. |
| `||` | OR (atau) | `$a || $b` | Benar jika setidaknya salah satu kondisi benar. |
| `!` | NOT (kebalikan) | `! $aktif` | Mengubah `true` menjadi `false` dan sebaliknya. |
| `.` | Penggabung string | `'pengaduan/' . $id` | Menggabungkan teks dengan nilai lain menjadi satu string. |

Contoh perbedaan `==` dan `===`:

```php
5 == '5';   // true: nilainya dianggap sama setelah konversi tipe
5 === '5';  // false: integer dan string adalah tipe yang berbeda
5 === 5;    // true: nilai dan tipe datanya sama
```

Di `PengaduanController`, kondisi kepemilikan menggunakan `===`:

```php
$pengaduan->user_id === $user->id
```

Maksudnya memastikan ID pemilik pengaduan sama dengan ID user yang login, termasuk tipe nilainya. `&&` dan `||` juga dipakai di kondisi akses untuk menggabungkan pemeriksaan role dan kepemilikan.

Perhatikan khususnya perbedaan ini:

- `$a = $b` berarti **beri nilai**.
- `$a == $b` berarti **bandingkan nilai**.
- `$a === $b` berarti **bandingkan nilai dan tipe**.
- `'key' => $value` berarti **pasangkan key dengan value dalam array**.
- `$object->method()` berarti **panggil method pada object**.
- `ClassName::method()` berarti **akses method melalui class**.

---

