<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($site['clinic_name'] ?? APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/website.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/appointment.css">
</head>
<body>
    <?php require __DIR__ . '/../partials/navbar.php'; ?>
    <main class=""> <?= $content ?> </main>
    <?php require __DIR__ . '/../partials/footer.php'; ?>
    <script>const APP_URL = '<?= APP_URL ?>';</script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= APP_URL ?>/assets/js/app.js"></script>
    <script src="<?= APP_URL ?>/assets/js/website.js"></script>
    <script src="<?= APP_URL ?>/assets/js/appointment.js"></script>
</body>
</html>
