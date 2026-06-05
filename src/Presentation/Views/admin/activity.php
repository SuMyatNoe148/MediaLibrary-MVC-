<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="admin-header">
            <?= IconHelper::activity('auth-icon') ?>
            <h1>Activity Log</h1>
            <a href="index.php?page=admin" class="btn-back">&larr; Back to Dashboard</a>
        </div>

        <div class="admin-section">
            <h2>Recent User Activity</h2>
            
            <?php if (empty($activities)): ?>
                <p>No activity recorded.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User</th>
                            <th>Activity</th>
                            <th>Media</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($activities as $activity): ?>
                            <tr>
                                <td><?= date('M d, Y H:i', strtotime($activity['created_at'])) ?></td>
                                <td><?= htmlspecialchars($activity['username']) ?></td>
                                <td>
                                    <span class="activity-badge <?= strtolower($activity['activity_type']) ?>">
                                        <?= htmlspecialchars(str_replace('_', ' ', $activity['activity_type'])) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($activity['media_title']): ?>
                                        <a href="index.php?page=details&id=<?= $activity['media_id'] ?>">
                                            <?= htmlspecialchars($activity['media_title']) ?>
                                        </a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($activity['details'] ?? '-') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
