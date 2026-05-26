<?php

namespace App\Controller;

abstract class BaseController
{
    protected function render(string $view, array $data = []): void
    {
        $file = BASE_PATH . '/view/' . $view . '.php';

        if (!file_exists($file)) {
            throw new \Exception("View not found: $view");
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    protected function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }

    protected function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }



    protected function post(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST';
    }

    protected function requireLogin(): void
    {
        if (!$this->session('user_id')) {
            $this->redirect('?page=login');
        }
    }

    protected function isLoggedIn(): bool
    {
        return !empty($this->session('user_id'));
    }

    /* =========================
     * SESSION (FIXED)
     * ========================= */

    protected function session(string $key, $value = null)
    {
        if ($value === null) {
            return $_SESSION[$key] ?? null;
        }

        $_SESSION[$key] = $value;
    }

    protected function destroySession(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }
}
