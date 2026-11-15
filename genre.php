<?php
$base = ''; $page = 'genre'; $title = 'Genre';
require __DIR__ . '/config.php';
$genres = $pdo->query("SELECT DISTINCT genre FROM films ORDER BY genre")->fetchAll(PDO::FETCH_COLUMN);
$sel = $_GET['g'] ?? '';
if ($sel !== '') {
    $st = $pdo->prepare("SELECT * FROM films WHERE genre = ? ORDER BY title");
    $st->execute([$sel]);
    $films = $st->fetchAll();
} else {
    $films = $pdo->query("SELECT * FROM films ORDER BY title")->fetchAll();
}
require __DIR__ . '/includes/header.php';
?>
<div class="page">
  <h1>Genre</h1>
  <div class="chips">
    <a class="chip <?= $sel === '' ? 'on' : '' ?>" href="genre.php">Semua</a>
    <?php foreach ($genres as $g): ?>
      <a class="chip <?= $sel === $g ? 'on' : '' ?>" href="genre.php?g=<?= urlencode($g) ?>"><?= e($g) ?></a>
    <?php endforeach; ?>
  </div>
  <?php if (!$films): ?><p class="mu">Tidak ada film di genre ini.</p><?php endif; ?>
  <div class="gr"><?php foreach ($films as $f) echo tile($f); ?></div>
</div>
<?php require __DIR__ . '/includes/footer.php'; ?>
