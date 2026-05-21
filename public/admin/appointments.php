<?php
/**
 * ClinicOS Admin - Appointments
 * Manage all appointments
 */
require_once '../../includes/config.php';

// Authentication disabled - page accessible to all
// if (!isset($_SESSION['admin_id'])) {
//   header('Location: login.php');
//   exit;
// }

$page_title = 'Appointments';
$user_name = 'Admin User';
?>
<?php include '../../includes/header.php'; ?>

<div class="layout">
  <?php include '../../components/sidebar-admin.php'; ?>

  <div class="main">
    <?php include '../../components/topbar.php'; ?>

    <div class="content">
      <div class="row mb-4">
        <div class="col">
          <h2 class="h4">Appointment Management</h2>
          <p class="text-muted">Manage and track all clinic appointments.</p>
        </div>
        <div class="col-auto">
          <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#newAppointmentModal">
            <i class="bi bi-plus-lg"></i> New Appointment
          </button>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3">
              <input type="date" class="form-control" placeholder="Filter by date">
            </div>
            <div class="col-md-3">
              <select class="form-select">
                <option>All Status</option>
                <option>Confirmed</option>
                <option>Pending</option>
                <option>Completed</option>
                <option>Cancelled</option>
              </select>
            </div>
            <div class="col-md-3">
              <input type="text" class="form-control" placeholder="Search patient...">
            </div>
            <div class="col-md-3">
              <button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Search</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Appointments Table -->
      <div class="card">
        <div class="table-wrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Service</th>
                <th>Date & Time</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>John Smith</strong><br><span class="text-muted small">john@email.com</span></td>
                <td>Dr. Johnson</td>
                <td>General Checkup</td>
                <td>Dec 15, 2024<br>09:00 AM</td>
                <td><span class="badge bg-success">Confirmed</span></td>
                <td>
                  <button class="btn btn-action btn-edit"><i class="bi bi-pencil"></i></button>
                  <button class="btn btn-action btn-view"><i class="bi bi-eye"></i></button>
                </td>
              </tr>
              <tr>
                <td><strong>Sarah Wilson</strong><br><span class="text-muted small">sarah@email.com</span></td>
                <td>Dr. Martinez</td>
                <td>Dental Care</td>
                <td>Dec 15, 2024<br>10:30 AM</td>
                <td><span class="badge bg-warning">Pending</span></td>
                <td>
                  <button class="btn btn-action btn-approve"><i class="bi bi-check-circle"></i></button>
                  <button class="btn btn-action btn-reject"><i class="bi bi-x-circle"></i></button>
                </td>
              </tr>
              <tr>
                <td><strong>Michael Brown</strong><br><span class="text-muted small">michael@email.com</span></td>
                <td>Dr. Lee</td>
                <td>Eye Exam</td>
                <td>Dec 15, 2024<br>02:00 PM</td>
                <td><span class="badge bg-success">Confirmed</span></td>
                <td>
                  <button class="btn btn-action btn-edit"><i class="bi bi-pencil"></i></button>
                  <button class="btn btn-action btn-view"><i class="bi bi-eye"></i></button>
                </td>
              </tr>
              <tr>
                <td><strong>Emma Davis</strong><br><span class="text-muted small">emma@email.com</span></td>
                <td>Dr. Anderson</td>
                <td>Consultation</td>
                <td>Dec 15, 2024<br>03:30 PM</td>
                <td><span class="badge bg-danger">Cancelled</span></td>
                <td>
                  <button class="btn btn-action btn-view"><i class="bi bi-eye"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <nav class="mt-4">
        <ul class="pagination justify-content-center">
          <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
          <li class="page-item active"><a class="page-link" href="#">1</a></li>
          <li class="page-item"><a class="page-link" href="#">2</a></li>
          <li class="page-item"><a class="page-link" href="#">3</a></li>
          <li class="page-item"><a class="page-link" href="#">Next</a></li>
        </ul>
      </nav>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
