<!-- ─── FOOTER ─── -->
<footer class="py-5">
    <div class="container">

        <div class="row g-4 justify-content-between">

            <!-- Brand -->
            <div class="col-lg-4 col-md-6">
                <span class="brand-name d-inline-flex align-items-center mb-3">
                    <i class="bi bi-heart-pulse-fill me-2" style="color:var(--primary-mid);"></i>
                    <?= htmlspecialchars($site['clinic_name'] ?? APP_NAME) ?>
                </span>

                <p style="font-size:.875rem; line-height:1.8; max-width:360px;">
                    A modern online appointment system designed to simplify clinic scheduling and improve the healthcare experience for every patient.
                </p>
            </div>

            <!-- Navigation -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="mb-3">Navigation</h6>

                <a href="<?= APP_URL ?>">Home</a>
                <a href="<?= APP_URL ?>/#features">Features</a>
                <a href="<?= APP_URL ?>/#services">Services</a>
                <a href="<?= APP_URL ?>/#how-it-works">How It Works</a>
                <a href="<?= APP_URL ?>/#contact">Contact</a>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-3 col-6">
                <h6 class="mb-3">Quick Links</h6>

                <a href="<?= APP_URL ?>/?route=appointment">Book Appointment</a>
                <a href="<?= APP_URL ?>/?route=profile">My Profile</a>
                <a href="<?= APP_URL ?>/?route=auth&action=login">Login</a>
                <a href="<?= APP_URL ?>/?route=auth&action=register">Register</a>
            </div>

            <!-- Contact -->
            <div class="col-lg-3 col-md-6">
                <h6 class="mb-3">Contact</h6>

                <p style="font-size:.875rem; margin-bottom:10px;">
                    <i class="bi bi-geo-alt me-2"></i>
                    123 Main Street, Quezon City
                </p>

                <p style="font-size:.875rem; margin-bottom:10px;">
                    <i class="bi bi-telephone me-2"></i>
                    +63 917 123 4567
                </p>

                <p style="font-size:.875rem; margin-bottom:0;">
                    <i class="bi bi-envelope me-2"></i>
                    appointments@clinicos.ph
                </p>
            </div>

        </div>

        <hr class="my-4">

        <!-- Bottom -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">

            <p class="copyright mb-0">
                © <?= date('Y') ?> <?= htmlspecialchars($site['clinic_name'] ?? APP_NAME) ?>. All rights reserved.
            </p>

            <div class="d-flex gap-3">
                <a href="#" style="font-size:.8rem;">Privacy Policy</a>
                <a href="#" style="font-size:.8rem;">Terms of Use</a>
            </div>

        </div>

    </div>
</footer>