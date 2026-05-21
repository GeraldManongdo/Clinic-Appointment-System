<?php
/**
 * ClinicOS - Homepage
 * Main landing page with database-driven content
 */
require_once '../includes/config.php';

// Get database connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASSWORD
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Fallback if database connection fails
    $pdo = null;
}

// Fetch hero section
$hero = [];
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_hero WHERE id = 1 LIMIT 1");
        $stmt->execute();
        $hero = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        // Use defaults if query fails
    }
}

// Set defaults for hero
$hero_pill_text = $hero['hero_pill_text'] ?? 'Welcome to ClinicOS';
$hero_title = $hero['hero_title'] ?? 'Your <em>Trusted</em> Medical <span class="line-accent">Appointment System</span>';
$hero_subtitle = $hero['hero_subtitle'] ?? 'Streamline your healthcare appointments with ClinicOS. Easy booking, instant confirmations, and seamless clinic management all in one platform.';
$cta_button_text = $hero['cta_button_text'] ?? 'Book Appointment';
$cta_button_link = $hero['cta_button_link'] ?? 'booking.php';
$secondary_button_text = $hero['secondary_button_text'] ?? 'Learn More';
$secondary_button_link = $hero['secondary_button_link'] ?? '#features';
$stat1_number = $hero['stat1_number'] ?? '2.5K+';
$stat1_label = $hero['stat1_label'] ?? 'Happy Patients';
$stat2_number = $hero['stat2_number'] ?? '98%';
$stat2_label = $hero['stat2_label'] ?? 'Satisfaction Rate';

// Fetch why choose us features
$features = [];
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_features WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6");
        $stmt->execute();
        $features = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        // Use empty array if query fails
    }
}

// Fetch services
$services = [];
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_services WHERE is_active = 1 ORDER BY sort_order ASC");
        $stmt->execute();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        // Use empty array if query fails
    }
}

// Fetch ready to book section
$ready_to_book = [];
if ($pdo) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM site_sections WHERE section_key = 'ready_to_book' LIMIT 1");
        $stmt->execute();
        $ready_to_book = $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        // Use empty array if query fails
    }
}

$ready_to_book_title = $ready_to_book['title'] ?? 'Ready to Book Your Appointment?';
$ready_to_book_subtitle = $ready_to_book['subtitle'] ?? 'Ready to Get Started?';
$ready_to_book_tagline = $ready_to_book['tagline'] ?? 'Join thousands of satisfied patients using ClinicOS.';

$page_title = 'Your Trusted Medical Appointment System';
?>
<?php include '../includes/header.php'; ?>

<!-- Load Theme Settings -->
<link rel="stylesheet" href="api/theme_api.php?action=generate_css">

<!-- Navigation -->
<?php include '../components/navbar.php'; ?>

