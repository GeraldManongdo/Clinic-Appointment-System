<?php
require_once '../../includes/config.php';
$page_title = "Homepage Content Management";
require_once '../../includes/header.php';
?>

<!-- Main Layout -->
<div class="layout">
  <?php require_once '../../components/sidebar-admin.php'; ?>

  <main class="admin-main">
    <?php require_once '../../components/topbar.php'; ?>

    <div class="admin-content">
      <!-- Navigation Tabs -->
      <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-bs-toggle="tab" href="#hero-tab" role="tab">Hero Section</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#features-tab" role="tab">Why Choose Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#services-tab" role="tab">Services</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#sections-tab" role="tab">Other Sections</a>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content">

        <!-- ============================================================ -->
        <!-- HERO SECTION TAB -->
        <!-- ============================================================ -->
        <div class="tab-pane fade show active" id="hero-tab" role="tabpanel">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Hero Section Editor</h5>
              <small class="text-muted">Edit the main hero section that appears at the top of your homepage</small>
            </div>
            <div class="card-body">
              <form id="heroForm" class="row g-3">
                <input type="hidden" name="action" value="update_hero">

                <div class="col-12">
                  <label class="form-label">Pill Text</label>
                  <input type="text" class="form-control" id="hero_pill_text" name="hero_pill_text" placeholder="e.g., Welcome to ClinicOS">
                </div>

                <div class="col-12">
                  <label class="form-label">Main Title (HTML allowed)</label>
                  <textarea class="form-control" id="hero_title" name="hero_title" rows="3" placeholder="Your &lt;em&gt;Trusted&lt;/em&gt; Medical..."></textarea>
                  <small class="text-muted">You can use HTML tags like &lt;em&gt;, &lt;strong&gt;, &lt;span class="line-accent"&gt;</small>
                </div>

                <div class="col-12">
                  <label class="form-label">Subtitle</label>
                  <textarea class="form-control" id="hero_subtitle" name="hero_subtitle" rows="3"></textarea>
                </div>

                <div class="col-md-6">
                  <label class="form-label">Primary CTA Button Text</label>
                  <input type="text" class="form-control" id="cta_button_text" name="cta_button_text" placeholder="Book Appointment">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Primary CTA Button Link</label>
                  <input type="text" class="form-control" id="cta_button_link" name="cta_button_link" placeholder="booking.php">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Secondary Button Text</label>
                  <input type="text" class="form-control" id="secondary_button_text" name="secondary_button_text" placeholder="Learn More">
                </div>

                <div class="col-md-6">
                  <label class="form-label">Secondary Button Link</label>
                  <input type="text" class="form-control" id="secondary_button_link" name="secondary_button_link" placeholder="#features">
                </div>

                <hr class="col-12">

                <div class="col-md-3">
                  <label class="form-label">Stat 1 Number</label>
                  <input type="text" class="form-control" id="stat1_number" name="stat1_number" placeholder="2.5K+">
                </div>

                <div class="col-md-3">
                  <label class="form-label">Stat 1 Label</label>
                  <input type="text" class="form-control" id="stat1_label" name="stat1_label" placeholder="Happy Patients">
                </div>

                <div class="col-md-3">
                  <label class="form-label">Stat 2 Number</label>
                  <input type="text" class="form-control" id="stat2_number" name="stat2_number" placeholder="98%">
                </div>

                <div class="col-md-3">
                  <label class="form-label">Stat 2 Label</label>
                  <input type="text" class="form-control" id="stat2_label" name="stat2_label" placeholder="Satisfaction Rate">
                </div>

                <div class="col-12">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Save Changes
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- WHY CHOOSE US FEATURES TAB -->
        <!-- ============================================================ -->
        <div class="tab-pane fade" id="features-tab" role="tabpanel">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0">Why Choose Us - Features (Max 6)</h5>
                <small class="text-muted">Add up to 6 features/cards that appear in the "Why Choose Us" section</small>
              </div>
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#featureModal">
                <i class="bi bi-plus-circle"></i> Add Feature
              </button>
            </div>
            <div class="card-body">
              <div id="featuresContainer" class="row g-3">
                <!-- Features will be loaded here -->
              </div>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- SERVICES TAB -->
        <!-- ============================================================ -->
        <div class="tab-pane fade" id="services-tab" role="tabpanel">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-0">Services</h5>
                <small class="text-muted">Manage healthcare services offered by your clinic</small>
              </div>
              <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#serviceModal">
                <i class="bi bi-plus-circle"></i> Add Service
              </button>
            </div>
            <div class="card-body">
              <div id="servicesContainer" class="row g-3">
                <!-- Services will be loaded here -->
              </div>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- OTHER SECTIONS TAB -->
        <!-- ============================================================ -->
        <div class="tab-pane fade" id="sections-tab" role="tabpanel">
          <div class="row g-3">
            <!-- Ready to Book Section -->
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="mb-0">Ready to Book? Section</h5>
                  <small class="text-muted">CTA section at the bottom of the page</small>
                </div>
                <div class="card-body">
                  <form id="readyToBookForm" class="row g-3">
                    <input type="hidden" name="action" value="update_section">
                    <input type="hidden" name="section_key" value="ready_to_book">

                    <div class="col-md-6">
                      <label class="form-label">Title</label>
                      <input type="text" class="form-control" id="rtb_title" name="title" placeholder="Ready to Book?">
                    </div>

                    <div class="col-md-6">
                      <label class="form-label">Subtitle</label>
                      <input type="text" class="form-control" id="rtb_subtitle" name="subtitle" placeholder="Ready to Get Started?">
                    </div>

                    <div class="col-12">
                      <label class="form-label">Tagline</label>
                      <textarea class="form-control" id="rtb_tagline" name="tagline" rows="2" placeholder="Book your appointment now..."></textarea>
                    </div>

                    <div class="col-12">
                      <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Changes
                      </button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>
