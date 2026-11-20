<h1>Dashboard</h1><p>Ringkasan kondisi program kerja dan arsip Departemen Dalam Negeri.</p>
<div class="stats">
<div class="stat"><span>Program kerja belum selesai</span><strong><?= (int)$summary['proker_belum'] ?></strong></div>
<div class="stat"><span>Dokumen berstatus Draft</span><strong><?= (int)$summary['dokumen_draft'] ?></strong></div>
<div class="stat"><span>Rapat tanpa keputusan</span><strong><?= (int)$summary['rapat_kosong'] ?></strong></div>
<div class="stat"><span>Total program kerja</span><strong><?= (int)$summary['total_proker'] ?></strong></div></div>
<h2>Ringkasan Kelengkapan Dokumen</h2><div class="table-wrap"><table><thead><tr><th>Program Kerja</th><th>Divisi</th><th>Total Dokumen</th><th>Final</th><th>Draft</th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= htmlspecialchars($r['nama_proker']) ?></td><td><?= htmlspecialchars($r['nama_divisi']) ?></td><td><?= (int)$r['total_dokumen'] ?></td><td><?= (int)$r['dokumen_final'] ?></td><td><?= (int)$r['dokumen_draft'] ?></td></tr><?php endforeach; ?>
<?php if(!$rows): ?><tr><td colspan="5">Belum ada data program kerja.</td></tr><?php endif; ?></tbody></table></div>