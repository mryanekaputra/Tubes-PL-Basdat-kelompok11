<?php
class AuthController extends BaseController {
    public function login(): void {
        if (!empty($_SESSION['user'])) $this->redirect('dashboard/index');
        $this->render('auth/login', ['title'=>'Login']);
    }
    public function authenticate(): void {
        $username = trim((string)$this->input('username'));
        $password = (string)$this->input('password');
        $stmt = Database::getConnection()->prepare("SELECT u.*, p.nama_pengurus FROM users u JOIN pengurus p ON p.id_pengurus=u.id_pengurus WHERE u.username=? LIMIT 1");
        $stmt->execute([$username]); $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['user'] = ['id_user'=>$user['id_user'],'id_pengurus'=>$user['id_pengurus'],'nama'=>$user['nama_pengurus'],'username'=>$user['username']];
            $this->redirect('dashboard/index');
        }
        $this->render('auth/login', ['title'=>'Login','error'=>'Username atau password salah.']);
    }
    public function logout(): void { $_SESSION = []; session_destroy(); $this->redirect('auth/login'); }
}
