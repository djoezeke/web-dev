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
            <a class="clear-link" href="/services.php">Clear</a>
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
