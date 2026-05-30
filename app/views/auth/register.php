<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm p-4">
                <h4 class="mb-4">Create an Account</h4>
                <?php if (isset($_GET['error']) && $_GET['error'] === '1'): ?><div class="alert alert-danger">Please complete all required fields.</div><?php endif; ?>
                <?php if (isset($_GET['error']) && $_GET['error'] === '2'): ?><div class="alert alert-danger">Email is already registered.</div><?php endif; ?>
                <form method="post" action="<?= APP_URL ?>/?route=auth">
                    <input type="hidden" name="action" value="register">
                    <div class="mb-3"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
                    <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" required></div>
                    <button class="btn btn-primary w-100">Register</button>
                </form>
                <div class="mt-3 text-center">Already have an account? <a href="<?= APP_URL ?>/?route=auth&action=login">Login</a></div>
            </div>
        </div>
    </div>
</section>
