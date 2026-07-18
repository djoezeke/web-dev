<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../helpers/view.php';

requireLogin();

$db = getDbConnection();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registrationNumber = strtoupper(trim($_POST['registration_number'] ?? ''));
    $make = trim($_POST['make'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $manufactureYear = (int) ($_POST['manufacture_year'] ?? 0);
    $customerId = (int) ($_POST['customer_id'] ?? 0);

    $currentYear = (int) date('Y');

    if ($registrationNumber === '' || $make === '' || $model === '' || $manufactureYear === 0 || $customerId === 0) {
        $error = 'All vehicle fields are required.';
    } elseif ($manufactureYear < 1950 || $manufactureYear > $currentYear + 1) {
        $error = 'Invalid manufacture year.';
    } else {
        $stmt = $db->prepare('INSERT INTO vehicles (registration_number, make, model, manufacture_year, customer_id) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssii', $registrationNumber, $make, $model, $manufactureYear, $customerId);

        if ($stmt->execute()) {
            $message = 'Vehicle registered successfully.';
        } else {
            $error = 'Failed to register vehicle. Check registration number uniqueness.';
        }

        $stmt->close();
    }
}

$customersResult = $db->query('SELECT id, full_name FROM customers ORDER BY full_name');
$vehiclesResult = $db->query('SELECT v.id, v.registration_number, v.make, v.model, v.manufacture_year, c.full_name AS owner_name
                              FROM vehicles v
                              INNER JOIN customers c ON c.id = v.customer_id
                              ORDER BY v.id DESC');

render('vehicles', [
    'message' => $message,
    'error' => $error,
    'customersResult' => $customersResult,
    'vehiclesResult' => $vehiclesResult,
], true);

$db->close();
