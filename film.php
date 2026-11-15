<?php
$base = ''; $page = 'film';
require __DIR__ . '/config.php';

// Ambil ID film dari URL
$st = $pdo->prepare("SELECT * FROM films WHERE id = ?");
$st->execute([(int)($_GET['id'] ?? 0)]);
$f = $st->fetch();

// Kalau film nggak ada
if (!$f) {
    http_response_code(404);
    $title = 'Tidak ditemukan';
    require __DIR__ . '/includes/header.php';
    echo '<div class="page" style="text-align:center; padding-top:150px;">
            <h1 style="color:#f1c40f; font-family:\'Cinzel\', serif;">Film tidak ditemukan</h1>
            <a class="btn" href="index.php" style="margin-top:20px;">Kembali ke Home</a>
          </div>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$title = $f['title'];
// Pecah string pemeran menjadi array
$cast = array_filter(array_map('trim', explode(',', (string)$f['cast_list'])));

require __DIR__ . '/includes/header.php';
?>

<!-- Container utama tanpa pembungkus .page agar full width -->
<div class="det-container">
  <div class="det">
    
    <!-- KOLOM KIRI: POSTER (Pakai Bingkai Emas) -->
    <div class="poster">
      <?php if (!empty($f['poster_url'])): ?>
        <img src="<?= e($f['poster_url']) ?>" alt="<?= e($f['title']) ?>">
      <?php else: ?>
        <!-- Huruf inisial jika belum ada gambar -->
        <span style="font-size: 5rem; font-family: 'Cinzel', serif; color: rgba(255,255,255,0.4);">
          <?= e(mb_strtoupper(mb_substr($f['title'], 0, 1))) ?>
        </span>
      <?php endif; ?>
    </div>

    <!-- KOLOM KANAN: INFO FILM -->
    <div class="det-info">
      <h1><?= e($f['title']) ?></h1>
      
      <div class="meta">
        <span class="star">★ <?= e($f['rating']) ?></span>
        <span>·</span>
        <span><?= e($f['genre']) ?></span>
        <?php if ($f['year']): ?>
          <span>·</span>
          <span><?= e($f['year']) ?></span>
        <?php endif; ?>
      </div>

      <h3>Sinopsis</h3>
      <p><?= nl2br(e($f['synopsis'])) ?></p>

      <h3>Pemeran</h3>
      <div class="cast">
        <?php if (empty($cast)): ?>
          <span>Belum ada data pemeran</span>
        <?php else: ?>
          <?php foreach ($cast as $c): ?>
            <span><?= e($c) ?></span>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
      
      <div style="margin-top: 40px;">
        <a class="btn" href="index.php">← Kembali ke Home</a>
      </div>
    </div>

  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>