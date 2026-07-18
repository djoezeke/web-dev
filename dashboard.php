<?php

declare(strict_types=1);

require_once __DIR__ . '/config/auth.php';
requireLogin();

require __DIR__ . '/includes/header.php';
require __DIR__ . '/includes/nav.php';
?>
<main class="container">
    <h2>Dashboard</h2>
    <div class="cards">
        <a class="card" href="/customers.php">Manage Customers</a>
        <a class="card" href="/vehicles.php">Manage Vehicles</a>
        <a class="card" href="/services.php">Manage Services</a>
    </div>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
