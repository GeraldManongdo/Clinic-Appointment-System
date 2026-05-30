CREATE DATABASE IF NOT EXISTS clinic CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinic;

CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  phone VARCHAR(50) DEFAULT NULL,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(20) NOT NULL DEFAULT 'user',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (name, email, phone, password, role, created_at) VALUES
('Administrator', 'admin@clinicos.ph', '+63 900 000 0000', '$2y$10$iqKgoZfKsFqk4eJI0np8jeQCzxLw6FckHl6hJ5hjQS6tVlQ5JrtlK', 'admin', NOW());

CREATE TABLE services (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  image_path VARCHAR(255) DEFAULT NULL,
  visible TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE appointments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  service_id INT NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time VARCHAR(20) NOT NULL,
  payment_method VARCHAR(50) DEFAULT NULL,
  payment_reference VARCHAR(150) DEFAULT NULL,
  receipt_path VARCHAR(255) DEFAULT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'pending',
  admin_notes TEXT DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE blocked_dates (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_value DATE NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE blocked_time_slots (
  id INT AUTO_INCREMENT PRIMARY KEY,
  date_value DATE NOT NULL,
  time_value VARCHAR(20) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL,
  subject VARCHAR(255) DEFAULT NULL,
  message TEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE email_verifications (
  id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(150) NOT NULL,
  code VARCHAR(10) NOT NULL,
  expires_at DATETIME NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE payment_logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT NOT NULL,
  method VARCHAR(50) NOT NULL,
  reference VARCHAR(150) NOT NULL,
  status VARCHAR(50) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE site_settings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  `key` VARCHAR(100) NOT NULL UNIQUE,
  `value` TEXT DEFAULT NULL
) ENGINE=InnoDB;

CREATE TABLE testimonials (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  position VARCHAR(100) DEFAULT NULL,
  message TEXT NOT NULL,
  approved TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO site_settings (`key`, `value`) VALUES
('clinic_name', 'Clinicos Clinic'),
('hero_title', 'Book quality care with a trusted doctor'),
('hero_subtitle', 'Single-doctor clinic appointments with secure payment verification and easy patient access.'),
('about_text', 'Clinicos Clinic delivers convenient care in a patient-first environment. We support online booking, confirmations, and appointment history.'),
('doctor_name', 'Dr. Maria Santos'),
('doctor_specialty', 'General Practitioner'),
('doctor_bio', 'Experienced physician focused on family health and wellness with personalized treatment plans.'),
('faq_items', '[{"question":"How do I book an appointment?","answer":"Fill the form, upload payment receipt, and verify via email OTP."},{"question":"What payment methods are accepted?","answer":"GCash and Maya payments are accepted."}]'),
('contact_text', 'Reach out to schedule a visit or ask about service options.'),
('clinic_address', '123 Health Street, Manila'),
('consultation_fee', '499'),
('opening_hours', 'Mon-Fri 8AM - 5PM'),
('booking_duration', '30 minutes'),
('contact_phone', '+63 912 345 6789'),
('contact_email', 'info@clinicos.ph'),
('smtp_host', 'smtp.example.com'),
('smtp_port', '587'),
('smtp_username', 'smtp@example.com'),
('smtp_password', 'password'),
('smtp_encryption', 'tls');

INSERT INTO services (title, description, image_path, visible) VALUES
('General consultation', 'Comprehensive assessment and care plan for general health concerns.', '' , 1),
('Follow-up visit', 'Continued support and review of treatment progress.', '' , 1),
('Blood pressure check', 'Quick and accurate blood pressure monitoring and advice.', '' , 1);

INSERT INTO testimonials (name, position, message, approved) VALUES
('Ana Cruz', 'Patient', 'Fast appointment scheduling and friendly care.', 1),
('Rico Dela Vega', 'Patient', 'The clinic staff were very helpful and the doctor was professional.', 1);