</div>

<!-- ============================================================ -->
<!-- MODALS -->
<!-- ============================================================ -->

<!-- Feature Modal -->
<div class="modal fade" id="featureModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Feature</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="featureForm" class="modal-body">
        <input type="hidden" id="feature_id" name="id" value="">
        <input type="hidden" name="action" value="add_feature">

        <div class="mb-3">
          <label class="form-label">Icon (Emoji or Unicode)</label>
          <input type="text" class="form-control" id="feature_icon" name="icon" placeholder="⭐ or 👨‍⚕️" maxlength="10">
        </div>

        <div class="mb-3">
          <label class="form-label">Title *</label>
          <input type="text" class="form-control" id="feature_title" name="title" placeholder="e.g., Expert Medical Team" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description *</label>
          <textarea class="form-control" id="feature_description" name="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Sort Order</label>
          <input type="number" class="form-control" id="feature_sort_order" name="sort_order" value="0">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Save Feature
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Service Modal -->
<div class="modal fade" id="serviceModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add Service</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form id="serviceForm" class="modal-body">
        <input type="hidden" id="service_id" name="id" value="">
        <input type="hidden" name="action" value="add_service">

        <div class="mb-3">
          <label class="form-label">Icon (Emoji)</label>
          <input type="text" class="form-control" id="service_icon" name="icon" placeholder="👨‍⚕️" maxlength="10" value="🏥">
        </div>

        <div class="mb-3">
          <label class="form-label">Badge/Category</label>
          <input type="text" class="form-control" id="service_badge" name="badge" placeholder="General" value="General">
        </div>

        <div class="mb-3">
          <label class="form-label">Title *</label>
          <input type="text" class="form-control" id="service_title" name="title" placeholder="e.g., General Checkup" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description *</label>
          <textarea class="form-control" id="service_description" name="description" rows="3" required></textarea>
        </div>

        <div class="mb-3">
          <label class="form-label">Sort Order</label>
          <input type="number" class="form-control" id="service_sort_order" name="sort_order" value="0">
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Save Service
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// ============================================================
// HERO FORM HANDLER
// ============================================================
document.getElementById('heroForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  try {
    const response = await fetch('../api/homepage_api.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.status === 'success') {
      alert('Hero section updated successfully!');
      loadHeroData();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Error updating hero section: ' + error.message);
  }
});

