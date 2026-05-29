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

    protected function processForm(
        object $request,
        string $view,
        callable $serviceAction,
        callable $onSuccess
    ): void {

        /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

        if (!$request->validate()) {

            $this->render($view, [
                'message' => 'Validation failed',
                'errors' => $request->errors()
            ]);

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | VALIDATED DATA
    |--------------------------------------------------------------------------
    */

        $data = $request->validated();

        /*
    |--------------------------------------------------------------------------
    | SERVICE CALL
    |--------------------------------------------------------------------------
    */

        $response = $serviceAction($data);

        /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

        if ($response->success) {

            $onSuccess($response);

            return;
        }

        /*
    |--------------------------------------------------------------------------
    | FAILURE
    |--------------------------------------------------------------------------
    */

        $this->render($view, [
            'message' => $response->message,
            'errors' => []
        ]);
    }

    //      protected function handleServiceResult(
    //         array $result,
    //         callable $onSuccess,
    //         string $errorView,
    //         array $errorData = []
    //     ): void {
    //         if (!empty($result['success'])) {
    //             $onSuccess($result);
    //             return;
    //         }

    //         $this->render($errorView, array_merge([
    //             'message' => $result['message'] ?? 'Something went wrong',
    //         ], $errorData));
    //     }

    //     protected function handleRequest(
    //     object $request,
    //     callable $onSuccess,
    //     string $view
    // ): void {

    //     if (!$request->validate()) {
    //         $this->render($view, [
    //             'message' => 'Validation failed',
    //             'errors' => $request->errors()
    //         ]);
    //         return;
    //     }
    // // var_dump($request->validated());
    // // exit;
    //     $onSuccess($request->validated());
    //     // echo ($onSuccess($request->validated()));
    //     // exit;
    // }



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
