<?php
$base = ''; $page = 'reset_password'; $title = 'Reset Password';
require __DIR__ . '/config.php';

$pesan = '';
$tipe_pesan = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token'] ?? '');
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi = $_POST['konfirmasi'] ?? '';

    if (empty($token) || empty($password_baru) || empty($konfirmasi)) {
        $pesan = 'Semua kolom wajib diisi.';
        $tipe_pesan = 'err';
    } elseif ($password_baru !== $konfirmasi) {
        $pesan = 'Password baru dan konfirmasi tidak cocok.';
        $tipe_pesan = 'err';
    } else {
        // Cari user berdasarkan token yang masih berlaku
        $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expires > datetime('now')");
        $stmt->execute([$token]);
        $user = $stmt->fetch();

        if ($user) {
            // Update password dan hapus token
            $hash = password_hash($password_baru, PASSWORD_DEFAULT);
            $update = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE id = ?");
            $update->execute([$hash, $user['id']]);

            $pesan = 'Password berhasil direset! Silakan login.';
            $tipe_pesan = 'ok';
        } else {
            $pesan = 'Token tidak valid atau sudah kedaluwarsa.';
            $tipe_pesan = 'err';
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="box" style="margin-top: 120px;">
  <h1>Reset Password</h1>
  
  <?php if ($pesan): ?>
    <div class="alert <?= $tipe_pesan ?>" style="margin-bottom: 20px;">
      <?= e($pesan) ?>
      <?php if ($tipe_pesan === 'ok'): ?>
        <p style="margin-top: 10px;"><a href="login.php" style="color: #f1c40f; text-decoration: underline;">Klik di sini untuk Login</a></p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <label>Token Reset</label>
    <input type="text" name="token" required placeholder="Paste token dari halaman sebelumnya">
    
    <label>Password Baru</label>
    <input type="password" name="password_baru" required placeholder="Minimal 6 karakter">
    
    <label>Konfirmasi Password Baru</label>
    <input type="password" name="konfirmasi" required placeholder="Ulangi password baru">
    
    <button type="submit" class="btn" style="width: 100%; margin-top: 15px;">Reset Password</button>
  </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>