// Load hero data
async function loadHeroData() {
  try {
    const response = await fetch('../api/homepage_api.php?action=read_hero');
    const result = await response.json();
    if (result.status === 'success' && result.data) {
      const data = result.data;
      document.getElementById('hero_pill_text').value = data.hero_pill_text || '';
      document.getElementById('hero_title').value = data.hero_title || '';
      document.getElementById('hero_subtitle').value = data.hero_subtitle || '';
      document.getElementById('cta_button_text').value = data.cta_button_text || '';
      document.getElementById('cta_button_link').value = data.cta_button_link || '';
      document.getElementById('secondary_button_text').value = data.secondary_button_text || '';
      document.getElementById('secondary_button_link').value = data.secondary_button_link || '';
      document.getElementById('stat1_number').value = data.stat1_number || '';
      document.getElementById('stat1_label').value = data.stat1_label || '';
      document.getElementById('stat2_number').value = data.stat2_number || '';
      document.getElementById('stat2_label').value = data.stat2_label || '';
    }
  } catch (error) {
    console.error('Error loading hero data:', error);
  }
}

// ============================================================
// FEATURES HANDLERS
// ============================================================
async function loadFeatures() {
  try {
    const response = await fetch('../api/homepage_api.php?action=read_features');
    const result = await response.json();
    if (result.status === 'success') {
      const container = document.getElementById('featuresContainer');
      container.innerHTML = '';
      
      if (result.data.length === 0) {
        container.innerHTML = '<p class="text-muted">No features added yet</p>';
        return;
      }

      result.data.forEach(feature => {
        const card = `
          <div class="col-md-6">
            <div class="card feature-card">
              <div class="card-body">
                <h5><span style="font-size: 1.5em">${feature.icon}</span> ${feature.title}</h5>
                <p class="text-muted small">${feature.description}</p>
                <small class="text-muted">Sort: ${feature.sort_order}</small>
                <div class="mt-3 btn-group btn-group-sm">
                  <button type="button" class="btn btn-outline-primary" onclick="editFeature(${feature.id}, '${feature.icon.replace(/'/g, "\\'")}', '${feature.title.replace(/'/g, "\\'")}', '${feature.description.replace(/'/g, "\\'")}', ${feature.sort_order})">
                    <i class="bi bi-pencil"></i> Edit
                  </button>
                  <button type="button" class="btn btn-outline-danger" onclick="deleteFeature(${feature.id})">
                    <i class="bi bi-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;
        container.insertAdjacentHTML('beforeend', card);
      });
    }
  } catch (error) {
    console.error('Error loading features:', error);
  }
}

function editFeature(id, icon, title, description, sortOrder) {
  document.getElementById('feature_id').value = id;
  document.getElementById('feature_icon').value = icon;
  document.getElementById('feature_title').value = title;
  document.getElementById('feature_description').value = description;
  document.getElementById('feature_sort_order').value = sortOrder;
  document.getElementById('featureForm').elements['action'].value = 'update_feature';
  const modal = new bootstrap.Modal(document.getElementById('featureModal'));
  modal.show();
}

async function deleteFeature(id) {
  if (!confirm('Are you sure you want to delete this feature?')) return;
  
  const formData = new FormData();
  formData.append('action', 'delete_feature');
  formData.append('id', id);
  
  try {
    const response = await fetch('../api/homepage_api.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.status === 'success') {
      alert('Feature deleted successfully!');
      loadFeatures();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Error deleting feature: ' + error.message);
  }
}

document.getElementById('featureForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  try {
    const response = await fetch('../api/homepage_api.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.status === 'success') {
      alert('Feature saved successfully!');
      bootstrap.Modal.getInstance(document.getElementById('featureModal')).hide();
      document.getElementById('featureForm').reset();
      document.getElementById('feature_id').value = '';
      document.getElementById('featureForm').elements['action'].value = 'add_feature';
      loadFeatures();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Error saving feature: ' + error.message);
  }
});

// ============================================================
// SERVICES HANDLERS
// ============================================================
async function loadServices() {
  try {
    const response = await fetch('../api/homepage_api.php?action=read_services');
    const result = await response.json();
    if (result.status === 'success') {
      const container = document.getElementById('servicesContainer');
      container.innerHTML = '';
      
      if (result.data.length === 0) {
        container.innerHTML = '<p class="text-muted">No services added yet</p>';
        return;
      }

      result.data.forEach(service => {
        const card = `
          <div class="col-md-4">
            <div class="card service-card">
              <div class="card-body">
                <h6><span class="badge bg-primary">${service.badge}</span> <span style="font-size: 1.3em">${service.icon}</span></h6>
                <h5>${service.title}</h5>
                <p class="text-muted small">${service.description}</p>
                <small class="text-muted">Sort: ${service.sort_order}</small>
                <div class="mt-3 btn-group btn-group-sm w-100">
                  <button type="button" class="btn btn-outline-primary btn-sm" onclick="editService(${service.id}, '${service.icon.replace(/'/g, "\\'")}', '${service.badge.replace(/'/g, "\\'")}', '${service.title.replace(/'/g, "\\'")}', '${service.description.replace(/'/g, "\\'")}', ${service.sort_order})">
                    <i class="bi bi-pencil"></i> Edit
                  </button>
                  <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteService(${service.id})">
                    <i class="bi bi-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        `;
        container.insertAdjacentHTML('beforeend', card);
      });
    }
  } catch (error) {
    console.error('Error loading services:', error);
  }
}

