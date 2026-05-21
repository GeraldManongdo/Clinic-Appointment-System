-- ============================================================
-- ClinicOS - Content Management Tables
-- Tables for managing editable homepage content
-- ============================================================

-- ============================================================
-- TABLE: site_hero (Hero Section - Title, Subtitle, CTA)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_hero` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `hero_pill_text` VARCHAR(255) DEFAULT 'Welcome to ClinicOS',
  `hero_title` TEXT NOT NULL DEFAULT 'Your <em>Trusted</em> Medical <span class="line-accent">Appointment System</span>',
  `hero_subtitle` TEXT DEFAULT 'Streamline your healthcare appointments with ClinicOS. Easy booking, instant confirmations, and seamless clinic management all in one platform.',
  `hero_image_path` VARCHAR(500) DEFAULT '/clinic-updated/public/assets/images/hero-image.jpg',
  `cta_button_text` VARCHAR(100) DEFAULT 'Book Appointment',
  `cta_button_link` VARCHAR(500) DEFAULT 'booking.php',
  `secondary_button_text` VARCHAR(100) DEFAULT 'Learn More',
  `secondary_button_link` VARCHAR(500) DEFAULT '#features',
  `stat1_number` VARCHAR(50) DEFAULT '2.5K+',
  `stat1_label` VARCHAR(100) DEFAULT 'Happy Patients',
  `stat2_number` VARCHAR(50) DEFAULT '98%',
  `stat2_label` VARCHAR(100) DEFAULT 'Satisfaction Rate',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: site_whychooseus (Why Choose Us - Max 6 Cards)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_whychooseus` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `icon` VARCHAR(50) DEFAULT '⭐',
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: site_sections (Generic Section Content)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_sections` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `section_key` VARCHAR(100) UNIQUE NOT NULL,
  `title` VARCHAR(255),
  `subtitle` VARCHAR(500),
  `description` TEXT,
  `tagline` TEXT,
  `is_editable` TINYINT(1) DEFAULT 1,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: site_theme (Color and Font Settings)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_theme` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `theme_key` VARCHAR(100) UNIQUE NOT NULL,
  `theme_value` TEXT NOT NULL,
  `theme_type` ENUM('color', 'font', 'spacing', 'other') DEFAULT 'other',
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: site_admin_users (Admin Users for Multi-Admin Support)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_admin_users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) UNIQUE NOT NULL,
  `email` VARCHAR(255) UNIQUE NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'editor', 'viewer') DEFAULT 'admin',
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- INSERT SAMPLE DATA
-- ============================================================

-- Insert default hero section
INSERT INTO `site_hero` (id) VALUES (1) ON DUPLICATE KEY UPDATE id=id;

-- Insert default sections
INSERT INTO `site_sections` (`section_key`, `title`, `subtitle`, `description`, `tagline`, `is_editable`) VALUES
('how_it_works', 'How It Works', 'Simple Process', 'Get your appointment in 4 easy steps.', NULL, 0),
('services', 'Services', 'Our Services', 'Complete range of healthcare services.', NULL, 1),
('ready_to_book', 'Ready to Book?', 'Ready to Get Started?', 'Schedule your appointment today!', 'Book your appointment now and experience quality healthcare.', 1);

-- Insert default theme colors
INSERT INTO `site_theme` (`theme_key`, `theme_value`, `theme_type`) VALUES
('primary_color', '#4f46e5', 'color'),
('secondary_color', '#06b6d4', 'color'),
('accent_color', '#f59e0b', 'color'),
('text_color', '#1f2937', 'color'),
('bg_light', '#f9fafb', 'color'),
('primary_font', 'Inter, sans-serif', 'font'),
('heading_font', 'Sora, sans-serif', 'font')
ON DUPLICATE KEY UPDATE theme_value=VALUES(theme_value);

-- ============================================================
-- UPDATE EXISTING TABLES
-- ============================================================

-- Ensure site_features is properly set up
INSERT INTO `site_features` (`icon`, `title`, `description`, `sort_order`, `is_active`) VALUES
('⚕️', 'Expert Medical Team', 'Our qualified doctors and healthcare professionals provide best-in-class medical services.', 1, 1),
('🏥', 'Modern Facilities', 'State-of-the-art medical equipment and comfortable clinic environment for patient care.', 2, 1),
('⏰', '24/7 Support', 'Round-the-clock customer support and emergency services for all your healthcare needs.', 3, 1),
('💝', 'Affordable Care', 'Competitive pricing with flexible payment options and insurance acceptance.', 4, 1)
ON DUPLICATE KEY UPDATE sort_order=VALUES(sort_order);

-- Ensure site_services is properly set up
INSERT INTO `site_services` (`icon`, `badge`, `title`, `description`, `sort_order`, `is_active`) VALUES
('👨‍⚕️', 'General', 'General Checkup', 'Comprehensive health examination including vital signs, medical history review, and general assessment.', 1, 1),
('🦷', 'Dental', 'Dental Care', 'Professional dental services including cleaning, check-ups, and preventive treatments for oral health.', 2, 1),
('👁️', 'Eye Care', 'Eye Care & Vision', 'Complete eye examination, vision correction, and treatment for various eye conditions.', 3, 1),
('💬', 'Consultation', 'Medical Consultation', 'One-on-one consultation with experienced doctors for health concerns and medical advice.', 4, 1),
('🧬', 'Lab Tests', 'Laboratory Tests', 'Complete blood work, diagnostic tests, and laboratory analysis for health monitoring.', 5, 1),
('💉', 'Vaccination', 'Vaccination Services', 'Immunization programs and vaccination services for disease prevention and health protection.', 6, 1)
ON DUPLICATE KEY UPDATE sort_order=VALUES(sort_order);

-- ============================================================
-- Database Updates Complete!
-- ============================================================
