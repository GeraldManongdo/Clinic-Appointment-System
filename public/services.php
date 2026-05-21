<?php
/**
 * ClinicOS - Services Page
 * Display available services
 */
require_once '../includes/config.php';

$page_title = 'Our Services';
?>
<?php include '../includes/header.php'; ?>

<!-- Navigation -->
<?php include '../components/navbar.php'; ?>

<main class="main">
  <!-- Page Header -->
  <section class="py-4 bg-light border-bottom">
    <div class="container">
      <h1 class="h2 mb-2">Our Services</h1>
      <p class="text-muted">Comprehensive healthcare services tailored to your needs.</p>
    </div>
  </section>

  <!-- Services Grid -->
  <section class="py-5">
    <div class="container">
      <div class="row g-4">
        <!-- General Checkup -->
        <div class="col-md-6 col-lg-4">
          <div class="card service-card h-100">
            <div class="service-img-wrap bg-primary-pale">
              <i class="bi bi-stethoscope" style="font-size: 3rem; color: var(--teal);"></i>
            </div>
            <div class="service-body">
              <span class="badge bg-primary mb-2">General Medicine</span>
              <h5 class="card-title">General Checkup</h5>
              <p class="card-text text-muted">Comprehensive health screening, vital signs monitoring, and preventive care advice.</p>
              <p class="fw-bold text-primary">$50 per visit</p>
              <a href="booking.php" class="btn btn-outline-primary btn-sm w-100">Book Now</a>
            </div>
          </div>
        </div>

        <!-- Dental Care -->
        <div class="col-md-6 col-lg-4">
          <div class="card service-card h-100">
            <div class="service-img-wrap bg-success-pale">
              <i class="bi bi-tooth" style="font-size: 3rem; color: var(--text-body);"></i>
            </div>
            <div class="service-body">
              <span class="badge bg-success mb-2">Dental</span>
              <h5 class="card-title">Dental Care</h5>
              <p class="card-text text-muted">Professional cleaning, cavity treatment, and cosmetic dentistry services.</p>
              <p class="fw-bold text-success">$75 per visit</p>
              <a href="booking.php" class="btn btn-outline-success btn-sm w-100">Book Now</a>
            </div>
          </div>
        </div>

        <!-- Eye Care -->
        <div class="col-md-6 col-lg-4">
          <div class="card service-card h-100">
            <div class="service-img-wrap bg-info-pale">
              <i class="bi bi-eye" style="font-size: 3rem; color: var(--text-body);"></i>
            </div>
            <div class="service-body">
              <span class="badge bg-info mb-2">Optometry</span>
              <h5 class="card-title">Eye Care</h5>
              <p class="card-text text-muted">Vision screening, eye exams, and prescription assistance.</p>
              <p class="fw-bold text-info">$60 per visit</p>
              <a href="booking.php" class="btn btn-outline-info btn-sm w-100">Book Now</a>
            </div>
          </div>
        </div>

        <!-- Consultation -->
        <div class="col-md-6 col-lg-4">
          <div class="card service-card h-100">
            <div class="service-img-wrap bg-warning-pale">
              <i class="bi bi-person-raised-hand" style="font-size: 3rem; color: var(--text-body);"></i>
            </div>
            <div class="service-body">
              <span class="badge bg-warning mb-2">Specialist</span>
              <h5 class="card-title">Expert Consultation</h5>
              <p class="card-text text-muted">One-on-one consultation with experienced medical specialists.</p>
              <p class="fw-bold text-warning">$100 per visit</p>
              <a href="booking.php" class="btn btn-outline-warning btn-sm w-100">Book Now</a>
            </div>
          </div>
        </div>

        <!-- Lab Tests -->
        <div class="col-md-6 col-lg-4">
          <div class="card service-card h-100">
            <div class="service-img-wrap bg-danger-pale">
              <i class="bi bi-droplet" style="font-size: 3rem; color: var(--text-body);"></i>
            </div>
            <div class="service-body">
              <span class="badge bg-danger mb-2">Laboratory</span>
              <h5 class="card-title">Lab Tests</h5>
              <p class="card-text text-muted">Blood tests, diagnostic screenings, and lab analysis services.</p>
              <p class="fw-bold text-danger">$30-150 per test</p>
              <a href="booking.php" class="btn btn-outline-danger btn-sm w-100">Book Now</a>
            </div>
          </div>
        </div>

        <!-- Vaccination -->
        <div class="col-md-6 col-lg-4">
          <div class="card service-card h-100">
            <div class="service-img-wrap bg-secondary-pale">
              <i class=\"bi bi-syringe\" style=\"font-size: 3rem; color: var(--text-body);\"></i>
            </div>
            <div class="service-body">
              <span class="badge bg-secondary mb-2">Immunization</span>
              <h5 class="card-title">Vaccination</h5>
              <p class="card-text text-muted">Routine immunizations and vaccination schedules for all ages.</p>
              <p class="fw-bold text-secondary">$25-75 per dose</p>
              <a href="booking.php" class="btn btn-outline-secondary btn-sm w-100">Book Now</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Service Features -->
  <section class="py-5 bg-light">
    <div class="container">
      <h2 class="h3 mb-4">Why Choose Our Services?</h2>
      <div class="row g-4">
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon-wrap"><i class="bi bi-clock-history"></i></div>
            <h5 class="feature-card-title">Quick Appointments</h5>
            <p class="feature-card-desc">Same-day or next-day booking available</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon-wrap"><i class="bi bi-person-check"></i></div>
            <h5 class="feature-card-title">Expert Staff</h5>
            <p class="feature-card-desc">Qualified and experienced professionals</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class="feature-icon-wrap"><i class="bi bi-wallet2"></i></div>
            <h5 class="feature-card-title">Affordable Prices</h5>
            <p class="feature-card-desc">Competitive rates and flexible payment</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="feature-card">
            <div class=\"feature-icon-wrap\"><i class=\"bi bi-building\"></i></div>
            <h5 class="feature-card-title">Modern Facility</h5>
            <p class="feature-card-desc">State-of-the-art equipment and technology</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Footer -->
<?php include '../components/footer.php'; ?>

<?php include '../includes/footer.php'; ?>
