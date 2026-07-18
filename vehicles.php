<?php

declare(strict_types=1);

require_once __DIR__ . '/config/auth.php';
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

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main class="container">
    <h2>Vehicle Management</h2>
    <?php if ($message !== ''): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <section class="panel">
        <h3>Register Vehicle</h3>
        <form method="post" data-validate="true" novalidate>
            <label>Registration Number
                <input type="text" name="registration_number" maxlength="20" pattern="[A-Za-z0-9\-]{3,20}" required>
            </label>
            <label>Make
                <input type="text" name="make" maxlength="50" required>
            </label>
            <label>Model
                <input type="text" name="model" maxlength="50" required>
            </label>
            <label>Year
                <input type="number" name="manufacture_year" min="1950" max="2100" required>
            </label>
            <label>Owner
                <select name="customer_id" required>
                    <option value="">Select owner</option>
                    <?php while ($customer = $customersResult->fetch_assoc()): ?>
                        <option value="<?= (int) $customer['id'] ?>"><?= htmlspecialchars($customer['full_name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </label>
            <button type="submit">Save Vehicle</button>
        </form>
    </section>

    <section class="panel">
        <h3>Registered Vehicles</h3>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Registration</th>
                <th>Make</th>
                <th>Model</th>
                <th>Year</th>
                <th>Owner</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($vehicle = $vehiclesResult->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $vehicle['id'] ?></td>
                    <td><?= htmlspecialchars($vehicle['registration_number']) ?></td>
                    <td><?= htmlspecialchars($vehicle['make']) ?></td>
                    <td><?= htmlspecialchars($vehicle['model']) ?></td>
                    <td><?= (int) $vehicle['manufacture_year'] ?></td>
                    <td><?= htmlspecialchars($vehicle['owner_name']) ?></td>
                    <td>
                        <a href="/edit_vehicle.php?id=<?= (int) $vehicle['id'] ?>">Edit</a> |
                        <a href="/delete_vehicle.php?id=<?= (int) $vehicle['id'] ?>" onclick="return confirm('Delete this vehicle?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>
<?php
$db->close();
require __DIR__ . '/includes/footer.php';
?>
