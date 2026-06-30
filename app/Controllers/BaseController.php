<?php

namespace App\Controllers;

class BaseController {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . url('/admin/login'));
            exit;
        }
    }

    protected function isAjax() {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    protected function json($data, $code = 200) {
        http_response_code($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($path, $params = []) {
        $query = http_build_query($params);
        $url = url($path);
        if ($query) $url .= '?' . $query;
        header('Location: ' . $url);
        exit;
    }
}
