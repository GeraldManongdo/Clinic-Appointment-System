<?php
/**
 * ClinicOS Admin - Payments
 * Payment management and history
 */
require_once '../../includes/config.php';

// Authentication disabled - page accessible to all
// if (!isset($_SESSION['admin_id'])) {
//   header('Location: login.php');
//   exit;
// }

$page_title = 'Payments';
$user_name = 'Admin User';
?>
<?php include '../../includes/header.php'; ?>

<div class="layout">
  <?php include '../../components/sidebar-admin.php'; ?>

  <div class="main">
    <?php include '../../components/topbar.php'; ?>

    <div class="content">
      <div class="mb-4">
        <h2 class="h4">Payment Management</h2>
        <p class="text-muted">Track and manage all payment transactions.</p>
      </div>

      <!-- Summary Cards -->
      <div class="row g-3 mb-4">
        <div class="col-md-3">
          <div class="stat-card">
            <div class="stat-icon blue"><i class="bi bi-cash-coin"></i></div>
            <div class="stat-content">
              <div class="stat-num">$45,230</div>
              <div class="stat-label">Total Revenue</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-card">
            <div class="stat-icon green"><i class="bi bi-check-circle"></i></div>
            <div class="stat-content">
              <div class="stat-num">328</div>
              <div class="stat-label">Completed</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-card">
            <div class="stat-icon yellow"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-content">
              <div class="stat-num">12</div>
              <div class="stat-label">Pending</div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="stat-card">
            <div class="stat-icon red"><i class="bi bi-exclamation-circle"></i></div>
            <div class="stat-content">
              <div class="stat-num">5</div>
              <div class="stat-label">Failed</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Filter Section -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3">
              <input type="date" class="form-control" placeholder="From date">
            </div>
            <div class="col-md-3">
              <input type="date" class="form-control" placeholder="To date">
            </div>
            <div class="col-md-3">
              <select class="form-select">
                <option>All Methods</option>
                <option>Credit Card</option>
                <option>Debit Card</option>
                <option>E-Wallet</option>
              </select>
            </div>
            <div class="col-md-3">
              <button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i> Search</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Payments Table -->
      <div class="card">
        <div class="table-wrap">
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Transaction ID</th>
                <th>Patient</th>
                <th>Amount</th>
                <th>Payment Method</th>
                <th>Date</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><strong>#TRX-2024-001</strong></td>
                <td>John Smith</td>
                <td><strong>$50.00</strong></td>
                <td>Credit Card</td>
                <td>Dec 15, 2024</td>
                <td><span class="badge bg-success">Completed</span></td>
                <td><button class="btn btn-action btn-view">👁️</button></td>
              </tr>
              <tr>
                <td><strong>#TRX-2024-002</strong></td>
                <td>Sarah Wilson</td>
                <td><strong>$75.00</strong></td>
                <td>Debit Card</td>
                <td>Dec 14, 2024</td>
                <td><span class="badge bg-success">Completed</span></td>
                <td><button class="btn btn-action btn-view">👁️</button></td>
              </tr>
              <tr>
                <td><strong>#TRX-2024-003</strong></td>
                <td>Michael Brown</td>
                <td><strong>$60.00</strong></td>
                <td>E-Wallet</td>
                <td>Dec 13, 2024</td>
                <td><span class="badge bg-warning">Pending</span></td>
                <td><button class="btn btn-action btn-view">👁️</button></td>
              </tr>
              <tr>
                <td><strong>#TRX-2024-004</strong></td>
                <td>Emma Davis</td>
                <td><strong>$40.00</strong></td>
                <td>Credit Card</td>
                <td>Dec 12, 2024</td>
                <td><span class="badge bg-danger">Failed</span></td>
                <td><button class="btn btn-action btn-view">👁️</button></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
