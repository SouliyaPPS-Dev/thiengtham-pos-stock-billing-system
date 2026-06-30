<?php

namespace App\Controllers;

class ServiceWorkerController {
    public function serve() {
        $cssPath = __DIR__ . '/../../public/css/app.css';
        $swPath = __DIR__ . '/../../public/sw.js';

        $version = file_exists($cssPath) ? filemtime($cssPath) : time();

        header('Content-Type: application/javascript');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        $content = file_get_contents($swPath);
        $content = preg_replace(
            '/const CACHE_NAME = "miss-clean-[^"]*"/',
            'const CACHE_NAME = "miss-clean-' . $version . '"',
            $content
        );

        echo $content;
        exit;
    }
}
