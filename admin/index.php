<?php
$base = '../'; $page = 'admin'; $title = 'Kelola Film';
require __DIR__ . '/../config.php';
require_admin();

$q = trim($_GET['q'] ?? '');
if ($q !== '') {
    $st = $pdo->prepare("SELECT * FROM films WHERE title LIKE ? OR genre LIKE ? ORDER BY id DESC");
    $st->execute(["%$q%", "%$q%"]);
    $films = $st->fetchAll();
} else {
    $films = $pdo->query("SELECT * FROM films ORDER BY id DESC")->fetchAll();
}
$f = flash();
require __DIR__ . '/../includes/header.php';
?>
<div class="page wide">
  <div class="bar">
    <h1>Kelola Film</h1>
    <a class="btn" href="form.php">+ Tambah film</a>
  </div>
  <?php if ($f): ?><div class="alert <?= e($f['type']) ?>"><?= e($f['msg']) ?></div><?php endif; ?>
  <form class="search" method="get">
    <input type="search" name="q" placeholder="Cari judul atau genre…" value="<?= e($q) ?>">
    <button class="btn g" type="submit">Cari</button>
  </form>
  <div class="tw">
  <table>
    <thead><tr><th>#</th><th>Judul</th><th>Genre</th><th>Rating</th><th>Tahun</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach ($films as $r): ?>
      <tr>
        <td><?= (int)$r['id'] ?></td>
        <td><?= e($r['title']) ?></td>
        <td><?= e($r['genre']) ?></td>
        <td>★ <?= e($r['rating']) ?></td>
        <td><?= e($r['year']) ?></td>
        <td class="act">
          <a class="sm" href="../film.php?id=<?= (int)$r['id'] ?>">Lihat</a>
          <a class="sm" href="form.php?id=<?= (int)$r['id'] ?>">Edit</a>
          <form method="post" action="delete.php" onsubmit="return confirm('Hapus film ini?')">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
            <button class="sm danger" type="submit">Hapus</button>
          </form>
        </td>
      </tr>
    <?php endforeach; ?>
    <?php if (!$films): ?><tr><td colspan="6" class="mu">Tidak ada data.</td></tr><?php endif; ?>
    </tbody>
  </table>
  </div>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
