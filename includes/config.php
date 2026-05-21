<?php
/**
 * ClinicOS Configuration
 * Main configuration file for the clinic management system
 */

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'clinic');  // Match the imported database name

// Site Configuration
define('SITE_NAME', 'ClinicOS');
define('SITE_DESCRIPTION', 'Your Trusted Medical Appointment System');
define('SITE_URL', 'http://localhost/clinic-updated/public/');

// Paths
define('BASE_PATH', dirname(dirname(__FILE__)) . '/');
define('PUBLIC_PATH', BASE_PATH . 'public/');
define('ASSETS_PATH', '/clinic-updated/public/assets/');  // Absolute path from web root
define('COMPONENTS_PATH', BASE_PATH . 'components/');
define('INCLUDES_PATH', BASE_PATH . 'includes/');

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
