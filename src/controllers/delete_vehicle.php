<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/auth.php';

requireLogin();

$vehicleId = (int) ($_GET['id'] ?? 0);

if ($vehicleId > 0) {
    $db = getDbConnection();
    $stmt = $db->prepare('DELETE FROM vehicles WHERE id = ?');
    $stmt->bind_param('i', $vehicleId);
    $stmt->execute();
    $stmt->close();
    $db->close();
}

header('Location: /vehicles.php');
exit;
