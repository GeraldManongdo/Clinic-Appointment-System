<?php
/**
 * Admin Sidebar Component
 * Left sidebar for admin dashboard
 */
$current_page = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar">
  <!-- Logo -->
  <div class="sidebar-logo">
    <div class="sidebar-logo-mark">
      <div class="sidebar-logo-icon">
        <i class="bi bi-hospital"></i>
      </div>
      <h1>ClinicOS</h1>
    </div>
    <p>Admin Panel</p>
  </div>

  <!-- Navigation -->
  <nav class="sidebar-nav flex-grow-1">
    <!-- Main Section -->
    <div class="nav-section">Menu</div>

    <a href="dashboard.php" class="nav-item <?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-speedometer2"></i>
      <span>Dashboard</span>
    </a>

    <a href="appointments.php" class="nav-item <?php echo $current_page === 'appointments.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-calendar-check"></i>
      <span>Appointments</span>
      <span class="nav-badge">3</span>
    </a>

    <a href="patients.php" class="nav-item <?php echo $current_page === 'patients.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-people"></i>
      <span>Patients</span>
    </a>

    <a href="payments.php" class="nav-item <?php echo $current_page === 'payments.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-credit-card"></i>
      <span>Payments</span>
    </a>

    <!-- Settings Section -->
    <div class="nav-section">Website Management</div>

    <a href="homepage.php" class="nav-item <?php echo $current_page === 'homepage.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-globe"></i>
      <span>Homepage Content</span>
    </a>

    <a href="theme.php" class="nav-item <?php echo $current_page === 'theme.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-palette"></i>
      <span>Theme & Branding</span>
    </a>

    <!-- Settings Section -->
    <div class="nav-section">Settings</div>

    <a href="calendar.php" class="nav-item <?php echo $current_page === 'calendar.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-gear"></i>
      <span>Calendar</span>
    </a>

    <a href="services.php" class="nav-item <?php echo $current_page === 'services.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-hospital"></i>
      <span>Services</span>
    </a>

    <a href="customizer.php" class="nav-item <?php echo $current_page === 'customizer.php' ? 'active' : ''; ?>">
      <i class="nav-icon bi bi-palette"></i>
      <span>Customizer</span>
    </a>
  </nav>

  <!-- Footer -->
  <div class="sidebar-footer">
    <button class="btn-logout" onclick="clinicNav.logout()">
      <i class="bi bi-box-arrow-right"></i>
      <span>Logout</span>
    </button>
  </div>
</aside>
