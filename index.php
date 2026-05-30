<?php
session_start();
require __DIR__ . '/config/app.php';
require __DIR__ . '/config/database.php';
require __DIR__ . '/app/core/Database.php';
require __DIR__ . '/app/core/Model.php';
require __DIR__ . '/app/core/Controller.php';
require __DIR__ . '/app/core/Auth.php';

spl_autoload_register(function ($class) {
    $paths = [__DIR__ . '/app/controllers', __DIR__ . '/app/models', __DIR__ . '/app/core'];
    foreach ($paths as $path) {
        $file = $path . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require __DIR__ . '/routes/web.php';
