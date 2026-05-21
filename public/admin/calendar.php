<?php
/**
 * ClinicOS Admin - Calendar & Services Management
 */
require_once '../../includes/config.php';

// Authentication disabled - page accessible to all
// if (!isset($_SESSION['admin_id'])) {
//   header('Location: login.php');
//   exit;
// }

$page_title = 'Calendar Settings';
$user_name = 'Admin User';
?>
<?php include '../../includes/header.php'; ?>

<div class="layout">
  <?php include '../../components/sidebar-admin.php'; ?>

  <div class="main">
    <?php include '../../components/topbar.php'; ?>

    <div class="content">
      <h2 class="h4 mb-1">Calendar Management</h2>
      <p class="text-muted mb-4">Manage appointment slots and calendar settings.</p>

      <div class="row">
        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Available Time Slots</h5>
            </div>
            <div class="card-body">
              <form>
                <div class="mb-3">
                  <label class="form-label fw-600">Appointment Duration</label>
                  <select class="form-select">
                    <option>15 minutes</option>
                    <option>30 minutes</option>
                    <option>45 minutes</option>
                    <option>60 minutes</option>
                  </select>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-600">Daily Slot Limit</label>
                  <input type="number" class="form-control" value="20">
                </div>

                <div class="mb-3">
                  <label class="form-label fw-600">Buffer Time Between Appointments</label>
                  <input type="number" class="form-control" value="5" placeholder="Minutes">
                </div>

                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Save Settings</button>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Holidays & Closures</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <input type="date" class="form-control mb-2">
                <button class="btn btn-outline-primary"><i class="bi bi-plus-lg"></i> Add Holiday</button>
              </div>

              <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between">
                  <span>Christmas</span>
                  <button class="btn btn-sm btn-outline-danger">Remove</button>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                  <span>New Year's Day</span>
                  <button class="btn btn-sm btn-outline-danger">Remove</button>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Calendar Preview</h5>
            </div>
            <div class="card-body">
              <div class="alert alert-info alert-sm mb-3">
                <small>📅 Calendar preview would appear here</small>
              </div>

              <div class="list-group small">
                <a href="#" class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div>Dec 15</div>
                    <div class="ms-auto text-muted">8 slots</div>
                  </div>
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                  <div class="d-flex">
                    <div>Dec 16</div>
                    <div class="ms-auto text-muted">Closed</div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
