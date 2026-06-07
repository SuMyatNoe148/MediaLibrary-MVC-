<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="section-header">
            <h1>Notifications</h1>
            <p class="section-subtitle">Stay updated with your activity</p>
        </div>

        <?php if (empty($notifications)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🔔</div>
                <h3>No Notifications</h3>
                <p>You don't have any notifications yet.</p>
            </div>
        <?php else: ?>
            <div class="notifications-container">
                <?php foreach ($notifications as $notification): ?>
                    <div class="notification-card <?= $notification['is_read'] ? 'read' : 'unread' ?>">
                        <div class="notification-content">
                            <h3 class="notification-title"><?= htmlspecialchars($notification['title']) ?></h3>
                            <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                            <p class="notification-time"><?= date('M j, Y \a\t g:i A', strtotime($notification['created_at'])) ?></p>
                        </div>
                        <div class="notification-actions">
                            <?php if (!$notification['is_read']): ?>
                                <form method="POST" action="index.php?page=notification-mark-read" style="display: inline;">
                                    <input type="hidden" name="notification_id" value="<?= $notification['notification_id'] ?>">
                                    <button type="submit" class="btn-small">Mark as Read</button>
                                </form>
                            <?php endif; ?>
                            <?php if ($notification['link']): ?>
                                <a href="<?= htmlspecialchars($notification['link']) ?>" class="btn-small btn-primary">View</a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>

<style>
.notifications-container {
    max-width: 800px;
    margin: 0 auto;
}

.notification-card {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s;
}

.notification-card.unread {
    background: #f0f9ff;
    border-left: 4px solid #6366f1;
}

.notification-card.read {
    opacity: 0.8;
}

.notification-content {
    flex: 1;
}

.notification-title {
    font-size: 16px;
    font-weight: 600;
    color: #1e1b4b;
    margin: 0 0 8px 0;
}

.notification-message {
    font-size: 14px;
    color: #64748b;
    margin: 0 0 8px 0;
}

.notification-time {
    font-size: 12px;
    color: #94a3b8;
    margin: 0;
}

.notification-actions {
    display: flex;
    gap: 10px;
    margin-left: 20px;
}

.btn-small {
    padding: 8px 16px;
    font-size: 13px;
    border: 1px solid #e2e8f0;
    background: white;
    color: #64748b;
    border-radius: 6px;
    cursor: pointer;
    text-decoration: none;
}

.btn-small:hover {
    background: #f8fafc;
    border-color: #cbd5e1;
}

.btn-small.btn-primary {
    background: #6366f1;
    color: white;
    border-color: #6366f1;
}

.btn-small.btn-primary:hover {
    background: #4f46e5;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
}

.empty-state-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.empty-state h3 {
    font-size: 20px;
    color: #1e1b4b;
    margin: 0 0 10px 0;
}

.empty-state p {
    color: #64748b;
    margin: 0;
}
</style>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
