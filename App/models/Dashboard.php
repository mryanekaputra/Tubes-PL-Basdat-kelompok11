<?php
class Dashboard {
    private PDO $db;
    public function __construct() { $this->db = Database::getConnection(); }
    public function summary(): array {
        return [
            'proker_belum' => (int)$this->db->query("SELECT COUNT(*) FROM program_kerja WHERE status_persiapan <> 'Selesai'")->fetchColumn(),
            'dokumen_draft' => (int)$this->db->query("SELECT COUNT(*) FROM dokumen_kegiatan WHERE status_dokumen = 'Draft'")->fetchColumn(),
            'rapat_kosong' => (int)$this->db->query("SELECT COUNT(*) FROM rapat_koordinasi WHERE hasil_keputusan IS NULL OR hasil_keputusan = ''")->fetchColumn(),
            'total_proker' => (int)$this->db->query("SELECT COUNT(*) FROM program_kerja")->fetchColumn()
        ];
    }
    public function completeness(): array {
        return $this->db->query("
          SELECT p.id_proker, p.nama_proker, d.nama_divisi,
          COUNT(dok.id_dokumen) total_dokumen,
          SUM(CASE WHEN dok.status_dokumen='Final' THEN 1 ELSE 0 END) dokumen_final,
          SUM(CASE WHEN dok.status_dokumen='Draft' THEN 1 ELSE 0 END) dokumen_draft
          FROM program_kerja p JOIN divisi d ON d.id_divisi=p.id_divisi
          LEFT JOIN dokumen_kegiatan dok ON dok.id_proker=p.id_proker
          GROUP BY p.id_proker ORDER BY p.id_proker DESC
        ")->fetchAll();
    }
}
