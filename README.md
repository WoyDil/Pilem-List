# Pilem List – Website Film (PHP + SQLite)

Website bergaya Netflix dengan halaman Home, Rating, Genre, detail film
(rating, sinopsis, pemeran), serta login dan CRUD film untuk admin.

## Cara menjalankan

### Opsi 1 – XAMPP / Laragon
1. Salin folder `pilemlist` ke `htdocs` (XAMPP) atau `www` (Laragon).
2. Jalankan Apache.
3. Buka `http://localhost/pilemlist/`

### Opsi 2 – PHP bawaan (tanpa XAMPP)
```
cd pilemlist
php -S localhost:8000
```
Buka `http://localhost:8000/`

Database SQLite dibuat otomatis di `data/pilemlist.sqlite` saat pertama dibuka.
Tidak perlu import SQL atau setting MySQL.

## Akun
**User**: daftar sendiri lewat halaman `register.php` (menu Daftar). User biasa hanya bisa melihat film, tidak bisa masuk ke halaman admin.

Database lama otomatis dimigrasi (kolom `role` ditambahkan).

Ganti password setelah deploy. Cara cepat: buat hash baru dengan
`php -r "echo password_hash('PASSWORD_BARU', PASSWORD_DEFAULT);"`, lalu update
kolom `password` tabel `users` (pakai DB Browser for SQLite).

## Struktur
```
config.php          koneksi DB, tabel otomatis, fungsi bantu (CSRF, session)
index.php           Home
rating.php          peringkat rating
genre.php           filter genre
film.php            detail film
login.php, register.php, logout.php
admin/index.php     daftar + cari + hapus  (Read, Delete)
admin/form.php      tambah + edit          (Create, Update)
admin/delete.php    proses hapus
includes/           header & footer
assets/style.css    tampilan
data/               file database (otomatis)
```

## Catatan keamanan
Sudah memakai prepared statement, password_hash, token CSRF, dan escape output.
Jika di-hosting publik, pakai HTTPS dan blok akses web ke folder `data/`.
=======
# Pilem-List
Project akhir Pemrograman Web Anggota :  
MUHAMMAD FADHIL ARRASYID  
MUHAMMAD FARID ALFARIDZI
