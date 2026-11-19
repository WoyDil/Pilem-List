<?php
require_once __DIR__ . '/../config.php';
$base  = $base ?? '';
$page  = $page ?? '';
$title = $title ?? 'PILEM LIST';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> – PILEM LIST</title>
<link rel="stylesheet" href="<?= $base ?>assets/style.css">
</head>
<body>
<header>
<a class="logo" href="<?= $base ?>index.php">
  <img src="<?= $base ?>assets/logo.png" alt="PILEM LIST" style="height: 45px; width: auto;">
</a>
  <nav class="tnav" aria-label="Menu utama">
    <a href="<?= $base ?>index.php" class="<?= $page === 'home' ? 'on' : '' ?>">Home</a>
    <a href="<?= $base ?>rating.php" class="<?= $page === 'rating' ? 'on' : '' ?>">Rating</a>
    <a href="<?= $base ?>genre.php" class="<?= $page === 'genre' ? 'on' : '' ?>">Genre</a>
  </nav>
    <div class="spacer"></div>

  <!-- --- MULAI DROPDOWN PROFIL --- -->
  <?php if (is_logged_in()): ?>
    <div class="profile-dropdown">
      <button class="profile-btn" id="profileBtn">
        <!-- Ikon User (SVG) -->
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
        <span><?= e($_SESSION['username'] ?? 'User') ?></span>
        <svg class="chevron" viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
      </button>
      
      <div class="dropdown-content" id="profileDropdown">
        <?php if (is_admin()): ?>
          <a href="<?= $base ?>admin/index.php">Kelola Film (Admin)</a>
        <?php endif; ?>
          <a href="<?= $base ?>pengaturan.php">Pengaturan Akun</a>
        <a href="#">Daftar Tontonan</a>
         <a href="#" id="themeToggle">
          <span id="themeIcon">🌙</span> <span id="themeText">Mode Gelap</span>
        </a>
    </div>
  <?php else: ?>
    <!-- Kalau Belum Login, Tampilkan Tombol Login Biasa -->
    <a class="tbtn" href="<?= $base ?>login.php">Login</a>
  <?php endif; ?>
  <!-- --- SELESAI DROPDOWN PROFIL --- -->

</header>
<main>
