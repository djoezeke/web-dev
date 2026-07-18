<main class="container">
    <h2>Customer Management</h2>
    <?php if ($message !== ''): ?><p class="success"><?= htmlspecialchars($message) ?></p><?php endif; ?>
    <?php if ($error !== ''): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <section class="panel">
        <h3>Register Customer</h3>
        <form method="post" data-validate="true" novalidate>
            <label>Full Name
                <input type="text" name="full_name" maxlength="100" required>
            </label>
            <label>Phone
                <input type="tel" name="phone" pattern="[0-9+\-\s]{7,20}" required>
            </label>
            <label>Email
                <input type="email" name="email" maxlength="100" required>
            </label>
            <label>Address
                <textarea name="address" maxlength="255" required></textarea>
            </label>
            <button type="submit">Save Customer</button>
        </form>
    </section>

    <section class="panel">
        <h3>Registered Customers</h3>
        <table>
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Registered</th>
            </tr>
            </thead>
            <tbody>
            <?php while ($customer = $customersResult->fetch_assoc()): ?>
                <tr>
                    <td><?= (int) $customer['id'] ?></td>
                    <td><?= htmlspecialchars($customer['full_name']) ?></td>
                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                    <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td><?= htmlspecialchars($customer['address']) ?></td>
                    <td><?= htmlspecialchars($customer['created_at']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </section>
</main>
