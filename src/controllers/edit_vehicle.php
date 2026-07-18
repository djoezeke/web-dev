<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../helpers/view.php';

requireLogin();

$db = getDbConnection();
$vehicleId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);

if ($vehicleId <= 0) {
    header('Location: /vehicles.php');
    exit;
}

$error = '';
$message = '';

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
        $stmt = $db->prepare('UPDATE vehicles SET registration_number = ?, make = ?, model = ?, manufacture_year = ?, customer_id = ? WHERE id = ?');
        $stmt->bind_param('sssiii', $registrationNumber, $make, $model, $manufactureYear, $customerId, $vehicleId);

        if ($stmt->execute()) {
            $message = 'Vehicle updated successfully.';
        } else {
            $error = 'Failed to update vehicle.';
        }

        $stmt->close();
    }
}

$stmt = $db->prepare('SELECT id, registration_number, make, model, manufacture_year, customer_id FROM vehicles WHERE id = ? LIMIT 1');
$stmt->bind_param('i', $vehicleId);
$stmt->execute();
$vehicle = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$vehicle) {
    $db->close();
    header('Location: /vehicles.php');
    exit;
}

$customersResult = $db->query('SELECT id, full_name FROM customers ORDER BY full_name');

render('edit_vehicle', [
    'message' => $message,
    'error' => $error,
    'vehicle' => $vehicle,
    'customersResult' => $customersResult,
], true);

$db->close();
