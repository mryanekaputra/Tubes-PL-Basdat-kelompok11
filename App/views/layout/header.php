<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars(($title ?? APP_NAME).' | '.APP_NAME) ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css"></head><body>
<header class="topbar"><a class="brand" href="<?= BASE_URL ?>/index.php?url=dashboard/index"><?= APP_NAME ?></a>
<?php if (!empty($_SESSION['user'])): ?><div class="user"> <?= htmlspecialchars($_SESSION['user']['nama']) ?> · Akun Bersama Dagri <a href="<?= BASE_URL ?>/index.php?url=auth/logout">Logout</a></div><?php endif; ?></header>
<?php if (!empty($_SESSION['user'])): ?><nav class="nav">
<a href="<?= BASE_URL ?>/index.php?url=dashboard/index">Dashboard</a>
<a href="<?= BASE_URL ?>/index.php?url=proker/index">Program Kerja</a>
<a href="<?= BASE_URL ?>/index.php?url=dokumen/index">Arsip Dokumen</a>
<a href="<?= BASE_URL ?>/index.php?url=rapat/index">Rapat</a>
<a href="<?= BASE_URL ?>/index.php?url=divisi/index">Divisi</a><a href="<?= BASE_URL ?>/index.php?url=pengurus/index">Pengurus</a>
</nav><?php endif; ?><main class="container">
<?php if (!empty($_SESSION['flash'])): ?><div class="alert"><?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?></div><?php endif; ?>
