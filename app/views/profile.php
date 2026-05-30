<section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4">
                <h4>My Profile</h4>
                <form method="post" action="<?= APP_URL ?>/?route=profile">
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly></div>
                    <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>"></div>
                    <button class="btn btn-primary w-100">Update profile</button>
                </form>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Appointment History</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr><th>Date</th><th>Service</th><th>Status</th><th>Reference</th><th></th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($appointments as $appointment): ?>
                            <tr>
                                <td><?= htmlspecialchars($appointment['appointment_date'] . ' ' . $appointment['appointment_time']) ?></td>
                                <td><?= htmlspecialchars($appointment['service_title']) ?></td>
                                <td><span class="badge bg-<?= $appointment['status'] === 'confirmed' ? 'success' : ($appointment['status'] === 'pending' ? 'warning text-dark' : 'secondary') ?>"><?= htmlspecialchars($appointment['status']) ?></span></td>
                                <td><?= htmlspecialchars($appointment['payment_reference']) ?></td>
                                <td class="text-end">
                                    <?php if ($appointment['status'] === 'pending'): ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="cancelAppointment(<?= $appointment['id'] ?>)">Cancel</button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-outline-secondary" disabled>Locked</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($appointments)): ?>
                            <tr><td colspan="5" class="text-center text-muted">No appointments yet.</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
