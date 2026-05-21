<?php
/**
 * Admin Topbar Component
 * Top bar for admin pages with title and user info
 */
$page_title = isset($page_title) ? $page_title : 'Dashboard';
$user_initial = isset($user_name) ? substr($user_name, 0, 1) : 'A';
?>
<div class="topbar">
  <h1 class="topbar-title"><?php echo htmlspecialchars($page_title); ?></h1>
  <div class="topbar-right">
    <div class="admin-avatar" title="Admin User">
      <i class="bi bi-person-circle"></i>
    </div>
    <span class="admin-name">Admin User</span>
  </div>
</div>
