    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS Files -->
    <script src="<?php echo ASSETS_PATH; ?>js/utils.js"></script>
    <script src="<?php echo ASSETS_PATH; ?>js/navigation.js"></script>
    
    <?php if (isset($page_js)): ?>
    <script src="<?php echo ASSETS_PATH; ?>js/<?php echo htmlspecialchars($page_js); ?>"></script>
    <?php endif; ?>
</body>
</html>