<main class="main">
  <!-- Hero Section -->
  <section class="hero">
    <div class="hero-grid"></div>
    <div class="hero-inner container">
      <div class="hero-content">
        <div class="hero-pill">
          <span class="hero-pill-dot"></span>
          <span><?php echo htmlspecialchars($hero_pill_text); ?></span>
        </div>
        
        <h1 class="hero-title">
          <?php echo $hero_title; ?>
        </h1>
        
        <p class="hero-desc">
          <?php echo htmlspecialchars($hero_subtitle); ?>
        </p>
        
        <div class="hero-cta-group">
          <a href="<?php echo htmlspecialchars($cta_button_link); ?>" class="btn btn-primary">
            <i class="bi bi-calendar-check"></i>
            <span><?php echo htmlspecialchars($cta_button_text); ?></span>
          </a>
          <a href="<?php echo htmlspecialchars($secondary_button_link); ?>" class="btn btn-outline">
            <i class="bi bi-arrow-down-circle"></i>
            <span><?php echo htmlspecialchars($secondary_button_text); ?></span>
          </a>
        </div>
        
        <div class="hero-stats">
          <div>
            <div class="hero-stat-num"><?php echo htmlspecialchars($stat1_number); ?></div>
            <div class="hero-stat-label"><?php echo htmlspecialchars($stat1_label); ?></div>
          </div>
          <div class="hero-stat-divider"></div>
          <div>
            <div class="hero-stat-num"><?php echo htmlspecialchars($stat2_number); ?></div>
            <div class="hero-stat-label"><?php echo htmlspecialchars($stat2_label); ?></div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Trust Bar -->
  <section class="trust-bar">
    <div class="trust-item">
      <i class="bi bi-check-circle"></i>
      <span>HIPAA Compliant</span>
    </div>
    <div class="trust-item">
      <i class="bi bi-shield-lock"></i>
      <span>Secure & Encrypted</span>
    </div>
    <div class="trust-item">
      <i class="bi bi-lightning-charge"></i>
      <span>Fast & Reliable</span>
    </div>
    <div class="trust-item">
      <i class="bi bi-globe"></i>
      <span>Available 24/7</span>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="py-5 bg-light">
    <div class="container">
      <div class="section-header text-center mb-5">
        <div class="eyebrow">Why Choose Us?</div>
        <h2 class="section-title">Why Choose This Clinic</h2>
        <p class="section-desc">Experience the best healthcare services with our dedicated team.</p>
      </div>

      <div class="row g-4">
        <?php if (!empty($features)): ?>
          <?php foreach ($features as $feature): ?>
            <div class="col-md-6 col-lg-4">
              <div class="feature-card">
                <div class="feature-icon-wrap" style="font-size: 2em;"><?php echo htmlspecialchars($feature['icon']); ?></div>
                <h5 class="feature-card-title"><?php echo htmlspecialchars($feature['title']); ?></h5>
                <p class="feature-card-desc"><?php echo htmlspecialchars($feature['description']); ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted">
            <p>No features configured yet. <a href="admin/homepage.php">Add features from admin panel</a></p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- How It Works -->
  <section class="py-5 bg-dark text-white">
    <div class="container">
      <div class="section-header text-center mb-5">
        <div class="eyebrow">Simple Process</div>
        <h2 class="section-title text-white">How It Works</h2>
        <p class="section-desc text-white-50">Get your appointment in 4 easy steps.</p>
      </div>

      <div class="steps-track">
        <div class="step-item">
          <div class="step-num-wrap">
            <i class="bi bi-search step-icon-big"></i>
            <span class="step-num-badge">1</span>
          </div>
          <h5 class="step-title">Find Service</h5>
          <p class="step-desc">Browse available services and doctors</p>
        </div>

        <div class="step-item">
          <div class="step-num-wrap">
            <i class="bi bi-calendar-event step-icon-big"></i>
            <span class="step-num-badge">2</span>
          </div>
          <h5 class="step-title">Choose Date</h5>
          <p class="step-desc">Pick your preferred appointment date</p>
        </div>

        <div class="step-item">
          <div class="step-num-wrap">
            <i class="bi bi-pencil-square step-icon-big"></i>
            <span class="step-num-badge">3</span>
          </div>
          <h5 class="step-title">Enter Details</h5>
          <p class="step-desc">Provide your personal information</p>
        </div>

        <div class="step-item">
          <div class="step-num-wrap">
            <i class="bi bi-check-circle step-icon-big"></i>
            <span class="step-num-badge">4</span>
          </div>
          <h5 class="step-title">Confirm</h5>
          <p class="step-desc">Complete your appointment booking</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Showcase -->
  <section class="py-5">
    <div class="container">
      <div class="section-header text-center mb-5">
        <div class="eyebrow">Our Services</div>
        <h2 class="section-title">Healthcare Services</h2>
        <p class="section-desc">We offer a wide range of healthcare services to meet your needs.</p>
      </div>

      <div class="row g-4">
        <?php if (!empty($services)): ?>
          <?php foreach (array_slice($services, 0, 6) as $service): ?>
            <div class="col-md-6 col-lg-4">
              <div class="card service-card">
                <div class="service-img-wrap">
                  <span class="service-icon-ph" style="font-size: 2.5em;"><?php echo htmlspecialchars($service['icon']); ?></span>
                </div>
                <div class="service-body">
                  <span class="service-badge-pill"><?php echo htmlspecialchars($service['badge']); ?></span>
                  <h5 class="service-card-title"><?php echo htmlspecialchars($service['title']); ?></h5>
                  <p class="service-card-desc"><?php echo htmlspecialchars($service['description']); ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12 text-center text-muted">
            <p>No services configured yet. <a href="admin/homepage.php">Add services from admin panel</a></p>
          </div>
        <?php endif; ?>
      </div>

      <div class="text-center mt-5">
        <a href="services.php" class="btn btn-primary">
          View All Services →
        </a>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="section-title mb-3"><?php echo htmlspecialchars($ready_to_book_title); ?></h2>
      <p class="section-desc mb-4"><?php echo htmlspecialchars($ready_to_book_tagline); ?></p>
      <a href="booking.php" class="btn btn-primary btn-lg">
        Start Booking Now
      </a>
    </div>
  </section>
</main>

<!-- Footer -->
<?php include '../components/footer.php'; ?>

<?php include '../includes/footer.php'; ?>
