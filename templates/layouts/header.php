<?php

declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Service Management</title>
    <link rel="stylesheet" href="/assets/style.css">
</head>
<body>
<header class="topbar">
    <div>
        <p class="eyebrow">🚗 University Assignment Project</p>
        <h1>Vehicle Service Management System</h1>
    </div>
    <?php if (isset($_SESSION['admin_username'])): ?>
        <p class="session-user">👤 Logged in as <strong><?= htmlspecialchars((string) $_SESSION['admin_username']) ?></strong></p>
    <?php endif; ?>
</header>
