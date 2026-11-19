<?php
$base = ''; $page = 'lupa_password'; $title = 'Lupa Password';
require __DIR__ . '/config.php';

$pesan = '';
$tipe_pesan = '';
$token_tampil = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    
    // Cek apakah username ada
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user) {
        // Buat token acak
        $token = bin2hex(random_bytes(16));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour')); // Berlaku 1 jam

        // Simpan token ke database
        $update = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = ? WHERE id = ?");
        $update->execute([$token, $expires, $user['id']]);

        $token_tampil = $token;
        $pesan = "Token reset berhasil dibuat. Salin token ini:";
        $tipe_pesan = 'ok';
    } else {
        $pesan = "Username tidak ditemukan.";
        $tipe_pesan = 'err';
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="box" style="margin-top: 120px;">
  <h1>Lupa Password</h1>
  
  <?php if ($pesan): ?>
    <div class="alert <?= $tipe_pesan ?>" style="margin-bottom: 20px;">
      <?= e($pesan) ?>
      <?php if ($token_tampil): ?>
        <div style="background: #0f172a; padding: 10px; border-radius: 4px; margin-top: 10px; text-align: center; font-family: monospace; font-size: 1.2rem; word-break: break-all;">
          <?= e($token_tampil) ?>
        </div>
        <p style="margin-top: 10px; font-size: 0.85rem;">Gunakan token ini di halaman <a href="reset_password.php" style="color: #f1c40f; text-decoration: underline;">Reset Password</a>.</p>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <label>Masukkan Username kamu</label>
    <input type="text" name="username" required placeholder="Username terdaftar">
    
    <button type="submit" class="btn" style="width: 100%; margin-top: 15px;">Buat Token Reset</button>
  </form>
  
  <p style="text-align: center; margin-top: 20px; font-size: 0.9rem;">
    <a href="login.php" class="lnk">Kembali ke Login</a>
  </p>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>