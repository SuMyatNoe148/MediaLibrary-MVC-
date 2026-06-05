    </div><!-- end content -->

    <?php
    // Check if on admin page
    $isAdminPage = isset($_GET['page']) && strpos($_GET['page'], 'admin') === 0;
    ?>

    <footer class="footer <?= $isAdminPage ? 'footer-simple' : '' ?>">
        <div class="wrapper">
            <?php if ($isAdminPage): ?>
                <!-- Simple footer for admin pages -->
                <div class="footer-bottom" style="border-top: none; padding-top: 0;">
                    <p>&copy; <?php echo date("Y"); ?> Personal Media Library. Admin Panel.</p>
                </div>
            <?php else: ?>
                <!-- Full footer for user pages -->
                <div class="footer-content">
                    <div class="footer-section">
                        <h4>Media Library</h4>
                        <p>Your personal collection of books, movies, and music. Discover, reserve, and enjoy your media.</p>
                    </div>
                    <div class="footer-section">
                        <h4>Quick Links</h4>
                        <ul>
                            <li><a href="index.php">Home</a></li>
                            <li><a href="index.php?page=catalog">Catalog</a></li>
                            <li><a href="index.php?page=suggest">Suggest Media</a></li>
                        </ul>
                    </div>
                    <div class="footer-section">
                        <h4>Account</h4>
                        <ul>
                            <li><a href="index.php?page=profile">My Profile</a></li>
                            <li><a href="index.php?page=reservations">My Reservations</a></li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    <p>&copy; <?php echo date("Y"); ?> Personal Media Library. All rights reserved.</p>
                </div>
            <?php endif; ?>
        </div>
    </footer>

</body>
</html>
