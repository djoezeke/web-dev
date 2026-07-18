<?php

declare(strict_types=1);

require_once __DIR__ . '/config/auth.php';
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

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main class="container">
    <h2>Service Management</h2>
    <?php if ($message !== ''): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <section class="panel">
        <h3>Record Vehicle Service</h3>
        <form method="post" data-validate="true" novalidate>
            <label>Vehicle
                <select name="vehicle_id" required>
                    <option value="">Select vehicle</option>
                    <?php while ($vehicle = $vehiclesResult->fetch_assoc()): ?>
                        <option value="<?= (int) $vehicle['id'] ?>"><?= htmlspecialchars($vehicle['registration_number']) ?></option>
                    <?php endwhile; ?>
                </select>
            </label>
            <label>Service Date
                <input type="date" name="service_date" required>
            </label>
            <label>Service Type
                <input type="text" name="service_type" maxlength="100" required>
            </label>
            <label>Mechanic
                <input type="text" name="mechanic_name" maxlength="100" required>
            </label>
            <label>Cost
                <input type="number" name="cost" min="0.01" step="0.01" required>
            </label>
            <label>Status
                <select name="status" required>
                    <option value="Pending">Pending</option>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </label>
            <label>Notes
                <textarea name="notes" maxlength="500"></textarea>
            </label>
            <button type="submit">Save Service</button>
        </form>
    </section>

    <section class="panel">
        <h3>Service Records</h3>
        <form method="get" class="inline-form" data-validate="true" novalidate>
            <input type="text" name="search" placeholder="Search by reg no, type, mechanic, status" value="<?= htmlspecialchars($search) ?>">
            <button type="submit">Search</button>
            <a href="/services.php">Clear</a>
        </form>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Vehicle</th>
                <th>Type</th>
                <th>Mechanic</th>
                <th>Cost</th>
                <th>Status</th>
                <th>Notes</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($service = $servicesResult->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $service['id'] ?></td>
                    <td><?= htmlspecialchars($service['service_date']) ?></td>
                    <td><?= htmlspecialchars($service['registration_number']) ?></td>
                    <td><?= htmlspecialchars($service['service_type']) ?></td>
                    <td><?= htmlspecialchars($service['mechanic_name']) ?></td>
                    <td><?= number_format((float) $service['cost'], 2) ?></td>
                    <td><?= htmlspecialchars($service['status']) ?></td>
                    <td><?= htmlspecialchars((string) $service['notes']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>
<?php
$stmt->close();
$db->close();
require __DIR__ . '/includes/footer.php';
?>
