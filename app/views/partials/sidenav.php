<aside class="sidebar bg-white border-end">

    <!-- BRAND -->
    <div class="p-3 border-bottom d-flex align-items-center gap-2">
        <i class="bi bi-heart-pulse-fill text-danger fs-4"></i>
        <span class="h5 mb-0">Clinicos</span>
    </div>

    <!-- NAV LINKS -->
    <div class="list-group list-group-flush">

        <a href="<?= APP_URL ?>/admin/index.php?route=dashboard"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=appointments"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'appointments' ? 'active' : '' ?>">
            <i class="bi bi-calendar-check me-2"></i> Appointments
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=patients"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'patients' ? 'active' : '' ?>">
            <i class="bi bi-people me-2"></i> Patients
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=services"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'services' ? 'active' : '' ?>">
            <i class="bi bi-briefcase me-2"></i> Services
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=schedule"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'schedule' ? 'active' : '' ?>">
            <i class="bi bi-calendar3 me-2"></i> Schedule
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=accounts"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'accounts' ? 'active' : '' ?>">
            <i class="bi bi-person-gear me-2"></i> Accounts
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=messages"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'messages' ? 'active' : '' ?>">
            <i class="bi bi-chat-dots me-2"></i> Messages
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=settings"
           class="list-group-item list-group-item-action <?= ($route ?? '') === 'settings' ? 'active' : '' ?>">
            <i class="bi bi-gear me-2"></i> Settings
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=logout"
           class="list-group-item list-group-item-action text-danger">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </a>

    </div>

</aside>