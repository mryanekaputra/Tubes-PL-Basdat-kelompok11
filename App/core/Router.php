<?php
class Router {
    public function dispatch(): void {
        $url = trim($_GET['url'] ?? 'dashboard/index', '/');
        $parts = explode('/', $url);
        $controllerName = ucfirst($parts[0] ?: 'dashboard') . 'Controller';
        $method = $parts[1] ?? 'index';
        $file = __DIR__ . '/../controllers/' . $controllerName . '.php';
        if (!is_file($file)) { http_response_code(404); exit('Halaman tidak ditemukan.'); }
        require_once $file;
        if (!class_exists($controllerName) || !method_exists($controllerName, $method) || str_starts_with($method, '_')) {
            http_response_code(404); exit('Aksi tidak ditemukan.');
        }
        (new $controllerName())->$method();
    }
}
