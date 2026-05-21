<?php
require_once '../../includes/config.php';
$page_title = "Theme & Branding";
require_once '../../includes/header.php';
?>

<!-- Main Layout -->
<div class="layout">
  <?php require_once '../../components/sidebar-admin.php'; ?>

  <main class="admin-main">
    <?php require_once '../../components/topbar.php'; ?>

    <div class="admin-content">
      <!-- Page Header -->
      <div class="mb-4">
        <h2 class="mb-1">Theme & Branding</h2>
        <p class="text-muted">Customize the colors, fonts, and overall look of your clinic website</p>
      </div>

      <!-- Navigation Tabs -->
      <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
          <a class="nav-link active" data-bs-toggle="tab" href="#colors-tab" role="tab">Colors</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#fonts-tab" role="tab">Fonts</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-bs-toggle="tab" href="#preview-tab" role="tab">Preview</a>
        </li>
      </ul>

      <!-- Tab Content -->
      <div class="tab-content">

        <!-- ============================================================ -->
        <!-- COLORS TAB -->
        <!-- ============================================================ -->
        <div class="tab-pane fade show active" id="colors-tab" role="tabpanel">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Color Palette</h5>
              <small class="text-muted">Define the primary colors used throughout your website</small>
            </div>
            <div class="card-body">
              <div class="row g-4" id="colorsContainer">
                <!-- Colors will be loaded here -->
              </div>
              <div class="mt-4">
                <button type="button" class="btn btn-success" onclick="saveAllThemeSettings()">
                  <i class="bi bi-save"></i> Save All Changes
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- FONTS TAB -->
        <!-- ============================================================ -->
        <div class="tab-pane fade" id="fonts-tab" role="tabpanel">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Font Settings</h5>
              <small class="text-muted">Select fonts used for body text and headings</small>
            </div>
            <div class="card-body">
              <div class="row g-4" id="fontsContainer">
                <!-- Fonts will be loaded here -->
              </div>
              <div class="mt-4">
                <button type="button" class="btn btn-success" onclick="saveAllThemeSettings()">
                  <i class="bi bi-save"></i> Save All Changes
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- ============================================================ -->
        <!-- PREVIEW TAB -->
        <!-- ============================================================ -->
        <div class="tab-pane fade" id="preview-tab" role="tabpanel">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">Live Preview</h5>
              <small class="text-muted">See how your theme looks in real-time</small>
            </div>
            <div class="card-body">
              <div id="previewContainer" class="p-4" style="background: #f8f9fa; border-radius: 8px;">
                <div class="row g-4">
                  <div class="col-md-6">
                    <h3 style="color: var(--primary-color); font-family: var(--heading-font);">This is a heading</h3>
                    <p style="color: var(--text-color); font-family: var(--primary-font);">This is body text. The clinic booking system uses your theme colors and fonts throughout the entire website.</p>
                    <button class="btn" style="background-color: var(--primary-color); color: white; border: none;">Primary Button</button>
                    <button class="btn btn-outline-secondary" style="color: var(--primary-color); border-color: var(--primary-color);">Secondary Button</button>
                  </div>
                  <div class="col-md-6">
                    <div style="background: var(--primary-color); color: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                      <h5>Primary Color Block</h5>
                      <p>This demonstrates your primary color in action.</p>
                    </div>
                    <div style="background: var(--secondary-color); color: white; padding: 20px; border-radius: 8px; margin-bottom: 15px;">
                      <h5>Secondary Color Block</h5>
                      <p>This demonstrates your secondary color.</p>
                    </div>
                    <div style="background: var(--accent-color); color: white; padding: 20px; border-radius: 8px;">
                      <h5>Accent Color Block</h5>
                      <p>This demonstrates your accent color.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </main>
</div>

<style id="themePreviewStyle">
  :root {
    --primary-color: #4f46e5;
    --secondary-color: #06b6d4;
    --accent-color: #f59e0b;
    --text-color: #1f2937;
    --bg-light: #f9fafb;
    --primary-font: 'Inter', sans-serif;
    --heading-font: 'Sora', sans-serif;
  }
</style>

<script>
const themeChanges = {};

// Font options
const fontOptions = [
  { value: 'Inter, sans-serif', label: 'Inter' },
  { value: 'Sora, sans-serif', label: 'Sora' },
  { value: 'Poppins, sans-serif', label: 'Poppins' },
  { value: 'Roboto, sans-serif', label: 'Roboto' },
  { value: 'Open Sans, sans-serif', label: 'Open Sans' },
  { value: 'Segoe UI, sans-serif', label: 'Segoe UI' },
  { value: 'Georgia, serif', label: 'Georgia' },
];

