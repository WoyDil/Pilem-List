<?php
$base = ''; $page = 'register'; $title = 'Daftar';
require __DIR__ . '/config.php';

if (is_logged_in()) { header('Location: index.php'); exit; }

$errors = [];
$username = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $username = trim($_POST['username'] ?? '');
    $p1 = $_POST['password'] ?? '';
    $p2 = $_POST['password2'] ?? '';

    if (!preg_match('/^[A-Za-z0-9_]{3,30}$/', $username)) {
        $errors[] = 'Username 3–30 karakter, hanya huruf, angka, dan garis bawah.';
    }
    if (strlen($p1) < 6) $errors[] = 'Password minimal 6 karakter.';
    if ($p1 !== $p2) $errors[] = 'Konfirmasi password tidak sama.';

    if (!$errors) {
        $st = $pdo->prepare("SELECT 1 FROM users WHERE username = ? COLLATE NOCASE");
        $st->execute([$username]);
        if ($st->fetch()) {
            $errors[] = 'Username sudah dipakai.';
        }
    }
    if (!$errors) {
        $st = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
        $st->execute([$username, password_hash($p1, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$pdo->lastInsertId();
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        header('Location: index.php');
        exit;
    }
}
require __DIR__ . '/includes/header.php';
?>
<div class="box">
  <h1>Daftar</h1>
  <?php foreach ($errors as $er): ?><div class="alert err"><?= e($er) ?></div><?php endforeach; ?>
  <form method="post" autocomplete="off">
    <?= csrf_field() ?>
    <label>Username<input type="text" name="username" value="<?= e($username) ?>" required autofocus></label>
    <label>Password<input type="password" name="password" required minlength="6"></label>
    <label>Ulangi password<input type="password" name="password2" required minlength="6"></label>
    <button class="btn" type="submit">Buat akun</button>
  </form>
  <p class="mu small">Sudah punya akun? <a class="lnk" href="login.php">Login</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
