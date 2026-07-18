<main class="container small">
    <section class="panel">
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
        <p><a href="/vehicles.php">← Back to vehicles</a></p>
    </section>
</main>
