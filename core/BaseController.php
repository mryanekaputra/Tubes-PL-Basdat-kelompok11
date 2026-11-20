<?php
class BaseController {
    protected function render(string $view, array $data = []): void {
        extract($data);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }
    protected function redirect(string $path): never {
        header('Location: ' . BASE_URL . '/index.php?url=' . urlencode($path)); exit;
    }
    protected function requireLogin(): void {
        if (empty($_SESSION['user'])) $this->redirect('auth/login');
    }
    protected function input(string $key, mixed $default = ''): mixed {
        return $_POST[$key] ?? $default;
    }
    protected function e(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
}
