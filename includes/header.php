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
  <button class="burger" id="bb" aria-label="Buka menu" aria-expanded="false"><i></i><i></i><i></i></button>
<a class="logo" href="<?= $base ?>index.php">
  <img src="<?= $base ?>assets/logo.png" alt="PILEM LIST" style="height: 45px; width: auto;">
</a>
  <nav class="tnav" aria-label="Menu utama">
    <a href="<?= $base ?>index.php" class="<?= $page === 'home' ? 'on' : '' ?>">Home</a>
    <a href="<?= $base ?>rating.php" class="<?= $page === 'rating' ? 'on' : '' ?>">Rating</a>
    <a href="<?= $base ?>genre.php" class="<?= $page === 'genre' ? 'on' : '' ?>">Genre</a>
  </nav>
  <div class="spacer"></div>
   <form class="search-form" action="<?= $base ?>search.php" method="GET">
    <input type="text" name="q" placeholder="Cari film..." value="<?= isset($_GET['q']) ? e($_GET['q']) : '' ?>">
    <button type="submit" aria-label="Cari">
      <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
    </button>
  </form>
  <?php if (is_admin()): ?>
    <a class="tbtn" href="<?= $base ?>admin/index.php">Admin</a>
  <?php elseif (is_logged_in()): ?>
    <span class="tuser">Halo, <?= e($_SESSION['username'] ?? '') ?></span>
    <a class="tbtn" href="<?= $base ?>logout.php">Logout</a>
  <?php else: ?>
    <a class="tbtn" href="<?= $base ?>login.php">Login</a>
  <?php endif; ?>
</header>
<div class="scrim" id="scrim"></div>
<aside class="drawer" id="dr" aria-label="Menu">
  <a class="logo" href="<?= $base ?>index.php">PILEM LIST</a>
  <a href="<?= $base ?>index.php" class="<?= $page === 'home' ? 'on' : '' ?>">Home</a>
  <a href="<?= $base ?>rating.php" class="<?= $page === 'rating' ? 'on' : '' ?>">Rating</a>
  <a href="<?= $base ?>genre.php" class="<?= $page === 'genre' ? 'on' : '' ?>">Genre</a>
  <hr>
  <?php if (is_logged_in()): ?>
    <?php if (is_admin()): ?>
      <a href="<?= $base ?>admin/index.php" class="<?= $page === 'admin' ? 'on' : '' ?>">Kelola Film</a>
    <?php endif; ?>
    <a href="<?= $base ?>logout.php">Logout (<?= e($_SESSION['username'] ?? '') ?>)</a>
  <?php else: ?>
    <a href="<?= $base ?>login.php" class="<?= $page === 'login' ? 'on' : '' ?>">Login</a>
    <a href="<?= $base ?>register.php" class="<?= $page === 'register' ? 'on' : '' ?>">Daftar</a>
  <?php endif; ?>
</aside>
<main>
