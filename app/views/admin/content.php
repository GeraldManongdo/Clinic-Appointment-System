<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

    <div>
        <h4 class="mb-1">Website Content</h4>

        <nav>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Content Settings</li>
            </ol>
        </nav>
    </div>

    <button class="btn btn-primary d-flex align-items-center gap-2"
        onclick="saveSettings('contentForm')">
        <i class="bi bi-save"></i>
        Save Changes
    </button>

</div>

<form onsubmit="event.preventDefault(); saveSettings('contentForm');" id="contentForm">

    <input type="hidden" name="action" value="save_setting">

    <!-- BASIC INFO -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <strong><i class="bi bi-building me-1"></i> Clinic Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Clinic Name</label>
                    <input class="form-control"
                        name="clinic_name"
                        value="<?= htmlspecialchars($site['clinic_name'] ?? 'Clinicos') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contact Text</label>
                    <input class="form-control"
                        name="contact_text"
                        value="<?= htmlspecialchars($site['contact_text'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Clinic Address</label>
                    <input class="form-control"
                        name="clinic_address"
                        value="<?= htmlspecialchars($site['clinic_address'] ?? '') ?>">
                </div>

            </div>

        </div>

    </div>

    <!-- HERO SECTION -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <strong><i class="bi bi-stars me-1"></i> Hero Section</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-12">
                    <label class="form-label">Hero Title</label>
                    <input class="form-control"
                        name="hero_title"
                        value="<?= htmlspecialchars($site['hero_title'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Hero Subtitle</label>
                    <textarea class="form-control" name="hero_subtitle" rows="3"><?= htmlspecialchars($site['hero_subtitle'] ?? '') ?></textarea>
                </div>

            </div>

        </div>

    </div>

    <!-- ABOUT / DOCTOR -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <strong><i class="bi bi-person-badge me-1"></i> Doctor Information</strong>
        </div>

        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Doctor Name</label>
                    <input class="form-control"
                        name="doctor_name"
                        value="<?= htmlspecialchars($site['doctor_name'] ?? '') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Doctor Specialty</label>
                    <input class="form-control"
                        name="doctor_specialty"
                        value="<?= htmlspecialchars($site['doctor_specialty'] ?? '') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Doctor Bio</label>
                    <textarea class="form-control" name="doctor_bio" rows="4"><?= htmlspecialchars($site['doctor_bio'] ?? '') ?></textarea>
                </div>

            </div>

        </div>

    </div>

    <!-- ABOUT TEXT -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <strong><i class="bi bi-journal-text me-1"></i> About Section</strong>
        </div>

        <div class="card-body">

            <label class="form-label">About Text</label>
            <textarea class="form-control" name="about_text" rows="4"><?= htmlspecialchars($site['about_text'] ?? '') ?></textarea>

        </div>

    </div>

    <!-- FAQ -->
    <div class="card border-0 shadow-sm mb-4">

        <div class="card-header bg-white">
            <strong><i class="bi bi-question-circle me-1"></i> FAQ Settings</strong>
        </div>

        <div class="card-body">

            <label class="form-label">FAQ Items (JSON Format)</label>

            <textarea class="form-control font-monospace" name="faq_items" rows="6"><?= htmlspecialchars(
                $site['faq_items'] ?? '[{"question":"How do I book?","answer":"Complete the form and verify with OTP."}]'
            ) ?></textarea>

            <small class="text-muted d-block mt-2">
                Use JSON format. Each item must contain <code>question</code> and <code>answer</code>.
            </small>

        </div>

    </div>

</form>