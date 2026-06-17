<?php

namespace App\Controllers;

class BaseController {
    public function __construct() {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
