<?php
class DokumenController extends BaseController {
    public function index(): void {
        $this->requireLogin();
        $sql="SELECT dok.*, p.nama_proker, d.nama_divisi, p.periode FROM dokumen_kegiatan dok JOIN program_kerja p ON p.id_proker=dok.id_proker JOIN divisi d ON d.id_divisi=p.id_divisi WHERE 1=1";
        $params=[]; $filter=$_GET['q'] ?? ''; $jenis=$_GET['jenis'] ?? '';
        if($filter!=='') {$sql.=" AND (p.nama_proker LIKE ? OR dok.nama_dokumen LIKE ? OR d.nama_divisi LIKE ?)";$params=["%$filter%","%$filter%","%$filter%"];}
        if($jenis!=='') {$sql.=" AND dok.jenis_dokumen=?";$params[]=$jenis;}
        $sql.=" ORDER BY dok.id_dokumen DESC";$st=Database::getConnection()->prepare($sql);$st->execute($params);
        $this->render('dokumen/index',['title'=>'Arsip Dokumen','rows'=>$st->fetchAll(),'proker'=>(new ProgramKerja())->all()]);
    }
    public function store(): void {
        $this->requireLogin();
        $data=['id_proker'=>(int)$this->input('id_proker'),'jenis_dokumen'=>trim($this->input('jenis_dokumen')),'nama_dokumen'=>trim($this->input('nama_dokumen')),'tanggal_dokumen'=>$this->input('tanggal_dokumen') ?: null,'status_dokumen'=>$this->input('status_dokumen'),'tautan_drive'=>trim($this->input('tautan_drive'))];
        $v=(new Validator())->required($data,['id_proker','jenis_dokumen','nama_dokumen','tautan_drive'])->inList($data,'status_dokumen',['Draft','Final']);
        if (!filter_var($data['tautan_drive'], FILTER_VALIDATE_URL) || !preg_match('~^https?://~i',$data['tautan_drive'])) $_SESSION['flash']='Tautan Drive harus berupa URL yang valid.';
        elseif (!$v->valid()) $_SESSION['flash']=implode(' ',$v->errors());
        else {try{(new DokumenKegiatan())->create($data);$_SESSION['flash']='Dokumen ditambahkan.';}catch(Throwable $e){$_SESSION['flash']='Gagal menyimpan dokumen.';}}
        $this->redirect('dokumen/index');
    }
    public function delete(): void {$this->requireLogin();(new DokumenKegiatan())->delete((int)$this->input('id'));$_SESSION['flash']='Dokumen dihapus.';$this->redirect('dokumen/index');}
}
