<?php require_once BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>
<link rel="stylesheet" href="Public/assets/css/admin-reservations.css">

<div class="admin-reservation-dashboard">
    <div class="admin-reservation-header">
        <?= IconHelper::calendar('auth-icon') ?>
        <h1>Manage Reservations</h1>
        <a href="index.php?page=admin" class="btn-back">Back to Dashboard</a>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="admin-reservation-filters">
        <form method="GET" action="index.php" class="filter-form">
            <input type="hidden" name="page" value="admin-reservations">
            <div class="filter-group">
                <label for="search">Search</label>
                <input type="text" id="search" name="search" placeholder="Search by user or media..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <div class="filter-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">All</option>
                    <option value="pending" <?= (($_GET['status'] ?? '') === 'pending' ? 'selected' : '') ?>>Pending</option>
                    <option value="confirmed" <?= (($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '') ?>>Confirmed</option>
                    <option value="completed" <?= (($_GET['status'] ?? '') === 'completed' ? 'selected' : '') ?>>Completed</option>
                    <option value="cancelled" <?= (($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '') ?>>Cancelled</option>
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-primary">Filter</button>
                <a href="index.php?page=admin-reservations" class="btn-secondary">Clear</a>
            </div>
        </form>
    </div>

    <?php if (empty($reservations)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📅</div>
            <h3>No Reservations Found</h3>
            <p>There are no reservations to manage at this time.</p>
        </div>
    <?php else: ?>
        <div class="admin-reservation-table-container">
            <table class="admin-reservation-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Media</th>
                        <th>Category</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td><?= $reservation['reservation_id'] ?></td>
                            <td><?= htmlspecialchars($reservation['username']) ?></td>
                            <td><?= htmlspecialchars($reservation['media_title']) ?></td>
                            <td><?= htmlspecialchars($reservation['category']) ?></td>
                            <td><?= date('M j, Y', strtotime($reservation['reservation_date'])) ?></td>
                            <td>$<?= number_format($reservation['amount'] ?? 0, 2) ?></td>
                            <td>
                                <?php
                                    $paymentStatus = $reservation['payment_status'] ?? 'pending';
                                    if ($paymentStatus === 'completed'): ?>
                                    <span class="status-badge status-completed">Paid</span>
                                <?php elseif ($paymentStatus === 'failed'): ?>
                                    <span class="status-badge status-cancelled">Failed</span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="status-badge status-<?= $reservation['status'] ?>">
                                    <?= ucfirst($reservation['status']) ?>
                                </span>
                            </td>
                            <td><?= date('M j, Y', strtotime($reservation['created_at'])) ?></td>
                            <td>
                                <form method="post" action="index.php?page=admin-reservations" class="inline-form">
                                    <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id'] ?>">
                                    <input type="hidden" name="action" value="update_status">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="pending" <?= $reservation['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="confirmed" <?= $reservation['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                        <option value="completed" <?= $reservation['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </form>
                                <form method="post" action="index.php?page=admin-reservations" class="inline-form" onsubmit="return confirm('Delete this reservation?')">
                                    <input type="hidden" name="reservation_id" value="<?= $reservation['reservation_id'] ?>">
                                    <input type="hidden" name="action" value="delete_reservation">
                                    <button type="submit" class="btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
