<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="admin-content" style="margin-left: 0; padding: 40px;">
    <div class="reservation-header">
        <h1>Invoice Management</h1>
        <p>View and manage all system invoices</p>
        <div style="margin-top: 20px;">
            <a href="index.php?page=admin" class="btn-pay" style="background: #95a5a6;">← Back to Dashboard</a>
        </div>
    </div>

    <div class="reservation-filters">
        <form method="GET" action="index.php">
            <input type="hidden" name="page" value="admin-invoices">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" placeholder="Search by invoice number or user..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All</option>
                        <option value="PAID" <?= (($_GET['status'] ?? '') === 'PAID' ? 'selected' : '') ?>>PAID</option>
                        <option value="PENDING" <?= (($_GET['status'] ?? '') === 'PENDING' ? 'selected' : '') ?>>PENDING</option>
                        <option value="CANCELLED" <?= (($_GET['status'] ?? '') === 'CANCELLED' ? 'selected' : '') ?>>CANCELLED</option>
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-pay" style="margin-top: 0;">Filter</button>
                    <a href="index.php?page=admin-invoices" class="btn-pay" style="background: #95a5a6; margin-left: 10px;">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <?php if (empty($invoices)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📄</div>
            <h3>No Invoices Found</h3>
            <p>No invoices have been generated yet.</p>
        </div>
    <?php else: ?>
        <div class="reservation-table-container">
            <table class="reservation-table">
                <thead>
                    <tr>
                        <th>Invoice No</th>
                        <th>User</th>
                        <th>Media</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Invoice Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr>
                            <td>
                                <div class="media-title"><?= htmlspecialchars($invoice['invoice_number']) ?></div>
                            </td>
                            <td>
                                <div><?= htmlspecialchars($invoice['user_name']) ?></div>
                                <small class="text-muted"><?= htmlspecialchars($invoice['user_email']) ?></small>
                            </td>
                            <td>
                                <span class="category"><?= htmlspecialchars($invoice['media_title']) ?></span>
                            </td>
                            <td>
                                <strong>$<?= number_format($invoice['amount'], 2) ?></strong>
                            </td>
                            <td>
                                <?php if ($invoice['status'] === 'PAID'): ?>
                                    <span class="badge badge-completed">PAID</span>
                                <?php elseif ($invoice['status'] === 'PENDING'): ?>
                                    <span class="badge badge-pending">PENDING</span>
                                <?php else: ?>
                                    <span class="badge badge-rejected"><?= htmlspecialchars($invoice['status']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= date('M j, Y', strtotime($invoice['created_at'])) ?></td>
                            <td>
                                <a href="index.php?page=invoice-view&id=<?= $invoice['invoice_id'] ?>" class="btn-pay" style="padding: 8px 16px; font-size: 13px;">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
