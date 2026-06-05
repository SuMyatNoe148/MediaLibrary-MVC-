<?php require_once BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>
<link rel="stylesheet" href="Public/assets/css/reservations.css">

<?php if (isset($_GET['success'])): ?>
    <div class="alert alert-success">
        ✓ Reservation created successfully.
    </div>
<?php endif; ?>


<div class="reservation-dashboard">
    <div class="reservation-header">
        <h1>Reservation History</h1>
        <p>Track and manage your media reservations</p>
    </div>

    <div class="reservation-filters">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="reservations">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search by title..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="filter-group">
                    <label for="payment_status">Payment Status</label>
                    <select id="payment_status" name="payment_status">
                        <option value="">All</option>
                        <option value="pending" <?= (($_GET['payment_status'] ?? '') === 'pending' ? 'selected' : '') ?>>Pending</option>
                        <option value="completed" <?= (($_GET['payment_status'] ?? '') === 'completed' ? 'selected' : '') ?>>Completed</option>
                        <option value="failed" <?= (($_GET['payment_status'] ?? '') === 'failed' ? 'selected' : '') ?>>Failed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="reservation_status">Reservation Status</label>
                    <select id="reservation_status" name="reservation_status">
                        <option value="">All</option>
                        <option value="pending" <?= (($_GET['reservation_status'] ?? '') === 'pending' ? 'selected' : '') ?>>Pending</option>
                        <option value="confirmed" <?= (($_GET['reservation_status'] ?? '') === 'confirmed' ? 'selected' : '') ?>>Confirmed</option>
                        <option value="cancelled" <?= (($_GET['reservation_status'] ?? '') === 'cancelled' ? 'selected' : '') ?>>Cancelled</option>
                        <option value="completed" <?= (($_GET['reservation_status'] ?? '') === 'completed' ? 'selected' : '') ?>>Completed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-pay" style="margin-top: 0;">Filter</button>
                    <a href="index.php?page=reservations" class="btn-pay" style="background: #95a5a6; margin-left: 10px;">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <?php if (empty($reservations)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📚</div>
            <h3>No Reservations Found</h3>
            <p>You haven't made any reservations yet.</p>
            <a href="index.php?page=catalog" class="btn-pay">Browse Catalog</a>
        </div>
    <?php else: ?>
        <div class="reservation-table-container">
            <table class="reservation-table">
                <thead>
                    <tr>
                        <th>Media Title</th>
                        <th>Category</th>
                        <th>Days Reserved</th>
                        <th>Amount</th>
                        <th>Payment Status</th>
                        <th>Reservation Status</th>
                        <th>Action</th>
                        <th>Requested Date</th>
                        <th>Processed Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <?php
                            $daysReserved = 1;
                            if (!empty($reservation['reservation_date']) && !empty($reservation['created_at'])) {
                                $reqDate = new DateTime($reservation['reservation_date']);
                                $createdDate = new DateTime($reservation['created_at']);
                                $daysReserved = $reqDate->diff($createdDate)->days + 1;
                            }
                            
                            $paymentStatus = $reservation['payment_status'] ?? 'pending';
                            $reservationStatus = $reservation['status'] ?? 'pending';
                        ?>
                        <tr>
                            <td>
                                <div class="media-title"><?= htmlspecialchars($reservation['media_title']) ?></div>
                            </td>
                            <td>
                                <span class="category"><?= htmlspecialchars($reservation['category']) ?></span>
                            </td>
                            <td><?= $daysReserved ?> Day<?= $daysReserved > 1 ? 's' : '' ?></td>
                            <td>$<?= number_format($reservation['amount'] ?? 0, 2) ?></td>
                            <td>
                                <?php if ($paymentStatus === 'completed'): ?>
                                    <span class="badge badge-completed">Completed</span>
                                <?php elseif ($paymentStatus === 'failed'): ?>
                                    <span class="badge badge-failed">Failed</span>
                                <?php else: ?>
                                    <span class="badge badge-pending">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($reservationStatus === 'confirmed' || $reservationStatus === 'completed'): ?>
                                    <span class="badge badge-approved">Approved</span>
                                <?php elseif ($reservationStatus === 'cancelled'): ?>
                                    <span class="badge badge-rejected">Cancelled</span>
                                <?php else: ?>
                                    <span class="badge badge-orange">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($reservationStatus === 'confirmed' && $paymentStatus === 'pending' && ($reservation['amount'] ?? 0) > 0): ?>
                                    <a href="index.php?page=stripe-checkout&reservation_id=<?= $reservation['reservation_id'] ?>" class="btn-pay">Pay Now</a>
                                <?php elseif ($paymentStatus === 'completed'): ?>
                                    <span class="paid-text">PAID</span>
                                <?php elseif ($reservationStatus === 'pending'): ?>
                                    <span class="waiting-text">Waiting Approval</span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, Y', strtotime($reservation['created_at'])) ?></td>
                            <td><?= !empty($reservation['processed_date']) ? date('M j, Y', strtotime($reservation['processed_date'])) : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
