<?php
require __DIR__ . '/../config.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
csrf_check();
$st = $pdo->prepare("DELETE FROM films WHERE id = ?");
$st->execute([(int)($_POST['id'] ?? 0)]);
flash('Film berhasil dihapus.');
header('Location: index.php');
exit;