function editService(id, icon, badge, title, description, sortOrder) {
  document.getElementById('service_id').value = id;
  document.getElementById('service_icon').value = icon;
  document.getElementById('service_badge').value = badge;
  document.getElementById('service_title').value = title;
  document.getElementById('service_description').value = description;
  document.getElementById('service_sort_order').value = sortOrder;
  document.getElementById('serviceForm').elements['action'].value = 'update_service';
  const modal = new bootstrap.Modal(document.getElementById('serviceModal'));
  modal.show();
}

async function deleteService(id) {
  if (!confirm('Are you sure you want to delete this service?')) return;
  
  const formData = new FormData();
  formData.append('action', 'delete_service');
  formData.append('id', id);
  
  try {
    const response = await fetch('../api/homepage_api.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.status === 'success') {
      alert('Service deleted successfully!');
      loadServices();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Error deleting service: ' + error.message);
  }
}

document.getElementById('serviceForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  try {
    const response = await fetch('../api/homepage_api.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.status === 'success') {
      alert('Service saved successfully!');
      bootstrap.Modal.getInstance(document.getElementById('serviceModal')).hide();
      document.getElementById('serviceForm').reset();
      document.getElementById('service_id').value = '';
      document.getElementById('serviceForm').elements['action'].value = 'add_service';
      loadServices();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Error saving service: ' + error.message);
  }
});

// ============================================================
// READY TO BOOK FORM HANDLER
// ============================================================
document.getElementById('readyToBookForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  try {
    const response = await fetch('../api/homepage_api.php', {
      method: 'POST',
      body: formData
    });
    const result = await response.json();
    if (result.status === 'success') {
      alert('Section updated successfully!');
      loadSectionData();
    } else {
      alert('Error: ' + result.message);
    }
  } catch (error) {
    alert('Error updating section: ' + error.message);
  }
});

async function loadSectionData() {
  try {
    const response = await fetch('../api/homepage_api.php?action=read_section&key=ready_to_book');
    const result = await response.json();
    if (result.status === 'success' && result.data) {
      const data = result.data;
      document.getElementById('rtb_title').value = data.title || '';
      document.getElementById('rtb_subtitle').value = data.subtitle || '';
      document.getElementById('rtb_tagline').value = data.tagline || '';
    }
  } catch (error) {
    console.error('Error loading section data:', error);
  }
}

// Load all data on page load
document.addEventListener('DOMContentLoaded', function() {
  loadHeroData();
  loadFeatures();
  loadServices();
  loadSectionData();
});
</script>

<?php require_once '../../includes/footer.php'; ?>
