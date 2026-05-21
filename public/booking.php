<?php
/**
 * ClinicOS - Booking Page
 * Appointment booking form
 */
require_once '../includes/config.php';

$page_title = 'Book Your Appointment';
$page_css = 'booking.css';
$page_js = 'booking.js';
?>
<?php include '../includes/header.php'; ?>

<!-- Navigation -->
<?php include '../components/navbar.php'; ?>

<main class="main">
  <!-- Page Header -->
  <section class="py-4 bg-light border-bottom">
    <div class="container">
      <h1 class="h2 mb-2">Book Your Appointment</h1>
      <p class="text-muted">Schedule your medical appointment in just a few steps.</p>
    </div>
  </section>

  <!-- Booking Form -->
  <section class="py-5">
    <div class="container">
      <div class="row">
        <div class="col-lg-8">
          <div class="card booking-form-section">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0">Appointment Details</h5>
            </div>
            <div class="card-body">
              <form id="bookingForm" method="POST" action="./api/booking_api.php">
                <!-- Service Selection -->
                <div class="booking-form-group">
                  <label class="booking-form-label">Select Service *</label>
                  <select class="booking-form-select" name="service_id" required>
                    <option value="">-- Choose a service --</option>
                    <option value="1">General Checkup - $50</option>
                    <option value="2">Dental Care - $75</option>
                    <option value="3">Eye Care - $60</option>
                    <option value="4">Consultation - $40</option>
                  </select>
                </div>

                <!-- Doctor Selection -->
                <div class="booking-form-group">
                  <label class="booking-form-label">Select Doctor *</label>
                  <select class="booking-form-select" name="doctor_id" required>
                    <option value="">-- Choose a doctor --</option>
                    <option value="1">Dr. John Smith, MD</option>
                    <option value="2">Dr. Sarah Johnson, DDS</option>
                    <option value="3">Dr. Michael Brown, OD</option>
                  </select>
                </div>

                <!-- Date Selection -->
                <div class="row">
                  <div class="col-md-6">
                    <div class="booking-form-group">
                      <label class="booking-form-label">Preferred Date *</label>
                      <input type="date" class="booking-form-input" name="appointment_date" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="booking-form-group">
                      <label class="booking-form-label">Preferred Time *</label>
                      <select class="booking-form-select" name="appointment_time" required>
                        <option value="">-- Select time --</option>
                        <option value="09:00">09:00 AM</option>
                        <option value="10:00">10:00 AM</option>
                        <option value="11:00">11:00 AM</option>
                        <option value="14:00">02:00 PM</option>
                        <option value="15:00">03:00 PM</option>
                        <option value="16:00">04:00 PM</option>
                      </select>
                    </div>
                  </div>
                </div>

                <!-- Patient Information -->
                <div class="booking-form-title mt-4 mb-3">Patient Information</div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="booking-form-group">
                      <label class="booking-form-label">Full Name *</label>
                      <input type="text" class="booking-form-input" name="patient_name" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="booking-form-group">
                      <label class="booking-form-label">Email *</label>
                      <input type="email" class="booking-form-input" name="patient_email" required>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class="booking-form-group">
                      <label class="booking-form-label">Phone Number *</label>
                      <input type="tel" class="booking-form-input" name="patient_phone" required>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="booking-form-group">
                      <label class="booking-form-label">Date of Birth *</label>
                      <input type="date" class="booking-form-input" name="patient_dob" required>
                    </div>
                  </div>
                </div>

                <!-- Notes -->
                <div class="booking-form-group">
                  <label class="booking-form-label">Additional Notes</label>
                  <textarea class="booking-form-input" name="notes" rows="4" placeholder="Any specific concerns or questions?"></textarea>
                </div>

                <!-- Terms -->
                <div class="form-check mb-4">
                  <input class="form-check-input" type="checkbox" id="termsCheck" required>
                  <label class="form-check-label" for="termsCheck">
                    I agree to the terms and privacy policy
                  </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary btn-lg w-100">
                  <i class="bi bi-check-circle"></i>
                  <span>Confirm Appointment</span>
                </button>
              </form>
            </div>
          </div>
        </div>

        <!-- Sidebar Information -->
        <div class="col-lg-4">
          <div class="card mb-4">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0"><i class="bi bi-lightbulb"></i> Tips</h5>
            </div>
            <div class="card-body">
              <ul class="list-unstyled">
                <li class="mb-2"><i class="bi bi-check-circle"></i> Please arrive 10 minutes early</li>
                <li class="mb-2"><i class="bi bi-check-circle"></i> Bring your insurance information</li>
                <li class="mb-2"><i class="bi bi-check-circle"></i> Bring a valid ID</li>
                <li><i class="bi bi-check-circle"></i> Fill out patient forms if first visit</li>
              </ul>
            </div>
          </div>

          <div class="card">
            <div class="card-header bg-light border-bottom">
              <h5 class="mb-0"><i class="bi bi-telephone"></i> Need Help?</h5>
            </div>
            <div class="card-body">
              <p class="text-muted mb-3">Contact us if you have any questions:</p>
              <p><strong><i class="bi bi-envelope"></i> Email:</strong> info@clinicos.com</p>
              <p><strong><i class="bi bi-telephone"></i> Phone:</strong> +1 (555) 123-4567</p>
              <p class="text-muted small">Available Monday - Friday, 9 AM - 6 PM</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>

<!-- Footer -->
<?php include '../components/footer.php'; ?>

<?php include '../includes/footer.php'; ?>
