<?php require_once BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>
<link rel="stylesheet" href="Public/assets/css/admin-messages.css">

<div class="admin-messages-dashboard">
    <div class="admin-messages-header">
        <h1>Messages</h1>
        <a href="index.php?page=admin" class="btn-back">Back to Dashboard</a>
    </div>

    <div class="admin-messages-filters">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="admin-messages">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filter">Filter</label>
                    <select id="filter" name="filter">
                        <option value="">All Messages</option>
                        <option value="unread" <?= (($_GET['filter'] ?? '') === 'unread' ? 'selected' : '') ?>>Unread Only</option>
                        <option value="read" <?= (($_GET['filter'] ?? '') === 'read' ? 'selected' : '') ?>>Read Only</option>
                    </select>
                </div>
                <div class="filter-group filter-actions">
                    <button type="submit" class="btn-primary">Filter</button>
                    <a href="index.php?page=admin-messages" class="btn-secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <?php if (empty($messages)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📧</div>
            <h3>No Messages Found</h3>
            <p>There are no messages at this time.</p>
        </div>
    <?php else: ?>
        <div class="admin-messages-table-container">
            <table class="admin-messages-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>From</th>
                        <th>Subject</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($messages as $message):
                        $msgId = $message['message_id'] ?? $message['id'] ?? 0;
                    ?>
                        <tr class="<?= ($message['is_read'] ?? 0) == 0 ? 'unread' : '' ?>">
                            <td><?= $msgId ?></td>
                            <td><?= htmlspecialchars($message['username'] ?? 'Unknown') ?></td>
                            <td class="message-subject"><?= htmlspecialchars($message['subject'] ?? 'No Subject') ?></td>
                            <td class="message-preview"><?= htmlspecialchars(substr($message['message'] ?? '', 0, 60)) ?>...</td>
                            <td>
                                <?php if (($message['is_read'] ?? 0) == 0): ?>
                                    <span class="status-unread">Unread</span>
                                <?php else: ?>
                                    <span class="status-read">Read</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, Y g:i A', strtotime($message['created_at'])) ?></td>
                            <td>
                                <a href="index.php?page=admin-message-view&id=<?= $msgId ?>" class="btn-primary btn-sm">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>


<?php require_once BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
