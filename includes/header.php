<?php
// Header include - Common head tag and opening body
$site_name = defined('SITE_NAME') ? SITE_NAME : 'ClinicOS';
$page_title = isset($page_title) ? $page_title . ' — ' . $site_name : $site_name;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <!-- Custom CSS Files -->
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/variables.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/bootstrap-override.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/layout.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/components.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/public.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/admin.css">
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/responsive.css">
    
    <?php if (isset($page_css)): ?>
    <link rel="stylesheet" href="<?php echo ASSETS_PATH; ?>css/<?php echo htmlspecialchars($page_css); ?>">
    <?php endif; ?>
</head>
<body>
