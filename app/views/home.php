<!-- ─── HERO ─── -->
<section class="hero position-relative overflow-hidden" id="home">
    <div class="hero-pattern"></div>
    <div class="container position-relative py-5">
        <div class="row align-items-center g-5">
            <!-- Left Content -->
            <div class="col-lg-6 py-5 py-lg-0">
                <div class="hero-badge mb-1">
                    <i class="bi bi-shield-check"></i>
                    <?= htmlspecialchars(
                        $site['hero_badge']
                        ?? 'Trusted by Patients Across Metro Manila'
                    ) ?>
                </div>
                <h1 class="mb-3">
                    <?= htmlspecialchars(
                        $site['hero_title']
                        ?? 'Your Health, Scheduled With Ease.'
                    ) ?>
                </h1>
                <p class="lead mb-3">
                    <?= htmlspecialchars(
                        $site['hero_subtitle']
                        ?? 'Book clinic appointments in minutes — no phone calls, no long queues. Get reminders, manage your records, and connect with trusted healthcare professionals anytime.'
                    ) ?>
                </p>
                <div class="d-flex flex-wrap gap-3 mb-5">
                    <a href="<?= APP_URL ?>/?route=appointment"
                        class="btn-primary-custom text-decoration-none">
                        Book an Appointment
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="<?= APP_URL ?>/#how-it-works"
                        class="btn-outline-custom text-decoration-none">
                        How It Works
                    </a>
                </div>
            </div>
            <!-- Right Image -->
            <div class="col-lg-6 text-center">
                <img src="<?= htmlspecialchars(
                        $site['hero_image']
                        ?? 'https://images.unsplash.com/photo-1584515933487-779824d29309?auto=format&fit=crop&w=900&q=80'
                    ) ?>"
                    class="img-fluid hero-img"
                    alt="Clinic Hero Image">
            </div>
        </div>
    </div>
</section>

<!-- ─── FEATURES ─── -->
<section class="py-5 bg-white" id="features">
    <div class="container py-5">

        <div class="row align-items-center mb-5">
            <div class="m-auto">
                <div class="section-label">Why Choose Clinicos</div>
                <h2 class="display-5 mb-0">Everything you need all in one place.</em></h2>
                <div class=" mt-3 mt-lg-0">
                    <p class="text-muted mb-0" style="line-height:1.75;">
                        Clinicos is built around one goal: making healthcare access simpler, faster, and less stressful for every patient and clinic staff.
                    </p>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#e8f5f1; color:var(--primary);">
                        <i class="bi bi-calendar2-check"></i>
                    </div>
                    <h5 class="mb-2">Online Appointment Booking</h5>
                    <p class="text-muted mb-0" style="font-size:.9rem; line-height:1.7;">
                        Book your visit anytime, anywhere — no phone calls or walk-ins required. Choose your doctor, date, and time in just a few taps.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fef3e2; color:#d97706;">
                        <i class="bi bi-bell-fill"></i>
                    </div>
                    <h5 class="mb-2">Smart Reminders</h5>
                    <p class="text-muted mb-0" style="font-size:.9rem; line-height:1.7;">
                        Receive automatic SMS and email reminders before your appointment so you never miss a scheduled visit.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#fce7e7; color:#dc2626;">
                        <i class="bi bi-heart-pulse-fill"></i>
                    </div>
                    <h5 class="mb-2">Centralized Health Records</h5>
                    <p class="text-muted mb-0" style="font-size:.9rem; line-height:1.7;">
                        Your appointment history, prescriptions, and medical notes are securely stored and accessible whenever you need them.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#eef2ff; color:#4f46e5;">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h5 class="mb-2">Shorter Wait Times</h5>
                    <p class="text-muted mb-0" style="font-size:.9rem; line-height:1.7;">
                        Walk in at your scheduled time — no more sitting in a crowded waiting room. The clinic is ready when you arrive.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#e8f5f1; color:var(--primary);">
                        <i class="bi bi-shield-lock-fill"></i>
                    </div>
                    <h5 class="mb-2">Private & Secure</h5>
                    <p class="text-muted mb-0" style="font-size:.9rem; line-height:1.7;">
                        Your personal health information is protected with role-based access and encrypted storage at every step.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-4">
                <div class="feature-card">
                    <div class="feature-icon" style="background:#f0fdf4; color:#16a34a;">
                        <i class="bi bi-phone-fill"></i>
                    </div>
                    <h5 class="mb-2">Works on Any Device</h5>
                    <p class="text-muted mb-0" style="font-size:.9rem; line-height:1.7;">
                        Access Clinicos on your phone, tablet, or computer. A seamless experience no matter what device you use.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ─── HOW IT WORKS ─── -->
