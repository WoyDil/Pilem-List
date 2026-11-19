<?php
$base = ''; $page = 'pengaturan'; $title = 'Pengaturan Akun';
require __DIR__ . '/config.php';

// Pastikan user sudah login
if (!is_logged_in()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$pesan = '';
$tipe_pesan = '';

// Ambil data user saat ini
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    
    $password_lama = $_POST['password_lama'] ?? '';
    $password_baru = $_POST['password_baru'] ?? '';
    $konfirmasi    = $_POST['konfirmasi'] ?? '';

    // Validasi
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi)) {
        $pesan = 'Semua kolom harus diisi!';
        $tipe_pesan = 'err';
    } elseif (!password_verify($password_lama, $user['password'])) {
        $pesan = 'Password lama salah!';
        $tipe_pesan = 'err';
    } elseif ($password_baru !== $konfirmasi) {
        $pesan = 'Password baru dan konfirmasi tidak cocok!';
        $tipe_pesan = 'err';
    } elseif (strlen($password_baru) < 6) {
        $pesan = 'Password baru minimal 6 karakter.';
        $tipe_pesan = 'err';
    } else {
        // Update password
        $hash = password_hash($password_baru, PASSWORD_DEFAULT);
        $update = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $update->execute([$hash, $user_id]);
        
        $pesan = 'Password berhasil diubah! Silakan login ulang.';
        $tipe_pesan = 'ok';
        
        // Hancurkan session agar user login ulang dengan password baru
        session_destroy();
        header('Refresh: 2; URL=login.php');
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="page" style="max-width: 600px; margin: 120px auto;">
  <h1 style="font-family: 'Cinzel', serif; color: #f1c40f; margin-bottom: 30px;">Pengaturan Akun</h1>
  
  <?php if ($pesan): ?>
    <div class="alert <?= $tipe_pesan ?>" style="margin-bottom: 20px;">
      <?= e($pesan) ?>
    </div>
  <?php endif; ?>

  <div class="box" style="max-width: 100%; margin: 0; background: var(--sf); padding: 30px; border-radius: 8px; border: 1px solid var(--ln);">
    
    <h3 style="margin-top: 0; color: var(--tx);">Informasi Akun</h3>
    <p style="color: var(--mu);">Username: <strong style="color: var(--tx);"><?= e($user['username']) ?></strong></p>
    <p style="color: var(--mu);">Role: <strong style="color: var(--tx);"><?= e(ucfirst($user['role'])) ?></strong></p>
    
    <hr style="border: 0; border-top: 1px solid var(--ln); margin: 20px 0;">

    <h3 style="color: var(--tx);">Ganti Password</h3>
    <form method="POST">
      <?= csrf_field() ?>
      
      <label>Password Lama</label>
      <input type="password" name="password_lama" required placeholder="Masukkan password saat ini">
      
      <label>Password Baru</label>
      <input type="password" name="password_baru" required placeholder="Minimal 6 karakter">
      
      <label>Konfirmasi Password Baru</label>
      <input type="password" name="konfirmasi" required placeholder="Ulangi password baru">
      
      <button type="submit" class="btn" style="margin-top: 15px; width: 100%;">Simpan Password Baru</button>
    </form>
    
    <hr style="border: 0; border-top: 1px solid var(--ln); margin: 30px 0;">

    <a href="logout.php" class="btn" style="background-color: #ef4444 !important; color: #fff !important; width: 100%; text-align: center;">Logout dari Akun</a>

  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>