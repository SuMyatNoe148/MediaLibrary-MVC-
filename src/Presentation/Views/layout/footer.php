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

    <?php if (isset($_SESSION['user_id'])): ?>
    <script>
    // Notification functionality
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBtn = document.getElementById('notificationBtn');
        const notificationDropdown = document.getElementById('notificationDropdown');
        const notificationList = document.getElementById('notificationList');
        const notificationBadge = document.getElementById('notificationBadge');
        const markAllReadBtn = document.getElementById('markAllRead');

        // Toggle dropdown
        notificationBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notificationDropdown.classList.toggle('show');
            if (notificationDropdown.classList.contains('show')) {
                loadNotifications();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!notificationDropdown.contains(e.target) && !notificationBtn.contains(e.target)) {
                notificationDropdown.classList.remove('show');
            }
        });

        // Load notifications
        function loadNotifications() {
            fetch('index.php?page=notifications-api')
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        notificationList.innerHTML = '<div class="notification-empty">Error loading notifications</div>';
                        return;
                    }

                    // Update badge
                    if (data.unread_count > 0) {
                        notificationBadge.textContent = data.unread_count;
                        notificationBadge.style.display = 'block';
                    } else {
                        notificationBadge.style.display = 'none';
                    }

                    // Render notifications
                    if (data.notifications.length === 0) {
                        notificationList.innerHTML = '<div class="notification-empty">No notifications</div>';
                    } else {
                        notificationList.innerHTML = data.notifications.map(notif => `
                            <div class="notification-item ${notif.is_read ? '' : 'unread'}" 
                                 onclick="handleNotificationClick(${notif.notification_id}, '${notif.link || ''}')">
                                <div class="notification-title">${escapeHtml(notif.title)}</div>
                                <div class="notification-message">${escapeHtml(notif.message)}</div>
                                <div class="notification-time">${formatTime(notif.created_at)}</div>
                            </div>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                    notificationList.innerHTML = '<div class="notification-empty">Error loading notifications</div>';
                });
        }

        // Mark all as read
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const formData = new FormData();
            fetch('index.php?page=notification-mark-all-read', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            });
        });

        // Handle notification click
        window.handleNotificationClick = function(notificationId, link) {
            const formData = new FormData();
            formData.append('notification_id', notificationId);
            
            fetch('index.php?page=notification-mark-read', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && link) {
                    window.location.href = link;
                } else {
                    loadNotifications();
                }
            });
        };

        // Helper functions
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatTime(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return Math.floor(diff / 60000) + ' min ago';
            if (diff < 86400000) return Math.floor(diff / 3600000) + ' hours ago';
            return date.toLocaleDateString();
        }

        // Initial load of unread count
        fetch('index.php?page=notifications-api')
            .then(response => response.json())
            .then(data => {
                if (data.unread_count > 0) {
                    notificationBadge.textContent = data.unread_count;
                    notificationBadge.style.display = 'block';
                }
            });
    });
    </script>
    <?php endif; ?>

</body>
</html>
