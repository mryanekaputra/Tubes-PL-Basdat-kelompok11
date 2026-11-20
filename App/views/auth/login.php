<section class="login card"><h1>Login Pengguna</h1><p>Masuk untuk mengelola program kerja dan arsip kegiatan.</p>
<?php if (!empty($error)): ?><div class="alert error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
<form method="post" action="<?= BASE_URL ?>/index.php?url=auth/authenticate">
<label>Username<input name="username" required autocomplete="username"></label>
<label>Password<input type="password" name="password" required autocomplete="current-password"></label>
<button class="btn" type="submit">Masuk</button></form></section>