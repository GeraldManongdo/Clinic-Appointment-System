<!-- PAGE HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1">Services Management</h4>
        <nav>
            <ol class="breadcrumb mb-0 small">
                <li class="breadcrumb-item"><a href="#" class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active">Services</li>
            </ol>
        </nav>
    </div>

    <button class="btn btn-primary d-flex align-items-center gap-2"
        data-bs-toggle="modal"
        data-bs-target="#serviceModal"
        onclick="openServiceForm()">
        <i class="bi bi-plus-circle"></i>
        Add Service
    </button>
</div>

<!-- TOOLBAR (SEARCH + FILTER STYLE LIKE APPOINTMENTS) -->
<div class="p-2 mb-3 bg-white border rounded-3 shadow-sm">

    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">

        <!-- SEARCH -->
        <div class="input-group" style="max-width:420px;">
            <span class="input-group-text bg-white">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" class="form-control"
                placeholder="Search services (title, description...)">
        </div>

        <!-- FILTERS -->
        <div class="d-flex flex-wrap gap-2 align-items-center">

            <div class="input-group" style="width:180px;">
                <span class="input-group-text bg-white">
                    <i class="bi bi-funnel"></i>
                </span>
                <select class="form-select">
                    <option>All Visibility</option>
                    <option>Visible</option>
                    <option>Hidden</option>
                </select>
            </div>

            <button class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-sliders"></i>
                Apply
            </button>

        </div>
    </div>
</div>

<!-- SERVICES GRID -->
<div class="row g-4">

    <?php foreach ($services as $service): ?>
        <div class="col-md-6 col-xl-4">

            <div class="card border-0 shadow-sm h-100 overflow-hidden">

                <!-- IMAGE -->
                <div style="height:200px; overflow:hidden;">
                    <img
                        src="<?= htmlspecialchars(
                            $service['image_path']
                                ? APP_URL . '/uploads/' . $service['image_path']
                                : 'https://images.unsplash.com/photo-1550831107-1553da8c8464?auto=format&fit=crop&w=900&q=80'
                        ) ?>"
                        class="w-100 h-100"
                        style="object-fit:cover;"
                        alt="<?= htmlspecialchars($service['title']) ?>">
                </div>

                <!-- BODY -->
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="mb-0"><?= htmlspecialchars($service['title']) ?></h5>

                        <span class="badge <?= $service['visible'] ? 'bg-success' : 'bg-secondary' ?>">
                            <i class="bi <?= $service['visible'] ? 'bi-eye' : 'bi-eye-slash' ?> me-1"></i>
                            <?= $service['visible'] ? 'Visible' : 'Hidden' ?>
                        </span>
                    </div>

                    <p class="text-muted small mb-3">
                        <?= htmlspecialchars($service['description']) ?>
                    </p>

                    <!-- ACTIONS -->
                    <div class="d-flex justify-content-between align-items-center">

                        <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1"
                            onclick="openServiceForm(<?= $service['id'] ?>)">
                            <i class="bi bi-pencil-square"></i>
                            Edit
                        </button>

                        <div class="d-flex gap-2">

                            <button class="btn btn-sm <?= $service['visible'] ? 'btn-outline-secondary' : 'btn-outline-success' ?>"
                                onclick="toggleService(<?= $service['id'] ?>, <?= $service['visible'] ? 0 : 1 ?>)">
                                <i class="bi bi-toggle-on"></i>
                            </button>

                            <button class="btn btn-sm btn-outline-danger"
                                onclick="deleteService(<?= $service['id'] ?>)">
                                <i class="bi bi-trash"></i>
                            </button>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    <?php endforeach; ?>

</div>

<!-- MODAL -->
<div class="modal fade" id="serviceModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">
                <h5 class="modal-title">Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form id="serviceForm" enctype="multipart/form-data">

                <div class="modal-body">

                    <input type="hidden" name="action" value="save_service">
                    <input type="hidden" id="serviceId" name="id">

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" id="serviceTitle" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea id="serviceDescription" name="description" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" id="serviceImage" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="serviceVisible" name="visible" checked>
                        <label class="form-check-label" for="serviceVisible">
                            Visible
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveService()">
                        <i class="bi bi-check-circle me-1"></i> Save
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>