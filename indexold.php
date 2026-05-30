<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clinicos Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
    --primary:#1a6b5a;
    --primary-light:#e8f5f1;
    --text:#3a4a45;
    --bg:#f7faf9;
    --border:#dce8e4;
}

body{
    background:var(--bg);
    font-family:Segoe UI, sans-serif;
    color:var(--text);
}

/* SIDEBAR */
.sidebar{
    width:260px;
    height:100vh;
    position:fixed;
    left:0;
    top:0;
    background:#0e1e1a;
    padding:20px 0;
}

.sidebar-brand{
    color:#fff;
    font-weight:700;
    font-size:1.2rem;
    padding:0 20px 20px;
    display:flex;
    align-items:center;
    gap:10px;
}

.sidebar a{
    display:flex;
    gap:10px;
    padding:12px 20px;
    color:rgba(255,255,255,.75);
    text-decoration:none;
    font-size:.9rem;
    transition:.2s;
}

.sidebar a:hover,
.sidebar a.active{
    background:rgba(26,107,90,.25);
    color:#fff;
}

/* MAIN */
.main{
    margin-left:260px;
}

/* TOPBAR */
.topbar{
    height:60px;
    background:#fff;
    border-bottom:1px solid var(--border);
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 20px;
    position:sticky;
    top:0;
    z-index:10;
}

/* CARD UI */
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

/* TABLE POLISH */
.table tbody tr:hover{
    background:#f7faf9;
}

.table td, .table th{
    padding:14px 16px;
    font-size:.9rem;
}

.input-group-text{
    background:#fff;
    border-color:var(--border);
}

.form-control:focus,
.form-select:focus{
    box-shadow:0 0 0 3px rgba(26,107,90,.12);
    border-color:var(--primary);
}
</style>
</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-heart-pulse-fill"></i> Clinicos
    </div>

    <a href="#" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="#"><i class="bi bi-calendar-check"></i> Appointments</a>
    <a href="#"><i class="bi bi-people"></i> Patients</a>
    <a href="#"><i class="bi bi-briefcase"></i> Services</a>
    <a href="#"><i class="bi bi-gear"></i> Settings</a>
</div>

<!-- MAIN -->
<div class="main">

    <!-- TOPBAR -->
    <div class="topbar">
        <div class="fw-semibold">Appointments</div>

        <div class="dropdown">
            <a class="text-dark text-decoration-none dropdown-toggle fw-semibold"
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

    <div class="container-fluid p-4">

        <!-- BREADCRUMB -->
        <nav class="mb-3">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Appointments</li>
            </ol>
        </nav>

        <h3 class="mb-4">Appointments Management</h3>

        <!-- FILTER TOOLBAR -->
        <div class=" p-1 mb-1">

            <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">

                <!-- SEARCH -->
                <div class="input-group" style="max-width:420px;">
                    <span class="input-group-text">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control"
                        placeholder="Search patient, service, or ID...">
                </div>

                <!-- FILTERS -->
                <div class="d-flex flex-wrap gap-2 align-items-center">

                    <div class="input-group" style="width:180px;">
                        <span class="input-group-text">
                            <i class="bi bi-funnel"></i>
                        </span>
                        <select class="form-select">
                            <option>All Status</option>
                            <option>Pending</option>
                            <option>Confirmed</option>
                            <option>Cancelled</option>
                        </select>
                    </div>

                    <div class="input-group" style="width:200px;">
                        <span class="input-group-text">
                            <i class="bi bi-calendar-event"></i>
                        </span>
                        <input type="date" class="form-control">
                    </div>

                    <button class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-sliders"></i>
                        Apply
                    </button>

                </div>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card-ui overflow-hidden">

            <div class="table-responsive">
                <table class="table align-middle mb-0">

                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td class="fw-semibold">Juan Dela Cruz</td>
                            <td>General Checkup</td>
                            <td>May 26, 2026</td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-clock me-1"></i>Pending
                                </span>
                            </td>

                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-check2-circle"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </td>
                        </tr>

                        <tr>
                            <td class="fw-semibold">Maria Santos</td>
                            <td>Dental Consultation</td>
                            <td>May 27, 2026</td>

                            <td>
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle me-1"></i>Confirmed
                                </span>
                            </td>

                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i>
                                </button>

                                <button class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>

                </table>
            </div>

        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-between align-items-center mt-3">

            <small class="text-muted">Showing 1 to 10 of 50 entries</small>

            <nav>
                <ul class="pagination pagination-sm mb-0">

                    <li class="page-item disabled">
                        <a class="page-link">Previous</a>
                    </li>

                    <li class="page-item active">
                        <a class="page-link">1</a>
                    </li>

                    <li class="page-item"><a class="page-link">2</a></li>
                    <li class="page-item"><a class="page-link">3</a></li>

                    <li class="page-item">
                        <a class="page-link">Next</a>
                    </li>

                </ul>
            </nav>

        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 