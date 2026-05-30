<!-- STATS -->
<div class="row g-3 mb-4">

    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary text-white rounded-3 p-3 me-3">
                    <i class="bi bi-calendar-check fs-5"></i>
                </div>
                <div>
                    <small class="text-muted">Total appointments</small>
                    <h4 class="mb-0"><?= $stats['totalAppointments'] ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-success text-white rounded-3 p-3 me-3">
                    <i class="bi bi-hourglass-split fs-5"></i>
                </div>
                <div>
                    <small class="text-muted">Pending bookings</small>
                    <h4 class="mb-0"><?= $stats['pendingAppointments'] ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-info text-white rounded-3 p-3 me-3">
                    <i class="bi bi-briefcase fs-5"></i>
                </div>
                <div>
                    <small class="text-muted">Services</small>
                    <h4 class="mb-0"><?= $stats['services'] ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm border-0 p-3">
            <div class="d-flex align-items-center">
                <div class="bg-warning text-white rounded-3 p-3 me-3">
                    <i class="bi bi-gear fs-5"></i>
                </div>
                <div>
                    <small class="text-muted">Clinic status</small>
                    <h4 class="mb-0"><?= htmlspecialchars($site['booking_duration'] ?? 'N/A'); ?></h4>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- RECENT APPOINTMENTS -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Recent pending appointments</h6>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Patient</th>
                    <th>Service</th>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($recent as $appointment): ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?= htmlspecialchars($appointment['user_name']) ?></div>
                            <small class="text-muted"><?= htmlspecialchars($appointment['user_email']) ?></small>
                        </td>

                        <td><?= htmlspecialchars($appointment['service_title']) ?></td>

                        <td>
                            <i class="bi bi-calendar-event me-1 text-muted"></i>
                            <?= htmlspecialchars($appointment['appointment_date'] . ' ' . $appointment['appointment_time']) ?>
                        </td>

                        <td>
                            <span class="badge bg-warning text-dark">
                                <i class="bi bi-clock me-1"></i>
                                <?= htmlspecialchars($appointment['status']) ?>
                            </span>
                        </td>

                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary"
                                    onclick="openStatusModal(<?= $appointment['id'] ?>)">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
    </div>
</div>

<!-- MODAL -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title">Update booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="appointmentId">

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select id="appointmentStatus" class="form-select">
                        <option value="confirmed">Confirmed</option>
                        <option value="rejected">Rejected</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin note</label>
                    <textarea id="appointmentNote" class="form-control" rows="3"></textarea>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" onclick="updateAppointmentStatus()">
                    Save changes
                </button>
            </div>

        </div>
    </div>
</div>