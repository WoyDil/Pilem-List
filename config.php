<?php
// Konfigurasi, koneksi database (SQLite), dan fungsi bantu
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$dbFile = __DIR__ . '/data/pilemlist.sqlite';
$pdo = new PDO('sqlite:' . $dbFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

// Buat tabel otomatis saat pertama kali dijalankan
$pdo->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL,
    role TEXT NOT NULL DEFAULT 'user'
)");
$pdo->exec("CREATE TABLE IF NOT EXISTS films (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    genre TEXT NOT NULL,
    rating REAL NOT NULL DEFAULT 0,
    year INTEGER,
    synopsis TEXT,
    cast_list TEXT,
    color TEXT NOT NULL DEFAULT '#9b1c2c',
    poster_url TEXT,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
)");

// Migrasi: tambah kolom role jika database lama belum punya
$cols = array_column($pdo->query("PRAGMA table_info(users)")->fetchAll(), 'name');
if (!in_array('role', $cols, true)) {
    $pdo->exec("ALTER TABLE users ADD COLUMN role TEXT NOT NULL DEFAULT 'user'");
    $pdo->exec("UPDATE users SET role = 'admin' WHERE username = 'admin'");
}

// Akun admin awal: admin / admin123 (segera ganti setelah login pertama)
if ((int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0) {
    $st = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
    $st->execute(['admin', password_hash('admin123', PASSWORD_DEFAULT)]);
}

// Data film contoh
if ((int)$pdo->query("SELECT COUNT(*) FROM films")->fetchColumn() === 0) {
    $seed = [
        ['Judul Film 1', 'Aksi', 9.1, 2024, '#9b1c2c'],
        ['Judul Film 2', 'Drama', 8.7, 2023, '#1f5a85'],
        ['Judul Film 3', 'Komedi', 8.2, 2025, '#a8650f'],
        ['Judul Film 4', 'Horor', 7.9, 2022, '#4b2f78'],
        ['Judul Film 5', 'Fiksi Ilmiah', 7.4, 2024, '#17705a'],
        ['Judul Film 6', 'Romantis', 6.8, 2023, '#9a3068'],
    ];
    $st = $pdo->prepare("INSERT INTO films (title, genre, rating, year, synopsis, cast_list, color) VALUES (?,?,?,?,?,?,?)");
    foreach ($seed as $i => $f) {
        $n = $i + 1;
        $st->execute([$f[0], $f[1], $f[2], $f[3],
            "Tulis sinopsis Film $n di sini. Ringkas cerita dalam dua sampai tiga kalimat.",
            "Pemeran A, Pemeran B, Pemeran C", $f[4]]);
    }
}

// ---------- Fungsi bantu ----------
function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

function csrf_token() {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field() { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function csrf_check() {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(400);
        exit('Permintaan tidak valid (token CSRF salah).');
    }
}

function is_logged_in() { return !empty($_SESSION['user_id']); }
function is_admin() { return is_logged_in() && ($_SESSION['role'] ?? '') === 'admin'; }
function require_login() {
    if (!is_logged_in()) {
        header('Location: ../login.php');
        exit;
    }
}
// Khusus halaman admin: user biasa tidak boleh masuk
function require_admin() {
    if (!is_logged_in()) {
        header('Location: ../login.php');
        exit;
    }
    if (!is_admin()) {
        flash('Halaman itu khusus admin.', 'err');
        header('Location: ../index.php');
        exit;
    }
}

function flash($msg = null, $type = 'ok') {
    if ($msg !== null) {
        $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
        return;
    }
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}

function tile(array $f, $base = '') {
    $inner = $f['poster_url']
        ? '<img src="' . e($f['poster_url']) . '" alt="">'
        : '<span>' . e(mb_strtoupper(mb_substr($f['title'], 0, 1))) . '</span>';
    return '<a class="tile" href="' . $base . 'film.php?id=' . (int)$f['id'] . '">'
        . '<div class="poster" style="background:' . e($f['color']) . '">' . $inner . '</div>'
        . '<small>' . e($f['title']) . '</small></a>';
}
