<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../helpers/view.php';

if (isLoggedIn()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } elseif (!loginAdmin($username, $password)) {
        $error = 'Invalid login credentials.';
    } else {
        header('Location: /dashboard.php');
        exit;
    }
}

render('login', ['error' => $error]);
