<?php
// File: tests/bootstrap.php

// 1) Autoload everything
require __DIR__ . '/../vendor/autoload.php';

// 2) Load your real helpers (defines basePath, redirect, loadView, etc.)
require __DIR__ . '/../helpers.php';

// 3) Override the global helper functions so they don't actually exit or pull in views:

// a) redirect() → throw an exception instead of exit
if (!function_exists('redirect')) {
    function redirect(string $url): void
    {
        throw new \RuntimeException("Redirect to {$url}");
    }
}

// b) loadView() → echo JSON instead of requiring a .php template
if (!function_exists('loadView')) {
    function loadView(string $name, array $data = []): void
    {
        echo json_encode([
            'view' => $name,
            'data' => $data,
        ]);
    }
}
