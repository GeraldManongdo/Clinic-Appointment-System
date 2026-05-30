<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">

    <div>
        <h4 class="mb-1">Schedule Management</h4>

        <nav>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Schedule</li>
            </ol>
        </nav>
    </div>

</div>

<!-- BLOCK FORM CARD -->
<div class="p-3 mb-4 bg-white border rounded-3 shadow-sm">

    <form class="row g-2 align-items-end"
          id="blockDateForm"
          onsubmit="event.preventDefault(); addBlockDate();">

        <!-- DATE RANGE -->
        <div class="col-md-4">
            <label class="form-label small text-muted">Start Date</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-calendar-event"></i>
                </span>
                <input type="date" id="blockStartDate" class="form-control" required>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label small text-muted">End Date</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-calendar-event-fill"></i>
                </span>
                <input type="date" id="blockEndDate" class="form-control" required>
            </div>
        </div>

        <!-- TIME RANGE -->
        <div class="col-md-2">
            <label class="form-label small text-muted">Start Time</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-clock"></i>
                </span>
                <input type="time" id="blockStartTime" class="form-control">
            </div>
        </div>

        <div class="col-md-2">
            <label class="form-label small text-muted">End Time</label>
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-clock-fill"></i>
                </span>
                <input type="time" id="blockEndTime" class="form-control">
            </div>
        </div>

        <!-- ACTION -->
        <div class="col-12 d-flex justify-content-end mt-2">
            <button class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-slash-circle"></i>
                Block Schedule
            </button>
        </div>

    </form>
</div>

<!-- TABLE CARD -->
<div class="card border-0 shadow-sm">

    <div class="card-header bg-white border-bottom">
        <strong>Blocked Schedule List</strong>
    </div>

    <div class="table-responsive">

        <table class="table align-middle mb-0">

            <thead class="table-light">
                <tr>
                    <th>Date Range</th>
                    <th>Time Range</th>
                    <th>Blocked On</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>

                <?php foreach ($blocked as $item): ?>
                    <tr>

                        <!-- DATE RANGE -->
                        <td class="fw-semibold">
                            <i class="bi bi-calendar2-range me-1 text-primary"></i>
                            <?= htmlspecialchars($item['start_date']) ?>
                            <span class="text-muted">to</span>
                            <?= htmlspecialchars($item['end_date']) ?>
                        </td>

                        <!-- TIME RANGE -->
                        <td>
                            <?php if (!empty($item['start_time']) && !empty($item['end_time'])): ?>
                                <i class="bi bi-clock me-1 text-secondary"></i>
                                <?= htmlspecialchars($item['start_time']) ?>
                                <span class="text-muted">-</span>
                                <?= htmlspecialchars($item['end_time']) ?>
                            <?php else: ?>
                                <span class="text-muted small">All day</span>
                            <?php endif; ?>
                        </td>

                        <!-- CREATED -->
                        <td class="text-muted small">
                            <?= htmlspecialchars($item['created_at']) ?>
                        </td>

                        <!-- ACTION -->
                        <td class="text-end">

                            <button class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i>
                            </button>

                        </td>

                    </tr>
                <?php endforeach; ?>

                <?php if (empty($blocked)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            <i class="bi bi-calendar-x fs-4 d-block mb-2"></i>
                            No blocked schedules found
                        </td>
                    </tr>
                <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>