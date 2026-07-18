<?php

declare(strict_types=1);

require_once __DIR__ . '/config/auth.php';

if (isLoggedIn()) {
    header('Location: /dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } elseif (!loginAdmin($username, $password)) {
        $error = 'Invalid login credentials.';
    } else {
        header('Location: /dashboard.php');
        exit;
    }
}

require __DIR__ . '/includes/header.php';
?>
<main class="container small">
    <h2>Administrator Login</h2>
    <?php if ($error !== ''): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="post" data-validate="true" novalidate>
        <label>Username
            <input type="text" name="username" maxlength="50" required>
        </label>
        <label>Password
            <input type="password" name="password" minlength="6" required>
        </label>
        <button type="submit">Login</button>
    </form>
</main>
<?php require __DIR__ . '/includes/footer.php'; ?>
