<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="admin-header">
            <?= IconHelper::messageSquare('auth-icon') ?>
            <h1>Manage Reviews</h1>
            <a href="index.php?page=admin" class="btn-back">&larr; Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <p class="message success"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="admin-section">
            <h2>All Reviews (<?= count($reviews) ?>)</h2>
            
            <?php if (empty($reviews)): ?>
                <p>No reviews found.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Media</th>
                            <th>Review</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reviews as $review): ?>
                            <tr>
                                <td><?= $review['review_id'] ?></td>
                                <td><?= htmlspecialchars($review['username']) ?></td>
                                <td>
                                    <a href="index.php?page=details&id=<?= $review['media_id'] ?>">
                                        <?= htmlspecialchars($review['media_title']) ?>
                                    </a>
                                </td>
                                <td><?= nl2br(htmlspecialchars(substr($review['review_text'], 0, 100))) ?>...</td>
                                <td><?= date('M d, Y', strtotime($review['created_at'])) ?></td>
                                <td>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="review_id" value="<?= $review['review_id'] ?>">
                                        <input type="hidden" name="action" value="delete_review">
                                        <button type="submit" class="btn-small btn-danger" 
                                            onclick="return confirm('Delete this review?')">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
