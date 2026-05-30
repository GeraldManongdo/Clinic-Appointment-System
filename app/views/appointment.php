<section class="container py-5">
    <div class="row g-5">
        <div class="col-lg-7">
            <?php if (!Auth::check()): ?>
                <div class="card border-0 shadow-sm p-4">
                    <h4>Account required</h4>
                    <p class="text-muted">You need an account to book an appointment. Register or login first, then complete your booking.</p>
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="<?= APP_URL ?>/?route=auth&action=login" class="btn btn-primary">Login</a>
                        <a href="<?= APP_URL ?>/?route=auth&action=register" class="btn btn-outline-primary">Register</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h2 class="h4 mb-1">Book an Appointment</h2>
                            <p class="text-muted mb-0">Choose a date, time and confirm your booking with a clear progress form.</p>
                        </div>
                        <span class="badge bg-info text-dark">Logged in as <?= htmlspecialchars($user['name']) ?></span>
                    </div>
                    <div class="mb-3">
                        <div class="progress" style="height: 18px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 66%;" aria-valuenow="66" aria-valuemin="0" aria-valuemax="100">Step 2 of 3</div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 small text-muted">
                            <span>1. Select date</span>
                            <span>2. Confirm payment</span>
                            <span>3. Submit</span>
                        </div>
                    </div>
                </div>
                <form id="bookingForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="book">
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Full name</label><input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" readonly></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" readonly></div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6"><label class="form-label">Service</label><select name="service_id" class="form-select" required>
                            <?php foreach ($services as $service): ?>
                                <option value="<?= $service['id'] ?>"><?= htmlspecialchars($service['title']) ?></option>
                            <?php endforeach; ?>
                        </select></div>
                        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" readonly></div>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-6"><label class="form-label">Date</label><input type="date" name="appointment_date" class="form-control" id="appointmentDate" min="<?= date('Y-m-d') ?>" required></div>
                        <div class="col-md-6"><label class="form-label">Time</label><select name="appointment_time" class="form-select" id="appointmentTime" required><option value="">Select a time</option></select></div>
                    </div>
                    <div id="bookingDateStatus" class="mt-2"></div>
                    <div class="mb-3 mt-3"><label class="form-label">Payment method</label>
                        <div class="btn-group" role="group" aria-label="Payment method">
                            <input type="radio" class="btn-check" name="payment_method" id="payment1" value="GCash" checked><label class="btn btn-outline-primary" for="payment1">GCash</label>
                            <input type="radio" class="btn-check" name="payment_method" id="payment2" value="Maya"><label class="btn btn-outline-primary" for="payment2">Maya</label>
                        </div>
                    </div>
                    <div class="mb-3"><label class="form-label">Payment reference</label><input type="text" name="payment_reference" class="form-control" placeholder="Enter transaction reference" required></div>
                    <div class="mb-3"><label class="form-label">Receipt image</label><input type="file" name="receipt" class="form-control" accept="image/*" required></div>
                    <div class="mb-3"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="3" placeholder="Optional note"></textarea></div>
                    <button type="button" id="submitBooking" class="btn btn-success">Confirm Booking</button>
                    <div id="bookingMessage" class="mt-3"></div>
                </form>
            <?php endif; ?>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4 mb-4">
                <h4>Booking progress</h4>
                <p class="text-muted">Pick a date and time, upload your receipt, then confirm your appointment.</p>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="bi bi-calendar-check-fill text-primary me-2"></i>Choose an available date</li>
                    <li class="mb-2"><i class="bi bi-clock-fill text-primary me-2"></i>Select a free time slot</li>
                    <li class="mb-2"><i class="bi bi-file-earmark-check-fill text-primary me-2"></i>Upload receipt and confirm</li>
                </ul>
            </div>
            <div class="card border-0 shadow-sm p-4">
                <h5 class="mb-3">Calendar overview</h5>
                <div class="row g-2">
                    <?php foreach ($calendarDates as $day): ?>
                        <div class="col-6">
                            <div class="border rounded p-2 <?= $day['status'] === 'blocked' ? 'bg-danger text-white' : ($day['status'] === 'booked' ? 'bg-warning text-dark' : 'bg-light') ?>">
                                <div class="fw-bold"><?= htmlspecialchars($day['label']) ?></div>
                                <small><?= htmlspecialchars($day['date']) ?></small>
                                <div class="mt-1 small">
                                    <?php if ($day['status'] === 'blocked'): ?>
                                        <span class="badge bg-white text-danger">Blocked</span>
                                    <?php elseif ($day['status'] === 'booked'): ?>
                                        <span class="badge bg-white text-warning text-dark">Booked</span>
                                    <?php else: ?>
                                        <span class="badge bg-white text-success">Available</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
