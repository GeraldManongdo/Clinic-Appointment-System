<?php
/**
 * ClinicOS Admin - Customizer
 * Clinic settings and customization
 */
require_once '../../includes/config.php';

// Authentication disabled - page accessible to all
// if (!isset($_SESSION['admin_id'])) {
//   header('Location: login.php');
//   exit;
// }

$page_title = 'Customizer';
$user_name = 'Admin User';
?>
<?php include '../../includes/header.php'; ?>

<div class="layout">
  <?php include '../../components/sidebar-admin.php'; ?>

  <div class="main">
    <?php include '../../components/topbar.php'; ?>

    <div class="content">
      <div class="mb-4">
        <h2 class="h4">Clinic Settings</h2>
        <p class="text-muted">Customize your clinic's appearance and settings.</p>
      </div>

      <div class="row">
        <div class="col-lg-8">
          <!-- Clinic Information -->
          <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Clinic Information</h5>
            </div>
            <div class="card-body">
              <form>
                <div class="mb-3">
                  <label class="form-label fw-600">Clinic Name</label>
                  <input type="text" class="form-control" value="ClinicOS" required>
                </div>

                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-600">Email</label>
                    <input type="email" class="form-control" value="info@clinicos.com" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label class="form-label fw-600">Phone</label>
                    <input type="tel" class="form-control" value="+1 (555) 123-4567" required>
                  </div>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-600">Address</label>
                  <input type="text" class="form-control" value="123 Medical Center Dr, Healthcare City" required>
                </div>

                <div class="mb-3">
                  <label class="form-label fw-600">Clinic Description</label>
                  <textarea class="form-control" rows="4">A trusted medical appointment system providing comprehensive healthcare services.</textarea>
                </div>

                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> Save Changes</button>
              </form>
            </div>
          </div>

          <!-- Business Hours -->
          <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Business Hours</h5>
            </div>
            <div class="card-body">
              <form>
                <div class="row mb-3">
                  <div class="col-md-2">
                    <strong>Monday</strong>
                  </div>
                  <div class="col-md-5">
                    <input type="time" class="form-control" value="09:00">
                  </div>
                  <div class="col-md-5">
                    <input type="time" class="form-control" value="18:00">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-2">
                    <strong>Tuesday</strong>
                  </div>
                  <div class="col-md-5">
                    <input type="time" class="form-control" value="09:00">
                  </div>
                  <div class="col-md-5">
                    <input type="time" class="form-control" value="18:00">
                  </div>
                </div>
                <div class="row mb-3">
                  <div class="col-md-2">
                    <strong>Friday</strong>
                  </div>
                  <div class="col-md-5">
                    <input type="time" class="form-control" value="09:00">
                  </div>
                  <div class="col-md-5">
                    <input type="time" class="form-control" value="18:00">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-2">
                    <strong>Saturday</strong>
                  </div>
                  <div class="col-md-10">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" id="saturdayOff">
                      <label class="form-check-label" for="saturdayOff">
                        Closed on Saturday
                      </label>
                    </div>
                  </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">💾 Save Hours</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Sidebar Settings -->
        <div class="col-lg-4">
          <!-- Theme Settings -->
          <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Theme Settings</h5>
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label class="form-label fw-600">Primary Color</label>
                <input type="color" class="form-control form-control-color" value="#0a7c6e">
              </div>
              <div class="mb-3">
                <label class="form-label fw-600">Secondary Color</label>
                <input type="color" class="form-control form-control-color" value="#c8962a">
              </div>
              <button type="submit" class="btn btn-primary w-100">Apply Theme</button>
            </div>
          </div>

          <!-- Notifications -->
          <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Notifications</h5>
            </div>
            <div class="card-body">
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="emailNotif" checked>
                <label class="form-check-label" for="emailNotif">
                  Email Notifications
                </label>
              </div>
              <div class="form-check form-switch mb-2">
                <input class="form-check-input" type="checkbox" id="smsNotif" checked>
                <label class="form-check-label" for="smsNotif">
                  SMS Notifications
                </label>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="appointRemind" checked>
                <label class="form-check-label" for="appointRemind">
                  Appointment Reminders
                </label>
              </div>
            </div>
          </div>

          <!-- System Info -->
          <div class="card">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body small">
              <p><strong>Version:</strong> 2.0.1</p>
              <p><strong>Last Update:</strong> Dec 10, 2024</p>
              <p><strong>Database:</strong> Active</p>
              <p><strong>API Status:</strong> ✓ Online</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
