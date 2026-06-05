<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="admin-header">
            <?= IconHelper::settings('auth-icon') ?>
            <h1>Admin Dashboard</h1>
            <p class="admin-subtitle">Manage your media library system</p>
        </div>

        <!-- Stats Cards - Clickable -->
        <div class="admin-stats">
            <a href="index.php?page=admin-users" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon users"><?= IconHelper::user('icon') ?></div>
                    <div class="stat-info">
                        <h3><?= $stats['total_users'] ?></h3>
                        <p>User Management</p>
                        <span class="stat-action">Manage &rarr;</span>
                    </div>
                </div>
            </a>
            <a href="index.php?page=admin-catalog" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon media"><?= IconHelper::library('icon') ?></div>
                    <div class="stat-info">
                        <h3><?= $stats['total_media'] ?></h3>
                        <p>Media Management</p>
                        <span class="stat-action">Manage &rarr;</span>
                    </div>
                </div>
            </a>
            <a href="index.php?page=admin-reservations" class="stat-card-link">
                <div class="stat-card">
                    <div class="stat-icon reservations"><?= IconHelper::calendar('icon') ?></div>
                    <div class="stat-info">
                        <h3><?= $stats['total_reservations'] ?? 0 ?></h3>
                        <p>Reservation Management</p>
                        <span class="stat-action">Manage &rarr;</span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Quick Actions -->
        <div class="admin-section">
            <h2>Quick Actions</h2>
            <div class="admin-actions">
                <a href="index.php?page=admin-users" class="admin-btn">
                    <?= IconHelper::user('nav-icon-small') ?>
                    User Management
                </a>
                <a href="index.php?page=admin-catalog" class="admin-btn">
                    <?= IconHelper::book('nav-icon-small') ?>
                    Media Management
                </a>
                <a href="index.php?page=admin-reservations" class="admin-btn">
                    <?= IconHelper::calendar('nav-icon-small') ?>
                    Reservation Management
                </a>
            </div>
        </div>

        <!-- Mail Notifications -->
        <div class="admin-section">
            <h2>Mail Notifications</h2>
            <div class="mail-notifications">
                <?php if (empty($stats['unread_messages'])): ?>
                    <p>No new messages.</p>
                <?php else: ?>
                    <div class="notification-summary">
                        <span class="notification-count"><?= $stats['unread_messages'] ?></span>
                        <span>new message<?= $stats['unread_messages'] > 1 ? 's' : '' ?></span>
                    </div>
                    <a href="index.php?page=admin-messages" class="admin-btn" style="margin-top: 15px;">
                        <?= IconHelper::mail('nav-icon-small') ?>
                        View Messages
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="admin-section">
            <h2>Recent Users</h2>
            <?php if (empty($stats['recent_users'])): ?>
                <p>No users yet.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats['recent_users'] as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Recent Activity -->
        <div class="admin-section">
            <h2>Recent Activity</h2>
            <?php if (empty($stats['recent_activity'])): ?>
                <p>No recent activity.</p>
            <?php else: ?>
                <ul class="activity-list">
                    <?php foreach ($stats['recent_activity'] as $activity): ?>
                        <li class="activity-item">
                            <span class="activity-time"><?= date('M d, H:i', strtotime($activity['created_at'])) ?></span>
                            <span class="activity-user"><?= htmlspecialchars($activity['username']) ?></span>
                            <span class="activity-type"><?= htmlspecialchars(str_replace('_', ' ', $activity['activity_type'])) ?></span>
                            <?php if ($activity['media_title']): ?>
                                <span class="activity-media"><?= htmlspecialchars($activity['media_title']) ?></span>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
