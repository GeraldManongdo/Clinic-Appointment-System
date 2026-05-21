-- ============================================================
-- ClinicOS Database Schema with Sample Data
-- ============================================================
-- Complete database setup for clinic management system
-- Includes: appointments, users, services, features, messages
-- ============================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS `clinic`;
USE `clinic`;

-- ============================================================
-- TABLE: appointments
-- ============================================================
CREATE TABLE IF NOT EXISTS `appointments` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `patient_name` VARCHAR(200) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50),
  `appointment_date` DATE NOT NULL,
  `appointment_time` VARCHAR(20) NOT NULL,
  `appointment_datetime` VARCHAR(100) NOT NULL,
  `payment_method` ENUM('gcash','maya','cash') NOT NULL DEFAULT 'cash',
  `payment_ref` VARCHAR(100),
  `receipt_image` VARCHAR(255),
  `sender_name` VARCHAR(200),
  `booking_ref` VARCHAR(50) UNIQUE,
  `amount` DECIMAL(10,2) DEFAULT 500.00,
  `status` ENUM('pending','confirmed','rejected','cancelled') DEFAULT 'pending',
  `admin_notes` TEXT,
  `cancel_reason` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `confirmed_at` TIMESTAMP NULL,
  INDEX `idx_status` (`status`),
  INDEX `idx_date` (`appointment_date`),
  INDEX `idx_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: blocked_dates
-- ============================================================
CREATE TABLE IF NOT EXISTS `blocked_dates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `block_date` DATE NOT NULL UNIQUE,
  `reason` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_date` (`block_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: blocked_time_slots
-- ============================================================
CREATE TABLE IF NOT EXISTS `blocked_time_slots` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `block_date` DATE NOT NULL,
  `slot_time` VARCHAR(30) NOT NULL,
  `reason` VARCHAR(255),
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_slot` (`block_date`, `slot_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: site_features (Why Choose Us)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_features` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `icon` VARCHAR(20) NOT NULL DEFAULT '⭐',
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: site_services (Core Services)
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_services` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `icon` VARCHAR(20) NOT NULL DEFAULT '🏥',
  `badge` VARCHAR(100) NOT NULL DEFAULT 'General',
  `title` VARCHAR(200) NOT NULL,
  `description` TEXT NOT NULL,
  `image_path` VARCHAR(500) DEFAULT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- ============================================================
