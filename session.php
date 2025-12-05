<?php
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

if (!function_exists('is_admin')) {
    function is_admin(): bool {
        return isset($_SESSION['usuario']['perfil']) && $_SESSION['usuario']['perfil'] === 'admin';
    }
}

if (!function_exists('require_auth')) {
    function require_auth(string $required_profile = 'usuario'): bool {
        if (!isset($_SESSION['usuario'])) {
            // fallback se REQUEST_URI não existir
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? '/';
            header('Location: login.php');
            exit;
        }

        if ($required_profile === 'admin' && !is_admin()) {
            if (function_exists('set_flash')) {
                set_flash('Acesso Negado', 'Permissão insuficiente.');
            }
            header('Location: home.php');
            exit;
        }

        return true;
    }
}

if (!function_exists('current_user')) {
    function current_user(): ?array {
        return $_SESSION['usuario'] ?? null;
    }
}
?>