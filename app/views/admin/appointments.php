<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

    <div>
        <h5 class="mb-0 fw-semibold">Appointments</h5>
        <small class="text-muted">Manage clinic bookings and schedules</small>
    </div>

</div>

<!-- FILTER TOOLBAR -->
<div class="card-ui p-3 mb-4">

    <form class="d-flex flex-wrap gap-3 align-items-center justify-content-between"
          method="get"
          action="<?= APP_URL ?>/admin/index.php">

        <input type="hidden" name="route" value="appointments">

        <!-- SEARCH -->
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>

            <input type="search"
                   name="search"
                   class="form-control"
                   placeholder="Search patient, service, email..."
                   value="<?= htmlspecialchars($search) ?>">
        </div>

        <!-- FILTERS -->
        <div class="d-flex flex-wrap gap-2 align-items-center">

            <div class="input-group" style="width:180px;">
                <span class="input-group-text">
                    <i class="bi bi-funnel"></i>
                </span>

                <select class="form-select" name="status">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div class="input-group" style="width:200px;">
                <span class="input-group-text">
                    <i class="bi bi-calendar-event"></i>
                </span>

                <input type="date" name="date" class="form-control">
            </div>

            <button class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-sliders"></i>
                Apply
            </button>

        </div>

    </form>
</div>

<!-- TABLE -->
<div class="card-ui overflow-hidden">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Patient</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($appointments as $appointment): ?>
                <tr>

                    <!-- PATIENT -->
                    <td class="fw-semibold">
                        <?= htmlspecialchars($appointment['user_name']) ?><br>
                        <small class="text-muted">
                            <?= htmlspecialchars($appointment['user_email']) ?>
                        </small>
                    </td>

                    <!-- SERVICE -->
                    <td><?= htmlspecialchars($appointment['service_title']) ?></td>

                    <!-- DATE -->
                    <td>
                        <?= htmlspecialchars($appointment['appointment_date']) ?>
                        <br>
                        <small class="text-muted">
                            <?= htmlspecialchars($appointment['appointment_time']) ?>
                        </small>
                    </td>

                    <!-- PAYMENT -->
                    <td>
                        <span class="text-muted">
                            <?= htmlspecialchars($appointment['payment_method']) ?>
                        </span>
                        /
                        <small class="text-muted">
                            <?= htmlspecialchars($appointment['payment_reference']) ?>
                        </small>
                    </td>

                    <!-- STATUS -->
                    <td>
                        <?php if ($appointment['status'] === 'confirmed'): ?>
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i>Confirmed
                            </span>

                        <?php elseif ($appointment['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock me-1"></i>Pending
                            </span>

                        <?php elseif ($appointment['status'] === 'cancelled'): ?>
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle me-1"></i>Cancelled
                            </span>

                        <?php else: ?>
                            <span class="badge bg-light text-dark">
                                <?= htmlspecialchars($appointment['status']) ?>
                            </span>
                        <?php endif; ?>
                    </td>

                    <!-- ACTIONS -->
                    <td class="text-end">

                        <button class="btn btn-sm btn-outline-primary"
                                title="View / Update"
                                onclick="openStatusModal(<?= $appointment['id'] ?>)">
                            <i class="bi bi-eye"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-success"
                                title="Confirm"
                                onclick="openStatusModal(<?= $appointment['id'] ?>)">
                            <i class="bi bi-check2-circle"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger"
                                title="Cancel">
                            <i class="bi bi-x-circle"></i>
                        </button>

                    </td>

                </tr>
                <?php endforeach; ?>

                <?php if (empty($appointments)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No bookings found
                    </td>
                </tr>
                <?php endif; ?>

            </tbody>

        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="statusModal" tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Update Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="appointmentId">

                <div class="mb-3">
                    <label class="form-label">Status</label>

                    <select id="appointmentStatus" class="form-select">
                        <option value="confirmed">Confirm</option>
                        <option value="rejected">Reject</option>
                        <option value="cancelled">Cancel</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Notes</label>
                    <textarea id="appointmentNote" class="form-control" rows="3"
                        placeholder="Add internal notes..."></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="updateAppointmentStatus()">
                    Save Changes
                </button>
            </div>

        </div>
    </div>

</div>