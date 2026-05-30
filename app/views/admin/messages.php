<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

    <div>
        <h4 class="mb-1">Contact Inquiries</h4>

        <nav>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Messages</li>
            </ol>
        </nav>
    </div>

</div>

<!-- TOOLBAR -->
<div class="p-3 mb-4 bg-white border rounded-3 shadow-sm">

    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">

        <!-- SEARCH -->
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search name, email, subject...">
        </div>

        <!-- FILTER BUTTON (optional future use) -->
        <button class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-funnel"></i>
            Filter
        </button>

    </div>

</div>

<!-- TABLE -->
<div class="card border-0 shadow-sm">

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Subject</th>
                    <th>Message</th>
                    <th>Date</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($messages as $message): ?>
                    <tr>

                        <!-- NAME -->
                        <td class="fw-semibold">
                            <i class="bi bi-person-circle me-1 text-primary"></i>
                            <?= htmlspecialchars($message['name']) ?>
                        </td>

                        <!-- EMAIL -->
                        <td>
                            <div class="small text-muted">
                                <i class="bi bi-envelope me-1"></i>
                                <?= htmlspecialchars($message['email']) ?>
                            </div>
                        </td>

                        <!-- SUBJECT -->
                        <td>
                            <span class="badge bg-light text-dark border">
                                <?= htmlspecialchars($message['subject']) ?>
                            </span>
                        </td>

                        <!-- MESSAGE (TRUNCATED PREVIEW) -->
                        <td style="max-width:280px;">
                            <span class="text-muted small">
                                <?= htmlspecialchars(substr($message['message'], 0, 80)) ?>
                                <?= strlen($message['message']) > 80 ? '...' : '' ?>
                            </span>
                        </td>

                        <!-- DATE -->
                        <td class="text-muted small">
                            <i class="bi bi-clock me-1"></i>
                            <?= htmlspecialchars($message['created_at']) ?>
                        </td>

                        <!-- ACTION -->
                        <td class="text-end">

                            <button class="btn btn-sm btn-outline-primary"
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

                <?php if (empty($messages)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                            No messages received yet
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>