<section class="steps-section py-5" id="how-it-works">
    <div class="container py-5 position-relative">

        <div class="text-center mb-5">
            <div class="section-label justify-content-center" style="color:var(--primary-mid);">
                Simple Process
            </div>
            <h2 class="display-5 text-white mb-3">Book in 4 Easy Steps</h2>
            <p class="mb-0" style="color:rgba(255,255,255,.5); max-width:480px; margin:auto; font-size:.95rem;">
                From choosing a slot to confirming your visit — the whole process takes less than 3 minutes.
            </p>
        </div>

        <div class="row g-4">

            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <div class="step-icon"><i class="bi bi-calendar-week"></i></div>
                    <h5 class="text-white mb-2">Pick a Schedule</h5>
                    <p style="color:rgba(255,255,255,.5); font-size:.875rem; line-height:1.7; margin:0;">
                        Browse available dates and times and select the slot that works best for you.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">02</div>
                    <div class="step-icon"><i class="bi bi-person-lines-fill"></i></div>
                    <h5 class="text-white mb-2">Enter Your Details</h5>
                    <p style="color:rgba(255,255,255,.5); font-size:.875rem; line-height:1.7; margin:0;">
                        Fill in your name, contact information, and reason for the visit through our secure online form.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">03</div>
                    <div class="step-icon"><i class="bi bi-credit-card-2-front"></i></div>
                    <h5 class="text-white mb-2">Pay Consultation Fee</h5>
                    <p style="color:rgba(255,255,255,.5); font-size:.875rem; line-height:1.7; margin:0;">
                        Securely pay your consultation fee online using GCash, Maya, or credit card.
                    </p>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="step-card">
                    <div class="step-number">04</div>
                    <div class="step-icon"><i class="bi bi-check-circle-fill"></i></div>
                    <h5 class="text-white mb-2">Get Confirmation</h5>
                    <p style="color:rgba(255,255,255,.5); font-size:.875rem; line-height:1.7; margin:0;">
                        Receive an instant booking confirmation and appointment reminder via email and SMS.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ─── SERVICES ─── -->
<section class="py-5 bg-white" id="services">
    <div class="container py-5">

        <div class="row align-items-end mb-5">
            <div>
                <div class="section-label">Our Services</div>
                <h2 class="display-5 mb-0">
                    <?= htmlspecialchars($site['services_title'] ?? 'What would you like to book today?') ?>
                </h2>
            </div>
        </div>

        <div class="row g-4" id="serviceList">

            <?php foreach ($services as $service): ?>
                <div class="col-md-6 col-lg-4">

                    <div class="service-card">

                        <img src="<?= htmlspecialchars(
                            $service['image_path']
                                ? APP_URL . '/uploads/' . $service['image_path']
                                : 'https://images.unsplash.com/photo-1550831107-1553da8c8464?auto=format&fit=crop&w=900&q=80'
                        ) ?>"
                        alt="<?= htmlspecialchars($service['title']) ?>">

                        <div class="card-body d-flex flex-column">

    <span class="service-tag">
        <?= htmlspecialchars($service['category'] ?? 'Service') ?>
    </span>

    <h5><?= htmlspecialchars($service['title']) ?></h5>

    <p class="text-muted">
        <?= htmlspecialchars($service['description']) ?>
    </p>

    <!-- PUSH BUTTON TO BOTTOM -->
    <a href="<?= APP_URL ?>/?route=appointment&service_id=<?= $service['id'] ?>"
       class="link-arrow mt-auto text-end d-block">
        Book Now <i class="bi bi-arrow-right"></i>
    </a>

