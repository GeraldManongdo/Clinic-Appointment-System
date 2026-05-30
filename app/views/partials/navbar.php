<!-- <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= APP_URL ?>"><?= htmlspecialchars($site['clinic_name'] ?? APP_NAME) ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/?route=appointment">Book</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/?route=profile">Profile</a></li>
                <?php if (Auth::check()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/?route=auth&action=logout">Logout</a></li>
                    <li class="nav-item"><a class="btn btn-primary ms-lg-3" href="<?= APP_URL ?>/?route=profile"><?= htmlspecialchars(Auth::user()['name'] ?? 'My Profile') ?></a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/?route=auth&action=login">Login</a></li>
                    <li class="nav-item"><a class="btn btn-outline-primary ms-lg-3" href="<?= APP_URL ?>/?route=auth&action=register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav> -->

<!-- ─── NAVBAR ─── -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">

        <a class="navbar-brand" href="<?= APP_URL ?>">
            <i class="bi bi-heart-pulse-fill me-2"></i><?= htmlspecialchars($site['clinic_name'] ?? APP_NAME) ?>
        </a>

        <button class="navbar-toggler border-0 shadow-none text-white" type="button"
            data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <i class="bi bi-list fs-4"></i>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">

            <ul class="navbar-nav mx-auto gap-lg-1">
                <li class="nav-item"><a class="nav-link active" href="<?= APP_URL ?>">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/#features">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/#services">Services</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/?route=appointment">Book</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/#how-it-works">How It Works</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= APP_URL ?>/#contact">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-3">

                <?php if (Auth::check()): ?>

                    <?php
                        $user = Auth::user();
                        $name = $user['name'] ?? 'User';

                        $nameParts = explode(' ', trim($name));
                        $initials = '';

                        foreach ($nameParts as $part) {
                            $initials .= strtoupper(substr($part, 0, 1));
                            if (strlen($initials) >= 2) break;
                        }
                    ?>

                    <div class="dropdown">
                        <div class="user-pill dropdown-toggle" data-bs-toggle="dropdown" style="cursor:pointer;">
                            <div class="user-avatar"><?= $initials ?></div>
                            <span class="d-none d-lg-inline"><?= htmlspecialchars($name) ?></span>
                        </div>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-3 mt-2">
                            <li>
                                <h6 class="dropdown-header" style="font-family:'DM Sans',sans-serif;">
                                    <?= htmlspecialchars($name) ?>
                                </h6>
                            </li>

                            <li>
                                <a class="dropdown-item py-2" href="<?= APP_URL ?>/?route=profile">
                                    <i class="bi bi-person me-2 text-muted"></i>My Profile
                                </a>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item py-2 text-danger"
                                   href="<?= APP_URL ?>/?route=auth&action=logout">
                                    <i class="bi bi-box-arrow-right me-2"></i>Sign Out
                                </a>
                            </li>
                        </ul>
                    </div>

                <?php else: ?>

                    <a class="nav-link" href="<?= APP_URL ?>/?route=auth&action=login">Login</a>

                    <a class="btn btn-outline-light"
                       href="<?= APP_URL ?>/?route=auth&action=register">
                        Register
                    </a>

                <?php endif; ?>

            </div>

        </div>
    </div>
</nav>