<?php
class ProkerController extends BaseController {
    public function index(): void {
        $this->requireLogin();
        $sql="SELECT p.*, d.nama_divisi FROM program_kerja p JOIN divisi d ON d.id_divisi=p.id_divisi ORDER BY p.id_proker DESC";
        $rows=Database::getConnection()->query($sql)->fetchAll();
        $this->render('proker/index',['title'=>'Program Kerja','rows'=>$rows,'divisi'=>(new Divisi())->all(),'pengurus'=>(new Pengurus())->all()]);
    }
    public function store(): void {
        $this->requireLogin();
        $data=['id_divisi'=>(int)$this->input('id_divisi'),'nama_proker'=>trim($this->input('nama_proker')),'periode'=>trim($this->input('periode')),'tanggal_pelaksanaan'=>$this->input('tanggal_pelaksanaan') ?: null,'status_persiapan'=>$this->input('status_persiapan'),'total_rab'=>$this->input('total_rab','0'),'konsep_kegiatan'=>trim($this->input('konsep_kegiatan'))];
        $v=(new Validator())->required($data,['id_divisi','nama_proker','periode'])->numeric($data,['total_rab'])->inList($data,'status_persiapan',['Perencanaan','Persiapan','Pelaksanaan','Selesai']);
        if (!$v->valid()) {$_SESSION['flash']=implode(' ',$v->errors());$this->redirect('proker/index');}
        $db=Database::getConnection();$db->beginTransaction();
        try {
            $id=(new ProgramKerja())->create($data);
            $ids=$_POST['pengurus'] ?? []; $jobs=$_POST['jobdesk'] ?? [];
            foreach($ids as $i=>$pid) if ((int)$pid>0 && trim($jobs[$i] ?? '')!=='') (new DetailPengurusProker())->create(['id_pengurus'=>(int)$pid,'id_proker'=>$id,'jobdesk'=>trim($jobs[$i])]);
            $db->commit();$_SESSION['flash']='Program kerja berhasil ditambahkan.';
        } catch(Throwable $e) {$db->rollBack();$_SESSION['flash']='Gagal menyimpan program kerja. Periksa data dan penugasan.';}
        $this->redirect('proker/index');
    }
    public function delete(): void {
        $this->requireLogin();
        try {(new ProgramKerja())->delete((int)$this->input('id'));$_SESSION['flash']='Program kerja dihapus.';} catch(Throwable $e){$_SESSION['flash']='Program kerja tidak dapat dihapus.';}
        $this->redirect('proker/index');
    }
}
