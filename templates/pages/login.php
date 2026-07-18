<main class="container small">
    <section class="panel">
        <h2>Administrator Login</h2>
        <p class="hint">Sign in to manage customers, vehicles, and service records.</p>
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
    </section>
</main>
