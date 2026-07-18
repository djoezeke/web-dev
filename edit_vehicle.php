<?php

declare(strict_types=1);

require_once __DIR__ . '/config/auth.php';
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

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main class="container small">
    <h2>Edit Vehicle</h2>
    <?php if ($message !== ''): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <form method="post" data-validate="true" novalidate>
        <input type="hidden" name="id" value="<?= (int) $vehicle['id'] ?>">
        <label>Registration Number
            <input type="text" name="registration_number" maxlength="20" pattern="[A-Za-z0-9\-]{3,20}" value="<?= htmlspecialchars($vehicle['registration_number']) ?>" required>
        </label>
        <label>Make
            <input type="text" name="make" maxlength="50" value="<?= htmlspecialchars($vehicle['make']) ?>" required>
        </label>
        <label>Model
            <input type="text" name="model" maxlength="50" value="<?= htmlspecialchars($vehicle['model']) ?>" required>
        </label>
        <label>Year
            <input type="number" name="manufacture_year" min="1950" max="2100" value="<?= (int) $vehicle['manufacture_year'] ?>" required>
        </label>
        <label>Owner
            <select name="customer_id" required>
                <option value="">Select owner</option>
                <?php while ($customer = $customersResult->fetch_assoc()): ?>
                    <option value="<?= (int) $customer['id'] ?>" <?= (int) $customer['id'] === (int) $vehicle['customer_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($customer['full_name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </label>
        <button type="submit">Update Vehicle</button>
    </form>
    <p><a href="/vehicles.php">Back to vehicles</a></p>
</main>
<?php
$db->close();
require __DIR__ . '/includes/footer.php';
?>
