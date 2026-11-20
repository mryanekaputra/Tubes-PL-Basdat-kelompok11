<?php
class DivisiController extends BaseController {
    public function index(): void { $this->requireLogin(); $this->render('divisi/index',['title'=>'Data Divisi','rows'=>(new Divisi())->all()]); }
    public function store(): void {
        $this->requireLogin(); $data=['nama_divisi'=>trim($this->input('nama_divisi')),'fokus_kegiatan'=>trim($this->input('fokus_kegiatan'))];
        $v=(new Validator())->required($data,['nama_divisi']);
        if (!$v->valid()) { $_SESSION['flash']=implode(' ',$v->errors()); $this->redirect('divisi/index'); }
        try {(new Divisi())->create($data); $_SESSION['flash']='Divisi berhasil ditambahkan.';} catch(Throwable $e){$_SESSION['flash']='Gagal menyimpan. Nama divisi mungkin sudah digunakan.';}
        $this->redirect('divisi/index');
    }
    public function delete(): void {
        $this->requireLogin();
        try {(new Divisi())->delete((int)$this->input('id')); $_SESSION['flash']='Divisi dihapus.';} catch(Throwable $e){$_SESSION['flash']='Divisi tidak dapat dihapus karena masih digunakan.';}
        $this->redirect('divisi/index');
    }
}
