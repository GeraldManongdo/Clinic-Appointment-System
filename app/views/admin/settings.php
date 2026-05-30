<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

    <div>
        <h4 class="mb-1">Clinic Settings</h4>

        <nav>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Settings</li>
            </ol>
        </nav>
    </div>

</div>

<form onsubmit="event.preventDefault(); saveSettings('settingsForm');" id="settingsForm">

    <input type="hidden" name="action" value="save_setting">

    <div class="row g-4">

        <!-- GENERAL SETTINGS -->
        <div class="col-12">
            <div class="p-3 bg-white border rounded-3 shadow-sm">

                <h6 class="mb-3 text-primary">
                    <i class="bi bi-gear me-1"></i> General Settings
                </h6>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Consultation Fee</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-cash"></i></span>
                            <input class="form-control" name="consultation_fee"
                                value="<?= htmlspecialchars($site['consultation_fee'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Opening Hours</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-clock"></i></span>
                            <input class="form-control" name="opening_hours"
                                value="<?= htmlspecialchars($site['opening_hours'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Booking Duration</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar-range"></i></span>
                            <input class="form-control" name="booking_duration"
                                value="<?= htmlspecialchars($site['booking_duration'] ?? '') ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- CONTACT SETTINGS -->
        <div class="col-12">
            <div class="p-3 bg-white border rounded-3 shadow-sm">

                <h6 class="mb-3 text-primary">
                    <i class="bi bi-person-lines-fill me-1"></i> Contact Information
                </h6>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">Phone Contact</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                            <input class="form-control" name="contact_phone"
                                value="<?= htmlspecialchars($site['contact_phone'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Email Contact</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input class="form-control" name="contact_email"
                                value="<?= htmlspecialchars($site['contact_email'] ?? '') ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- SMTP SETTINGS -->
        <div class="col-12">
            <div class="p-3 bg-white border rounded-3 shadow-sm">

                <h6 class="mb-3 text-primary">
                    <i class="bi bi-envelope-at me-1"></i> Email (SMTP) Settings
                </h6>

                <div class="row g-3">

                    <div class="col-md-6">
                        <label class="form-label">SMTP Host</label>
                        <input class="form-control" name="smtp_host"
                            value="<?= htmlspecialchars($site['smtp_host'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SMTP Port</label>
                        <input class="form-control" name="smtp_port"
                            value="<?= htmlspecialchars($site['smtp_port'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SMTP Username</label>
                        <input class="form-control" name="smtp_username"
                            value="<?= htmlspecialchars($site['smtp_username'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">SMTP Password</label>
                        <input type="password" class="form-control" name="smtp_password"
                            value="<?= htmlspecialchars($site['smtp_password'] ?? '') ?>">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Encryption</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <input class="form-control" name="smtp_encryption"
                                value="<?= htmlspecialchars($site['smtp_encryption'] ?? 'tls') ?>">
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- SAVE BUTTON -->
        <div class="col-12 text-end">
            <button class="btn btn-primary px-4 d-flex align-items-center gap-2 ms-auto">
                <i class="bi bi-check-circle"></i>
                Save Settings
            </button>
        </div>

    </div>

</form>