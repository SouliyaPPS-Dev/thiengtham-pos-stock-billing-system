<?php

namespace App\Controllers;

class BaseController {
    public function __construct() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            // Simple redirect using url() helper
            header('Location: ' . url('/login'));
            exit;
        }
    }
}
