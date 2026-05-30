<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin • <?= htmlspecialchars($site['clinic_name'] ?? APP_NAME) ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
    --primary:#1a6b5a;
    --primary-light:#e8f5f1;
    --dark:#0e1e1a;
    --bg:#f7faf9;
    --border:#dce8e4;
    --text:#3a4a45;
}

/* GLOBAL */
body{
    background:var(--bg);
    font-family:Segoe UI, sans-serif;
    color:var(--text);
}

/* SHELL */
.admin-shell{
    min-height:100vh;
}

/* SIDEBAR */
.admin-sidebar{
    width:260px;
    background:var(--dark);
    color:#fff;
    position:sticky;
    top:0;
    height:100vh;
    display:flex;
    flex-direction:column;
}

.admin-sidebar a{
    color:rgba(255,255,255,.75);
    padding:12px 18px;
    text-decoration:none;
    font-size:.9rem;
    display:flex;
    gap:10px;
    align-items:center;
    transition:.2s;
}

.admin-sidebar a:hover{
    background:rgba(26,107,90,.25);
    color:#fff;
}

.admin-sidebar a.active{
    background:rgba(26,107,90,.35);
    color:#fff;
}

.admin-sidebar .brand{
    padding:16px 18px;
    font-weight:700;
    font-size:1.1rem;
    border-bottom:1px solid rgba(255,255,255,.08);
}

/* CONTENT */
.admin-content{
    flex:1;
    display:flex;
    flex-direction:column;
}

/* TOPBAR */
.admin-topbar{
    height:60px;
    background:#fff;
    border-bottom:1px solid var(--border);
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 20px;
    position:sticky;
    top:0;
    z-index:10;
}

/* PAGE AREA */
.admin-page{
    padding:20px;
}

/* CARDS */
.card-ui{
    background:#fff;
    border:1px solid var(--border);
    border-radius:14px;
    box-shadow:0 4px 18px rgba(0,0,0,.04);
}

/* BUTTON */
.btn-primary{
    background:var(--primary);
    border:none;
}
.btn-primary:hover{
    background:#145547;
}
</style>
</head>

<body>

<div class="admin-shell d-flex">

    <!-- SIDEBAR -->
    <aside class="admin-sidebar">

        <div class="brand d-flex align-items-center gap-2">
            <i class="bi bi-heart-pulse-fill text-success"></i>
            Clinic Admin
        </div>

        <a href="<?= APP_URL ?>/admin/index.php" class="active">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=appointments">
            <i class="bi bi-calendar-check"></i> Appointments
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=services">
            <i class="bi bi-briefcase"></i> Services
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=schedule">
            <i class="bi bi-clock"></i> Schedule
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=accounts">
            <i class="bi bi-people"></i> Accounts
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=messages">
            <i class="bi bi-chat-dots"></i> Messages
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=content">
            <i class="bi bi-gear"></i> Contents
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=settings">
            <i class="bi bi-gear"></i> Settings
        </a>

        <a href="<?= APP_URL ?>/admin/index.php?route=logout" class="text-danger mt-auto">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>

    </aside>

    <!-- MAIN CONTENT -->
    <div class="admin-content">

        <!-- TOPBAR -->
        <div class="admin-topbar">

            <div class="fw-semibold">
                <?= $page_title ?? 'Admin Panel' ?>
            </div>

            <div class="dropdown">
                <a class="text-decoration-none text-dark dropdown-toggle fw-semibold"
                   data-bs-toggle="dropdown">
                    Admin
                </a>

                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><a class="dropdown-item" href="#">Dashboard</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#">Logout</a></li>
                </ul>
            </div>

        </div>

        <!-- PAGE CONTENT -->
        <div class="admin-page">

            <?= $content ?>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const APP_URL = '<?= APP_URL ?>';
</script>
<script src="<?= APP_URL ?>/assets/js/app.js"></script>

</body>
</html>