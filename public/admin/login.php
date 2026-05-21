<?php
/**
 * ClinicOS Admin - Login Page
 * Admin authentication
 */
require_once '../../includes/config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_id'])) {
  header('Location: dashboard.php');
  exit;
}

$page_title = 'Admin Login';
?>
<?php include '../../includes/header.php'; ?>

<style>
  body {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
  }
  .login-container {
    width: 100%;
    max-width: 420px;
  }
</style>

<div class="login-container">
  <div class="card shadow-lg">
    <div class="card-body p-5">
      <!-- Logo -->
      <div class="text-center mb-4">
        <div class="rounded-3 d-inline-flex align-items-center justify-content-center bg-primary text-white mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
          ⚕️
        </div>
        <h1 class="h4 mb-1">ClinicOS Admin</h1>
        <p class="text-muted small">Clinic Management System</p>
      </div>

      <!-- Login Form -->
      <form method="POST" action="../auth/admin_auth.php">
        <div class="mb-3">
          <label class="form-label fw-600">Email Address</label>
          <div class="input-group">
            <span class="input-group-text border-end-0 bg-light">📧</span>
            <input type="email" class="form-control border-start-0" name="email" placeholder="admin@clinicos.com" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-600">Password</label>
          <div class="input-group">
            <span class="input-group-text border-end-0 bg-light">🔐</span>
            <input type="password" class="form-control border-start-0" name="password" placeholder="Enter your password" required>
          </div>
        </div>

        <div class="form-check mb-4">
          <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
          <label class="form-check-label" for="rememberMe">
            Remember me
          </label>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-600 mb-3">
          Login to Dashboard
        </button>
      </form>

      <!-- Error Message (if any) -->
      <?php if (isset($_GET['error'])): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> Invalid email or password.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      <?php endif; ?>

      <!-- Demo Credentials -->
      <div class="alert alert-info alert-sm">
        <small><strong>Demo:</strong> admin@clinicos.com / password123</small>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <p class="text-center text-white-50 small mt-4">
    &copy; 2024 ClinicOS. All rights reserved.
  </p>
</div>

<?php include '../../includes/footer.php'; ?>
