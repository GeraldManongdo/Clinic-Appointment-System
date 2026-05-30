<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="mb-4">User Login</h4>
                <?php if (isset($_GET['error'])): ?><div class="alert alert-danger">Invalid credentials. Please try again.</div><?php endif; ?>
                <form method="post" action="<?= APP_URL ?>/?route=auth">
                    <input type="hidden" name="action" value="login">
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                    <button class="btn btn-primary w-100">Login</button>
                </form>
                <div class="mt-3 text-center">Don't have an account? <a href="<?= APP_URL ?>/?route=auth&action=register">Register</a></div>
            </div>
        </div>
    </div>
</section>