</div>

                    </div>

                </div>
            <?php endforeach; ?>

        </div>

        <!-- Load More -->
        <?php if ($serviceCount > count($services)): ?>
            <div class="text-center mt-4">
                <button id="loadMoreServices" class="btn btn-primary">
                    Load more
                </button>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- <section class="container py-5">
    <div class="row g-4">
        <div class="col-lg-6">
            <h2>Testimonials</h2>
            <?php foreach ($testimonials as $testimonial): ?>
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <p class="mb-2">"<?= htmlspecialchars($testimonial['message']) ?>"</p>
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width:38px;height:38px;"><?= strtoupper($testimonial['name'][0] ?? 'C') ?></div>
                            <div>
                                <strong><?= htmlspecialchars($testimonial['name']) ?></strong>
                                <div class="text-muted small"><?= htmlspecialchars($testimonial['position'] ?? 'Patient') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="col-lg-6">
            <h2>FAQ</h2>
            <div class="accordion" id="faqAccordion">
                <?php $faqs = json_decode($site['faq_items'] ?? '[]', true) ?: [['question'=>'How do I book?','answer'=>'Use our booking form and confirm with email OTP.']]; ?>
                <?php foreach ($faqs as $index => $item): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="faqHeading<?= $index ?>">
                            <button class="accordion-button <?= $index ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapse<?= $index ?>"><?= htmlspecialchars($item['question']) ?></button>
                        </h2>
                        <div id="faqCollapse<?= $index ?>" class="accordion-collapse collapse <?= $index ? '' : 'show' ?>" data-bs-parent="#faqAccordion">
                            <div class="accordion-body"><?= htmlspecialchars($item['answer']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section> -->


<!-- ─── CONTACT ─── -->
<section class="contact-section py-5" id="contact">
    <div class="container py-5">

        <div class="mb-5">
            <div class="section-label">Contact Us</div>

            <h2 class="display-5 mb-2">
                <?= htmlspecialchars($site['contact_title'] ?? "We're here to help.") ?>
            </h2>

            <p class="text-muted mb-0">
                <?= htmlspecialchars($site['contact_text'] ?? 'Have a question? Reach out and our team will get back to you promptly.') ?>
            </p>
        </div>

        <div class="row g-4">

            <!-- Contact Info -->
            <div class="col-lg-5">

                <div class="d-flex flex-column gap-3 mb-4">

                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>

                        <div>
                            <small>Clinic Address</small>

                            <strong>
                                <?= htmlspecialchars($site['clinic_address'] ?? 'No address available') ?>
                            </strong>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-telephone-fill"></i>
                        </div>

                        <div>
                            <small>Phone Number</small>

                            <strong>
                                <?= htmlspecialchars($site['contact_phone'] ?? 'No phone number') ?>
                            </strong>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-envelope-fill"></i>
                        </div>

                        <div>
                            <small>Email Address</small>

                            <strong>
                                <?= htmlspecialchars($site['contact_email'] ?? 'No email available') ?>
                            </strong>
                        </div>
                    </div>

                    <div class="contact-info-card">
                        <div class="contact-info-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>

                        <div>
                            <small>Clinic Hours</small>

                            <strong>
                                <?= htmlspecialchars($site['opening_hours'] ?? 'Clinic schedule unavailable') ?>
                            </strong>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Contact Form -->
            <div class="col-lg-7">

                <div class="bg-white border rounded-4 p-4 p-lg-5"
                    style="border-color:var(--border) !important;">

                    <h4 class="mb-4">Send a Message</h4>

                    <form method="POST" action="<?= APP_URL ?>/?route=contact/send">

                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Your Name</label>

                                <input type="text"
                                    name="name"
                                    class="form-control"
                                    placeholder="Juan Dela Cruz"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="you@email.com"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Subject</label>

                                <input type="text"
                                    name="subject"
                                    class="form-control"
                                    placeholder="Appointment Inquiry"
                                    required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Message</label>

                                <textarea class="form-control"
                                    name="message"
                                    rows="5"
                                    placeholder="Write your message here..."
                                    required></textarea>
                            </div>

                            <div class="col-12">

                                <button type="submit"
                                    class="btn w-100 py-3 fw-semibold rounded-3 text-white"
                                    style="background:var(--primary); font-family:'DM Sans',sans-serif;">

                                    <i class="bi bi-send-fill me-2"></i>
                                    Send Message

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>

<!-- ─── CTA ─── -->
<section class="cta-section py-5">
    <div class="container py-4 text-center text-white position-relative">
        <p class="mb-2" style="font-size:.8rem; letter-spacing:.12em; text-transform:uppercase; color:rgba(255,255,255,.55);">
            Get Started Today
        </p>
        <h2 class="display-6 fw-normal mb-3 text-white" style="font-family:'DM Serif Display',serif;">
            Ready to book your appointment?
        </h2>
        <p class="mb-4" style="color:rgba(255,255,255,.65); max-width:460px; margin:auto;">
            Join thousands of patients who manage their clinic visits online — easily, securely, and on their own schedule.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <a href="#services" class="btn btn-light px-4 py-3 fw-semibold rounded-pill text-decoration-none"
                style="color:var(--primary); font-family:'DM Sans',sans-serif;">
                Book an Appointment <i class="bi bi-arrow-right ms-1"></i>
            </a>
            <a href="#contact" class="btn-outline-custom text-decoration-none">
                Contact Us
            </a>
        </div>
    </div>
</section>
