<?php
$base = ''; $page = 'home'; $title = 'Home';
require __DIR__ . '/config.php';

$all    = $pdo->query("SELECT * FROM films ORDER BY id")->fetchAll();
$top    = $pdo->query("SELECT * FROM films ORDER BY rating DESC LIMIT 6")->fetchAll();
$newest = $pdo->query("SELECT * FROM films ORDER BY created_at DESC, id DESC LIMIT 6")->fetchAll();
$hero   = $top[0] ?? null;
$flash  = flash();

require __DIR__ . '/includes/header.php';
?>
<?php if ($flash): ?><div class="alert <?= e($flash['type']) ?> toast"><?= e($flash['msg']) ?></div><?php endif; ?>
<?php if ($hero): ?>
<div class="hero" style="--hc:<?= e($hero['color']) ?>">
  <div>
    <h1><?= e($hero['title']) ?></h1>
    <p><span class="star">★ <?= e($hero['rating']) ?></span> · <?= e($hero['genre']) ?></p>
    <p><?= e($hero['synopsis']) ?></p>
    <a class="btn" href="film.php?id=<?= (int)$hero['id'] ?>">Lihat detail</a>
    <a class="btn g" href="rating.php">Lihat rating</a>
  </div>
</div>
<?php else: ?>
<div class="page"><h1>Belum ada film</h1><p class="mu">Login sebagai admin untuk menambahkan film.</p></div>
<?php endif; ?>

<?php foreach ([['Film pilihan', $all], ['Rating tertinggi', $top], ['Baru ditambahkan', $newest]] as [$label, $rows]): ?>
  <?php if ($rows): ?>
  <div class="rowt">
    <h2><?= e($label) ?></h2>
    <div class="sc"><?php foreach ($rows as $f) echo tile($f); ?></div>
  </div>
  <?php endif; ?>
<?php endforeach; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