// Load theme settings
async function loadThemeSettings() {
  try {
    const response = await fetch('../api/theme_api.php?action=read_all');
    const result = await response.json();
    
    if (result.status === 'success') {
      displayColors(result.data.color || []);
      displayFonts(result.data.font || []);
      updatePreview(result.data);
    }
  } catch (error) {
    console.error('Error loading theme settings:', error);
  }
}

function displayColors(colors) {
  const container = document.getElementById('colorsContainer');
  container.innerHTML = '';
  
  if (colors.length === 0) {
    container.innerHTML = '<p class="text-muted">No color settings found</p>';
    return;
  }

  colors.forEach(color => {
    const colorName = color.theme_key.replace(/_/g, ' ').toUpperCase();
    const col = document.createElement('div');
    col.className = 'col-md-6 col-lg-4';
    col.innerHTML = `
      <div class="color-input-group">
        <label class="form-label">${colorName}</label>
        <div class="input-group">
          <input type="color" class="form-control form-control-color theme-input" 
                 data-key="${color.theme_key}" 
                 value="${color.theme_value}" 
                 style="width: 60px; height: 50px;">
          <input type="text" class="form-control theme-input" 
                 data-key="${color.theme_key}" 
                 value="${color.theme_value}"
                 placeholder="#000000"
                 style="flex: 1;">
        </div>
        <small class="text-muted d-block mt-2">Current: <code>${color.theme_value}</code></small>
      </div>
    `;
    container.appendChild(col);
  });

  // Add event listeners
  document.querySelectorAll('.theme-input').forEach(input => {
    input.addEventListener('change', function() {
      updateThemeChange(this.dataset.key, this.value);
      syncColorInputs(this.dataset.key, this.value);
      updatePreview();
    });
  });
}

function displayFonts(fonts) {
  const container = document.getElementById('fontsContainer');
  container.innerHTML = '';
  
  if (fonts.length === 0) {
    container.innerHTML = '<p class="text-muted">No font settings found</p>';
    return;
  }

  fonts.forEach(font => {
    const fontName = font.theme_key.replace(/_/g, ' ').toUpperCase();
    const col = document.createElement('div');
    col.className = 'col-md-6';
    col.innerHTML = `
      <div class="font-select-group">
        <label class="form-label">${fontName}</label>
        <select class="form-select theme-input" data-key="${font.theme_key}">
          ${fontOptions.map(opt => `
            <option value="${opt.value}" ${opt.value === font.theme_value ? 'selected' : ''}>${opt.label}</option>
          `).join('')}
        </select>
        <small class="text-muted d-block mt-2">Current: <code>${font.theme_value}</code></small>
      </div>
    `;
    container.appendChild(col);
  });

  // Add event listeners
  document.querySelectorAll('.font-select-group .theme-input').forEach(input => {
    input.addEventListener('change', function() {
      updateThemeChange(this.dataset.key, this.value);
      updatePreview();
    });
  });
}

function syncColorInputs(key, value) {
  document.querySelectorAll(`[data-key="${key}"]`).forEach(input => {
    input.value = value;
  });
}

function updateThemeChange(key, value) {
  themeChanges[key] = value;
}

function updatePreview(allThemes = null) {
  const root = document.documentElement;
  const previewStyle = document.getElementById('themePreviewStyle');
  
  if (allThemes) {
    // Set initial values
    if (allThemes.color) {
      allThemes.color.forEach(color => {
        root.style.setProperty('--' + color.theme_key.replace(/_/g, '-'), color.theme_value);
      });
    }
    if (allThemes.font) {
      allThemes.font.forEach(font => {
        root.style.setProperty('--' + font.theme_key.replace(/_/g, '-'), font.theme_value);
      });
    }
  } else {
    // Update with changes
    Object.entries(themeChanges).forEach(([key, value]) => {
      root.style.setProperty('--' + key.replace(/_/g, '-'), value);
    });
  }
}

async function saveAllThemeSettings() {
  if (Object.keys(themeChanges).length === 0) {
    alert('No changes to save');
    return;
  }

  const savePromises = Object.entries(themeChanges).map(([key, value]) => {
    const formData = new FormData();
    formData.append('action', 'update');
    formData.append('theme_key', key);
    formData.append('theme_value', value);
    
    return fetch('../api/theme_api.php', {
      method: 'POST',
      body: formData
    }).then(r => r.json());
  });

  try {
    const results = await Promise.all(savePromises);
    if (results.every(r => r.status === 'success')) {
      alert('All theme settings saved successfully!');
      themeChanges = {};
      loadThemeSettings();
    } else {
      alert('Some settings failed to save. Please try again.');
    }
  } catch (error) {
    alert('Error saving theme settings: ' + error.message);
  }
}

// Load settings on page load
document.addEventListener('DOMContentLoaded', loadThemeSettings);
</script>

<?php require_once '../../includes/footer.php'; ?>
