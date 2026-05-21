<?php
/**
 * ClinicOS Admin - Services Management
 */
require_once '../../includes/config.php';

// Authentication disabled - page accessible to all
// if (!isset($_SESSION['admin_id'])) {
//   header('Location: login.php');
//   exit;
// }

$page_title = 'Services';
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
          <h2 class="h4">Service Management</h2>
          <p class="text-muted">Manage available clinic services.</p>
        </div>
        <div class="col-auto">
          <button class="btn btn-primary"><i class="bi bi-plus-lg"></i> Add Service</button>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">General Checkup</h5>
              <p class="card-text text-muted small">Comprehensive health screening</p>
              <p class="fw-bold">$50</p>
              <div class="btn-group w-100">
                <button class="btn btn-sm btn-outline-primary">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Dental Care</h5>
              <p class="card-text text-muted small">Professional dental services</p>
              <p class="fw-bold">$75</p>
              <div class="btn-group w-100">
                <button class="btn btn-sm btn-outline-primary">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-6 col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Eye Care</h5>
              <p class="card-text text-muted small">Vision screening and exams</p>
              <p class="fw-bold">$60</p>
              <div class="btn-group w-100">
                <button class="btn btn-sm btn-outline-primary">Edit</button>
                <button class="btn btn-sm btn-outline-danger">Delete</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
