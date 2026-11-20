<?php
class RapatController extends BaseController {
    public function index(): void {
        $this->requireLogin();
        $sql="SELECT r.*, p.nama_proker FROM rapat_koordinasi r LEFT JOIN program_kerja p ON p.id_proker=r.id_proker ORDER BY r.tanggal_rapat DESC";
        $this->render('rapat/index',['title'=>'Rapat Koordinasi','rows'=>Database::getConnection()->query($sql)->fetchAll(),'proker'=>(new ProgramKerja())->all()]);
    }
    public function store(): void {
        $this->requireLogin();
        $data=['id_proker'=>$this->input('id_proker') ?: null,'tanggal_rapat'=>$this->input('tanggal_rapat'),'agenda'=>trim($this->input('agenda')),'media_rapat'=>trim($this->input('media_rapat')),'hasil_keputusan'=>trim($this->input('hasil_keputusan'))];
        $v=(new Validator())->required($data,['tanggal_rapat','agenda']);
        if(!$v->valid()) $_SESSION['flash']=implode(' ',$v->errors());
        else {try{(new RapatKoordinasi())->create($data);$_SESSION['flash']='Rapat ditambahkan.';}catch(Throwable $e){$_SESSION['flash']='Gagal menyimpan rapat.';}}
        $this->redirect('rapat/index');
    }
    public function delete(): void {$this->requireLogin();(new RapatKoordinasi())->delete((int)$this->input('id'));$_SESSION['flash']='Rapat dihapus.';$this->redirect('rapat/index');}
}
