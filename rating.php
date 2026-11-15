<?php
$base = ''; $page = 'rating'; $title = 'Rating';
require __DIR__ . '/config.php';
$films = $pdo->query("SELECT * FROM films ORDER BY rating DESC, title")->fetchAll();
require __DIR__ . '/includes/header.php';
?>
<div class="page">
  <h1>Rating</h1>
  <?php if (!$films): ?><p class="mu">Belum ada film.</p><?php endif; ?>
  <?php foreach ($films as $k => $f): ?>
    <a class="rk" href="film.php?id=<?= (int)$f['id'] ?>">
      <span class="n"><?= $k + 1 ?></span>
      <span class="t"><?= e($f['title']) ?><small><?= e($f['genre']) ?></small></span>
      <span class="star">★ <?= e($f['rating']) ?></span>
    </a>
  <?php endforeach; ?>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
