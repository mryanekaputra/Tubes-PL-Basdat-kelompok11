<?php
class PengurusController extends BaseController {
    public function index(): void {
        $this->requireLogin();
        $sql="SELECT p.*, d.nama_divisi FROM pengurus p LEFT JOIN divisi d ON d.id_divisi=p.id_divisi ORDER BY p.id_pengurus DESC";
        $rows=Database::getConnection()->query($sql)->fetchAll();
        $this->render('pengurus/index',['title'=>'Data Pengurus','rows'=>$rows,'divisi'=>(new Divisi())->all()]);
    }
    public function store(): void {
        $this->requireLogin();
        $data=['nama_pengurus'=>trim($this->input('nama_pengurus')),'nim'=>trim($this->input('nim')),'jabatan'=>trim($this->input('jabatan')),'id_divisi'=>$this->input('id_divisi') ?: null,'status_aktif'=>$this->input('status_aktif','Aktif')];
        $v=(new Validator())->required($data,['nama_pengurus','nim','jabatan'])->inList($data,'status_aktif',['Aktif','Tidak Aktif']);
        if (!$v->valid()) {$_SESSION['flash']=implode(' ',$v->errors());$this->redirect('pengurus/index');}
        try {(new Pengurus())->create($data);$_SESSION['flash']='Pengurus ditambahkan.';} catch(Throwable $e){$_SESSION['flash']='Gagal menyimpan. NIM harus unik.';}
        $this->redirect('pengurus/index');
    }
    public function delete(): void {
        $this->requireLogin();
        try {(new Pengurus())->delete((int)$this->input('id'));$_SESSION['flash']='Pengurus dihapus.';} catch(Throwable $e){$_SESSION['flash']='Pengurus tidak dapat dihapus.';}
        $this->redirect('pengurus/index');
    }
}
