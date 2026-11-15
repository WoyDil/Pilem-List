<?php
$base = '../'; $page = 'admin';
require __DIR__ . '/../config.php';
require_admin();

$id = (int)($_GET['id'] ?? $_POST['id'] ?? 0);
$data = ['title'=>'','genre'=>'','rating'=>'','year'=>'','synopsis'=>'','cast_list'=>'','color'=>'#9b1c2c','poster_url'=>''];

if ($id) {
    $st = $pdo->prepare("SELECT * FROM films WHERE id = ?");
    $st->execute([$id]);
    $row = $st->fetch();
    if (!$row) { flash('Film tidak ditemukan.', 'err'); header('Location: index.php'); exit; }
    $data = array_merge($data, array_map(fn($v) => $v ?? '', $row));
}
$title = $id ? 'Edit Film' : 'Tambah Film';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    foreach (['title','genre','rating','year','synopsis','cast_list','color','poster_url'] as $k) {
        $data[$k] = trim($_POST[$k] ?? '');
    }
    if ($data['title'] === '') $errors[] = 'Judul wajib diisi.';
    if ($data['genre'] === '') $errors[] = 'Genre wajib diisi.';
    if (!is_numeric($data['rating']) || $data['rating'] < 0 || $data['rating'] > 10) $errors[] = 'Rating harus angka 0 sampai 10.';
    if ($data['year'] !== '' && (!ctype_digit($data['year']) || $data['year'] < 1888 || $data['year'] > 2100)) $errors[] = 'Tahun tidak valid.';
    if (!preg_match('/^#[0-9a-fA-F]{6}$/', $data['color'])) $errors[] = 'Warna harus format #RRGGBB.';
    if ($data['poster_url'] !== '' && !preg_match('#^https?://#i', $data['poster_url'])) $errors[] = 'URL poster harus diawali http:// atau https://';

    if (!$errors) {
        $vals = [$data['title'], $data['genre'], (float)$data['rating'], $data['year'] === '' ? null : (int)$data['year'],
                 $data['synopsis'], $data['cast_list'], $data['color'], $data['poster_url'] ?: null];
        if ($id) {
            $st = $pdo->prepare("UPDATE films SET title=?, genre=?, rating=?, year=?, synopsis=?, cast_list=?, color=?, poster_url=? WHERE id=?");
            $st->execute(array_merge($vals, [$id]));
            flash('Film berhasil diperbarui.');
        } else {
            $st = $pdo->prepare("INSERT INTO films (title, genre, rating, year, synopsis, cast_list, color, poster_url) VALUES (?,?,?,?,?,?,?,?)");
            $st->execute($vals);
            flash('Film berhasil ditambahkan.');
        }
        header('Location: index.php');
        exit;
    }
}
require __DIR__ . '/../includes/header.php';
?>
<div class="page wide">
  <h1><?= e($title) ?></h1>
  <?php foreach ($errors as $er): ?><div class="alert err"><?= e($er) ?></div><?php endforeach; ?>
  <form method="post" class="frm">
    <?= csrf_field() ?>
    <input type="hidden" name="id" value="<?= $id ?>">
    <label>Judul<input type="text" name="title" value="<?= e($data['title']) ?>" required></label>
    <div class="two">
      <label>Genre<input type="text" name="genre" value="<?= e($data['genre']) ?>" required></label>
      <label>Tahun<input type="number" name="year" value="<?= e($data['year']) ?>" min="1888" max="2100"></label>
    </div>
    <div class="two">
      <label>Rating (0–10)<input type="number" name="rating" step="0.1" min="0" max="10" value="<?= e($data['rating']) ?>" required></label>
      <label>Warna poster<input type="color" name="color" value="<?= e($data['color']) ?>"></label>
    </div>
    <label>URL gambar poster (opsional)<input type="url" name="poster_url" value="<?= e($data['poster_url']) ?>" placeholder="https://…"></label>
    <label>Sinopsis<textarea name="synopsis" rows="5"><?= e($data['synopsis']) ?></textarea></label>
    <label>Pemeran (pisahkan dengan koma)<input type="text" name="cast_list" value="<?= e($data['cast_list']) ?>" placeholder="Nama A, Nama B, Nama C"></label>
    <div>
      <button class="btn" type="submit">Simpan</button>
      <a class="btn g" href="index.php">Batal</a>
    </div>
  </form>
</div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
