<?php
/**
 * Navigation Bar Component
 * Main top navigation bar used across public pages
 */
?>
<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <div class="container-fluid">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
      <div class="nav-logo-mark">
        <span>⚕️</span>
      </div>
      <span class="nav-logo-text">ClinicOS</span>
    </a>

    <!-- Mobile toggle -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Nav links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto align-items-center gap-2">
        <li class="nav-item">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="booking.php">Book Appointment</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="services.php">Services</a>
        </li>
        <li class="nav-item">
          <a class="btn btn-primary btn-sm" href="admin/login.php">Admin Login</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
