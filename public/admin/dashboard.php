<?php
/**
 * ClinicOS Admin - Dashboard
 * Main admin dashboard
 */
require_once '../../includes/config.php';

// Authentication disabled - page accessible to all
// if (!isset($_SESSION['admin_id'])) {
//   header('Location: login.php');
//   exit;
// }

$page_title = 'Dashboard';
$user_name = 'Admin User';
?>
<?php include '../../includes/header.php'; ?>

<div class="layout">
  <!-- Sidebar -->
  <?php include '../../components/sidebar-admin.php'; ?>

  <div class="main">
    <!-- Topbar -->
    <?php include '../../components/topbar.php'; ?>

    <!-- Content -->
    <div class="content">
      <!-- Welcome Section -->
      <div class="mb-4">
        <h2 class="h4"><i class="bi bi-hand-thumbs-up"></i> Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h2>
        <p class="text-muted">Here's what's happening with your clinic today.</p>
      </div>

      <!-- Stats Cards -->
      <div class="row g-3 mb-4">
        <div class="col-md-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i class="bi bi-calendar-check"></i>
            </div>
            <div class="stat-content">
              <div class="stat-num">24</div>
              <div class="stat-label">Appointments Today</div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon green">
              <i class="bi bi-people"></i>
            </div>
            <div class="stat-content">
              <div class="stat-num">156</div>
              <div class="stat-label">Total Patients</div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon yellow">
              <i class="bi bi-credit-card"></i>
            </div>
            <div class="stat-content">
              <div class="stat-num">$3,450</div>
              <div class="stat-label">Revenue Today</div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon red">
              <i class="bi bi-clock"></i>
            </div>
            <div class="stat-content">
              <div class="stat-num">3</div>
              <div class="stat-label">Pending Confirmations</div>
            </div>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <!-- Recent Appointments -->
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header bg-light border-bottom d-flex justify-content-between align-items-center">
              <h5 class="mb-0">Recent Appointments</h5>
              <a href="appointments.php" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body p-0">
              <div class="table-wrap">
                <table class="table table-hover mb-0">
                  <thead>
                    <tr>
                      <th>Patient</th>
                      <th>Service</th>
                      <th>Doctor</th>
                      <th>Time</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>John Smith</td>
                      <td>General Checkup</td>
                      <td>Dr. Johnson</td>
                      <td>09:00 AM</td>
                      <td><span class="badge bg-success">Confirmed</span></td>
                    </tr>
                    <tr>
                      <td>Sarah Wilson</td>
                      <td>Dental Care</td>
                      <td>Dr. Martinez</td>
                      <td>10:30 AM</td>
                      <td><span class="badge bg-warning">Pending</span></td>
                    </tr>
                    <tr>
                      <td>Michael Brown</td>
                      <td>Eye Exam</td>
                      <td>Dr. Lee</td>
                      <td>02:00 PM</td>
                      <td><span class="badge bg-success">Confirmed</span></td>
                    </tr>
                    <tr>
                      <td>Emma Davis</td>
                      <td>Consultation</td>
                      <td>Dr. Anderson</td>
                      <td>03:30 PM</td>
                      <td><span class="badge bg-warning">Pending</span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
              <div class="d-grid gap-2">
                <a href="appointments.php" class="btn btn-outline-primary">
                  <i class="bi bi-calendar-check"></i> Manage Appointments
                </a>
                <a href="patients.php" class="btn btn-outline-primary">
                  <i class="bi bi-people"></i> View Patients
                </a>
                <a href="payments.php" class="btn btn-outline-primary">
                  <i class="bi bi-credit-card"></i> Payment History
                </a>
                <a href="customizer.php" class="btn btn-outline-primary">
                  <i class="bi bi-palette"></i> Clinic Settings
                </a>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0"><i class="bi bi-graph-up"></i> This Month</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>Appointments</span>
                  <strong>342</strong>
                </div>
                <div class="progress">
                  <div class="progress-bar" style="width: 75%"></div>
                </div>
              </div>
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                  <span>Revenue</span>
                  <strong>$15,680</strong>
                </div>
                <div class="progress">
                  <div class="progress-bar bg-success" style="width: 82%"></div>
                </div>
              </div>
              <div>
                <div class="d-flex justify-content-between mb-2">
                  <span>New Patients</span>
                  <strong>28</strong>
                </div>
                <div class="progress">
                  <div class="progress-bar bg-info" style="width: 45%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
