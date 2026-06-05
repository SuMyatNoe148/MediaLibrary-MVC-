<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="admin-header">
            <?= IconHelper::user('auth-icon') ?>
            <h1>Manage Users</h1>
            <a href="index.php?page=admin" class="btn-back">&larr; Back to Dashboard</a>
        </div>

        <?php if ($message): ?>
            <p class="message success"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="admin-section">
            <h2>All Users (<?= count($users) ?>)</h2>
            
            <?php if (empty($users)): ?>
                <p>No users found.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Admin</th>
                            <th>Verified</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['user_id'] ?></td>
                                <td><?= htmlspecialchars($user['username']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <?php if ($user['is_admin']): ?>
                                        <span class="badge admin">Admin</span>
                                    <?php else: ?>
                                        <span class="badge user">User</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['is_verified']): ?>
                                        <span class="badge verified">Yes</span>
                                    <?php else: ?>
                                        <span class="badge">No</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                                <td>
                                    <form method="post" style="display: inline;">
                                        <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                        <input type="hidden" name="action" value="toggle_admin">
                                        <button type="submit" class="btn-small" 
                                            onclick="return confirm('Toggle admin status for <?= htmlspecialchars($user['username']) ?>?')">
                                            <?= $user['is_admin'] ? 'Remove Admin' : 'Make Admin' ?>
                                        </button>
                                    </form>
                                    
                                    <?php if ($user['user_id'] !== $_SESSION['user_id']): ?>
                                        <form method="post" style="display: inline; margin-left: 5px;">
                                            <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                            <input type="hidden" name="action" value="delete_user">
                                            <button type="submit" class="btn-small btn-danger" 
                                                onclick="return confirm('Delete user <?= htmlspecialchars($user['username']) ?>? This cannot be undone.')">
                                                Delete
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
