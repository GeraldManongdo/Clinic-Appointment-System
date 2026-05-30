<!-- HEADER TOOLBAR -->
<div class="d-flex flex-wrap gap-3 justify-content-between align-items-center mb-3">

    <!-- TITLE -->
    <div>
        <h5 class="mb-0 fw-semibold">Accounts Management</h5>
        <small class="text-muted">Manage system users and roles</small>
    </div>

    <!-- ACTION BUTTON -->
    <button class="btn btn-primary d-flex align-items-center gap-2"
        data-bs-toggle="modal"
        data-bs-target="#accountModal"
        onclick="openAccountForm()">
        <i class="bi bi-person-plus"></i>
        Create Account
    </button>

</div>

<!-- FILTER TOOLBAR (MATCHING APPOINTMENTS STYLE) -->
<div class="card-ui p-3 mb-4">

    <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">

        <!-- SEARCH -->
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control"
                placeholder="Search name, email, phone...">
        </div>

        <!-- FILTERS -->
        <div class="d-flex flex-wrap gap-2 align-items-center">

            <div class="input-group" style="width:180px;">
                <span class="input-group-text">
                    <i class="bi bi-person-badge"></i>
                </span>
                <select class="form-select">
                    <option>All Roles</option>
                    <option>User</option>
                    <option>Admin</option>
                </select>
            </div>

            <div class="input-group" style="width:200px;">
                <span class="input-group-text">
                    <i class="bi bi-calendar-event"></i>
                </span>
                <input type="date" class="form-control">
            </div>

            <button class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-funnel"></i>
                Apply
            </button>

        </div>

    </div>
</div>

<!-- TABLE CARD -->
<div class="card-ui overflow-hidden">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($users as $user): ?>
                <tr
                    data-account-id="<?= $user['id'] ?>"
                    data-name="<?= htmlspecialchars($user['name'], ENT_QUOTES) ?>"
                    data-email="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>"
                    data-phone="<?= htmlspecialchars($user['phone'], ENT_QUOTES) ?>"
                    data-role="<?= htmlspecialchars($user['role'], ENT_QUOTES) ?>">

                    <td class="fw-semibold">
                        <?= htmlspecialchars($user['name']) ?>
                    </td>

                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>

                    <td>
                        <?php if ($user['role'] === 'admin'): ?>
                            <span class="badge bg-primary">
                                <i class="bi bi-shield-lock me-1"></i>Admin
                            </span>
                        <?php else: ?>
                            <span class="badge bg-secondary">
                                <i class="bi bi-person me-1"></i>User
                            </span>
                        <?php endif; ?>
                    </td>

                    <td><?= htmlspecialchars($user['created_at']) ?></td>

                    <td class="text-end">

                        <button class="btn btn-sm btn-outline-primary"
                            title="Edit"
                            onclick="openAccountForm(<?= $user['id'] ?>)">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-secondary"
                            title="View">
                            <i class="bi bi-eye"></i>
                        </button>

                        <button class="btn btn-sm btn-outline-danger"
                            title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>

                    </td>

                </tr>
                <?php endforeach; ?>

                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        No accounts found
                    </td>
                </tr>
                <?php endif; ?>

            </tbody>

        </table>
    </div>
</div>

<!-- PAGINATION (OPTIONAL UI CONSISTENCY) -->
<div class="d-flex justify-content-between align-items-center mt-3">

    <small class="text-muted">
        Showing <?= count($users) ?> accounts
    </small>

    <nav>
        <ul class="pagination pagination-sm mb-0">

            <li class="page-item disabled"><a class="page-link">Previous</a></li>
            <li class="page-item active"><a class="page-link">1</a></li>
            <li class="page-item"><a class="page-link">2</a></li>
            <li class="page-item"><a class="page-link">Next</a></li>

        </ul>
    </nav>

</div>