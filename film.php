<?php
$base = ''; $page = 'film';
require __DIR__ . '/config.php';
$st = $pdo->prepare("SELECT * FROM films WHERE id = ?");
$st->execute([(int)($_GET['id'] ?? 0)]);
$f = $st->fetch();
if (!$f) {
    http_response_code(404);
    $title = 'Tidak ditemukan';
    require __DIR__ . '/includes/header.php';
    echo '<div class="page"><h1>Film tidak ditemukan</h1><a class="btn" href="index.php">Kembali ke Home</a></div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}
$title = $f['title'];
$cast = array_filter(array_map('trim', explode(',', (string)$f['cast_list'])));
require __DIR__ . '/includes/header.php';
?>
<div class="page">
  <div class="det">
    <div class="poster" style="background:<?= e($f['color']) ?>">
      <?php if ($f['poster_url']): ?><img src="<?= e($f['poster_url']) ?>" alt=""><?php else: ?><span><?= e(mb_strtoupper(mb_substr($f['title'], 0, 1))) ?></span><?php endif; ?>
    </div>
    <div>
      <h1><?= e($f['title']) ?></h1>
      <div class="meta"><span class="star">★ <?= e($f['rating']) ?></span><?= e($f['genre']) ?><?= $f['year'] ? ' · ' . e($f['year']) : '' ?></div>
      <h3>Sinopsis</h3>
      <p><?= nl2br(e($f['synopsis'])) ?></p>
      <h3>Pemeran</h3>
      <div class="cast"><?php foreach ($cast as $c) echo '<span>' . e($c) . '</span>'; ?></div>
    </div>
  </div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
