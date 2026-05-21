<?php
/**
 * ClinicOS Admin - Patients
 * Patient management
 */
require_once '../../includes/config.php';

// Authentication disabled - page accessible to all
// if (!isset($_SESSION['admin_id'])) {
//   header('Location: login.php');
//   exit;
// }

$page_title = 'Patients';
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
          <h2 class="h4">Patient Management</h2>
          <p class="text-muted">Manage and view all registered patients.</p>
        </div>
        <div class="col-auto">
          <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Patient</button>
        </div>
      </div>

      <!-- Search Bar -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control" placeholder="Search by name, email, or ID...">
          </div>
        </div>
      </div>

      <!-- Patients Table -->
      <div class="card">
        <div class="table-wrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Patient Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>DOB</th>
                <th>Total Visits</th>
                <th>Last Visit</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>John Smith</strong></td>
                <td>john@email.com</td>
                <td>+1 (555) 123-4567</td>
                <td>Jan 15, 1990</td>
                <td>5</td>
                <td>Dec 10, 2024</td>
                <td>
                  <button class="btn btn-action btn-view"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-action btn-edit"><i class="bi bi-pencil"></i></button>
                </td>
              </tr>
              <tr>
                <td><strong>Sarah Wilson</strong></td>
                <td>sarah@email.com</td>
                <td>+1 (555) 234-5678</td>
                <td>Mar 22, 1985</td>
                <td>8</td>
                <td>Dec 12, 2024</td>
                <td>
                  <button class="btn btn-action btn-view"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-action btn-edit"><i class="bi bi-pencil"></i></button>
                </td>
              </tr>
              <tr>
                <td><strong>Michael Brown</strong></td>
                <td>michael@email.com</td>
                <td>+1 (555) 345-6789</td>
                <td>Jul 08, 1992</td>
                <td>3</td>
                <td>Dec 08, 2024</td>
                <td>
                  <button class="btn btn-action btn-view"><i class="bi bi-eye"></i></button>
                  <button class="btn btn-action btn-edit"><i class="bi bi-pencil"></i></button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
