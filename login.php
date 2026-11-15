<?php
$base = ''; $page = 'login'; $title = 'Login';
require __DIR__ . '/config.php';

if (is_logged_in()) { header('Location: ' . (is_admin() ? 'admin/index.php' : 'index.php')); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    $st = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $st->execute([$u]);
    $user = $st->fetch();
    if ($user && password_verify($p, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header('Location: ' . ($user['role'] === 'admin' ? 'admin/index.php' : 'index.php'));
        exit;
    }
    $error = 'Username atau password salah.';
}
require __DIR__ . '/includes/header.php';
?>
<div class="box">
  <h1>Login</h1>
  <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
  <form method="post" autocomplete="off">
    <?= csrf_field() ?>
    <label>Username<input type="text" name="username" value="<?= e($_POST['username'] ?? '') ?>" required autofocus></label>
    <label>Password<input type="password" name="password" required></label>
    <button class="btn" type="submit">Masuk</button>
  </form>
  <p class="mu small">Belum punya akun? <a class="lnk" href="register.php">Daftar sebagai user</a></p>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
