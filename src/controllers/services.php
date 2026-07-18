<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/auth.php';
require_once __DIR__ . '/../helpers/view.php';

requireLogin();

$db = getDbConnection();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicleId = (int) ($_POST['vehicle_id'] ?? 0);
    $serviceDate = trim($_POST['service_date'] ?? '');
    $serviceType = trim($_POST['service_type'] ?? '');
    $mechanicName = trim($_POST['mechanic_name'] ?? '');
    $cost = (float) ($_POST['cost'] ?? 0);
    $status = trim($_POST['status'] ?? 'Pending');
    $notes = trim($_POST['notes'] ?? '');

    $allowedStatus = ['Pending', 'In Progress', 'Completed'];

    if ($vehicleId === 0 || $serviceDate === '' || $serviceType === '' || $mechanicName === '' || $cost <= 0 || !in_array($status, $allowedStatus, true)) {
        $error = 'All required service fields must be valid.';
    } else {
        $stmt = $db->prepare('INSERT INTO services (vehicle_id, service_date, service_type, mechanic_name, cost, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('isssdss', $vehicleId, $serviceDate, $serviceType, $mechanicName, $cost, $status, $notes);

        if ($stmt->execute()) {
            $message = 'Service record saved.';
        } else {
            $error = 'Failed to save service record.';
        }

        $stmt->close();
    }
}

$search = trim($_GET['search'] ?? '');
$searchSql = '';
$params = [];
$types = '';

if ($search !== '') {
    $searchSql = ' WHERE v.registration_number LIKE ? OR s.service_type LIKE ? OR s.mechanic_name LIKE ? OR s.status LIKE ?';
    $searchTerm = '%' . $search . '%';
    $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
    $types = 'ssss';
}

$vehiclesResult = $db->query('SELECT id, registration_number FROM vehicles ORDER BY registration_number');

$query = 'SELECT s.id, s.service_date, s.service_type, s.mechanic_name, s.cost, s.status, s.notes, v.registration_number
          FROM services s
          INNER JOIN vehicles v ON v.id = s.vehicle_id'
          . $searchSql .
          ' ORDER BY s.service_date DESC, s.id DESC';

$stmt = $db->prepare($query);
if ($search !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$servicesResult = $stmt->get_result();

render('services', [
    'message' => $message,
    'error' => $error,
    'search' => $search,
    'vehiclesResult' => $vehiclesResult,
    'servicesResult' => $servicesResult,
], true);

$stmt->close();
$db->close();
