<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../helpers/view.php';

requireLogin();

$db = getDbConnection();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');

    if ($fullName === '' || $phone === '' || $email === '' || $address === '') {
        $error = 'All customer fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } else {
        $stmt = $db->prepare('INSERT INTO customers (full_name, phone, email, address) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $fullName, $phone, $email, $address);

        if ($stmt->execute()) {
            $message = 'Customer registered successfully.';
        } else {
            $error = 'Failed to register customer. Ensure email is unique.';
        }

        $stmt->close();
    }
}

$customersResult = $db->query('SELECT id, full_name, phone, email, address, created_at FROM customers ORDER BY id DESC');

render('customers', [
    'message' => $message,
    'error' => $error,
    'customersResult' => $customersResult,
], true);

$db->close();