-- TABLE: contact_messages (Messages & Testimonials)
-- ============================================================
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sender_name` VARCHAR(200) NOT NULL,
  `sender_email` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(300) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) NOT NULL DEFAULT 0,
  `is_testimonial` TINYINT(1) NOT NULL DEFAULT 0,
  `testimonial_rating` INT NOT NULL DEFAULT 5,
  `is_visible` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_read` (`is_read`),
  INDEX `idx_testimonial` (`is_testimonial`, `is_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- SAMPLE DATA: Site Features
-- ============================================================
INSERT INTO `site_features` (`icon`, `title`, `description`, `sort_order`, `is_active`) VALUES
('⚕️', 'Expert Medical Team', 'Our qualified doctors and healthcare professionals provide best-in-class medical services.', 1, 1),
('🏥', 'Modern Facilities', 'State-of-the-art medical equipment and comfortable clinic environment for patient care.', 2, 1),
('⏰', '24/7 Support', 'Round-the-clock customer support and emergency services for all your healthcare needs.', 3, 1),
('💝', 'Affordable Care', 'Competitive pricing with flexible payment options and insurance acceptance.', 4, 1);

-- ============================================================
-- SAMPLE DATA: Site Services
-- ============================================================
INSERT INTO `site_services` (`icon`, `badge`, `title`, `description`, `sort_order`, `is_active`) VALUES
('👨‍⚕️', 'General', 'General Checkup', 'Comprehensive health examination including vital signs, medical history review, and general assessment.', 1, 1),
('🦷', 'Dental', 'Dental Care', 'Professional dental services including cleaning, check-ups, and preventive treatments for oral health.', 2, 1),
('👁️', 'Eye Care', 'Eye Care & Vision', 'Complete eye examination, vision correction, and treatment for various eye conditions.', 3, 1),
('💬', 'Consultation', 'Medical Consultation', 'One-on-one consultation with experienced doctors for health concerns and medical advice.', 4, 1),
('🧬', 'Lab Tests', 'Laboratory Tests', 'Complete blood work, diagnostic tests, and laboratory analysis for health monitoring.', 5, 1),
('💉', 'Vaccination', 'Vaccination Services', 'Immunization programs and vaccination services for disease prevention and health protection.', 6, 1);

-- ============================================================
-- SAMPLE DATA: Appointments
-- ============================================================
INSERT INTO `appointments` (`patient_name`, `email`, `phone`, `appointment_date`, `appointment_time`, `appointment_datetime`, `payment_method`, `booking_ref`, `amount`, `status`, `created_at`) VALUES
('John Doe', 'john@example.com', '+1-555-0101', '2026-05-25', '9:00 AM', '2026-05-25 09:00', 'gcash', 'REF001', 500.00, 'confirmed', NOW()),
('Jane Smith', 'jane@example.com', '+1-555-0102', '2026-05-26', '10:30 AM', '2026-05-26 10:30', 'maya', 'REF002', 500.00, 'pending', NOW()),
('Michael Johnson', 'michael@example.com', '+1-555-0103', '2026-05-27', '2:00 PM', '2026-05-27 14:00', 'cash', 'REF003', 500.00, 'confirmed', NOW()),
('Sarah Williams', 'sarah@example.com', '+1-555-0104', '2026-05-28', '3:30 PM', '2026-05-28 15:30', 'gcash', 'REF004', 500.00, 'pending', NOW());

-- ============================================================
-- SAMPLE DATA: Contact Messages
-- ============================================================
INSERT INTO `contact_messages` (`sender_name`, `sender_email`, `subject`, `message`, `is_testimonial`, `testimonial_rating`, `is_visible`) VALUES
('Maria Garcia', 'maria@example.com', 'Great Service', 'The clinic staff was very professional and caring. My appointment was smooth and the doctor was very knowledgeable.', 1, 5, 1),
('Robert Chen', 'robert@example.com', 'Highly Recommended', 'Excellent service! The facilities are modern and clean. I felt comfortable throughout my visit.', 1, 5, 1),
('Patricia Davis', 'patricia@example.com', 'Appointment Inquiry', 'Would like to schedule a general checkup next week. Please let me know available slots.', 0, 0, 0),
('David Anderson', 'david@example.com', 'Outstanding Experience', 'Very impressed with the quick appointment scheduling and the quality of medical care provided.', 1, 5, 1);

-- ============================================================
-- SAMPLE DATA: Site Hero Section
-- ============================================================
INSERT INTO `site_hero` (`id`, `hero_pill_text`, `hero_title`, `hero_subtitle`, `cta_button_text`, `stat1_number`, `stat1_label`, `stat2_number`, `stat2_label`) VALUES
(1, 'Welcome to ClinicOS', 'Your <em>Trusted</em> Medical <span class="line-accent">Appointment System</span>', 'Streamline your healthcare appointments with ClinicOS. Easy booking, instant confirmations, and seamless clinic management all in one platform.', 'Book Appointment', '2.5K+', 'Happy Patients', '98%', 'Satisfaction Rate');

-- ============================================================
-- SAMPLE DATA: Site Sections
-- ============================================================
INSERT INTO `site_sections` (`section_key`, `title`, `subtitle`, `description`, `tagline`, `is_editable`) VALUES
('how_it_works', 'How It Works', 'Simple Process', 'Get your appointment in 4 easy steps.', NULL, 0),
('services', 'Services', 'Our Services', 'Complete range of healthcare services.', NULL, 1),
('ready_to_book', 'Ready to Book?', 'Ready to Get Started?', 'Schedule your appointment today!', 'Book your appointment now and experience quality healthcare.', 1);

-- ============================================================
-- SAMPLE DATA: Theme Settings
-- ============================================================
INSERT INTO `site_theme` (`theme_key`, `theme_value`, `theme_type`) VALUES
('primary_color', '#4f46e5', 'color'),
('secondary_color', '#06b6d4', 'color'),
('accent_color', '#f59e0b', 'color'),
('text_color', '#1f2937', 'color'),
('bg_light', '#f9fafb', 'color'),
('primary_font', 'Inter, sans-serif', 'font'),
('heading_font', 'Sora, sans-serif', 'font');

-- ============================================================
-- ADMIN CREDENTIALS
-- ============================================================
-- Username: admin
-- Password: password123 (hash: $2a$13$k46hlMoywmhCeFtcXL8.su2GYr6Dw6Uu7lCoBw2MajcxYrIye9i4e)
-- Change password in auth/admin_auth.php
-- ============================================================

-- ============================================================
-- Database Setup Complete!
-- ============================================================
-- The database is ready for use.
-- Access admin panel at: http://localhost/clinic-updated/public/admin/login.php
-- ============================================================
