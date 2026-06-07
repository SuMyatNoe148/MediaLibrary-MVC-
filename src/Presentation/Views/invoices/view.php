<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<style>
.invoice-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 40px 20px;
}
.invoice-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    padding: 50px;
}
.invoice-header {
    display: flex;
    justify-content: space-between;
    align-items: start;
    margin-bottom: 50px;
    padding-bottom: 30px;
    border-bottom: 3px solid #6366f1;
}
.invoice-logo {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 15px;
}
.invoice-logo img {
    height: 50px;
}
.invoice-title {
    font-size: 42px;
    font-weight: 800;
    color: #1e1b4b;
    margin: 0;
    letter-spacing: -1px;
}
.invoice-number {
    color: #64748b;
    font-size: 18px;
    margin: 5px 0 0 0;
    font-weight: 500;
}
.invoice-status {
    display: inline-block;
    padding: 10px 24px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 700;
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}
.invoice-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    margin-bottom: 40px;
}
.invoice-section-label {
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    margin: 0 0 20px 0;
}
.invoice-info-card {
    background: #f8fafc;
    padding: 25px;
    border-radius: 8px;
    border-left: 4px solid #6366f1;
}
.invoice-info-name {
    font-size: 18px;
    font-weight: 700;
    color: #1e1b4b;
    margin: 0 0 8px 0;
}
.invoice-info-detail {
    font-size: 14px;
    color: #64748b;
    margin: 0 0 5px 0;
}
.invoice-details-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-bottom: 40px;
    padding: 25px;
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 8px;
}
.invoice-detail-label {
    font-size: 12px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin: 0 0 8px 0;
}
.invoice-detail-value {
    font-size: 16px;
    font-weight: 600;
    color: #1e1b4b;
    margin: 0;
}
.invoice-table-container {
    margin-bottom: 40px;
}
.invoice-table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.invoice-table thead tr {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
}
.invoice-table th {
    padding: 18px 20px;
    text-align: left;
    font-weight: 700;
    color: white;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.invoice-table th:last-child {
    text-align: right;
}
.invoice-table tbody tr {
    background: white;
    border-bottom: 1px solid #e2e8f0;
}
.invoice-table td {
    padding: 20px;
    color: #1e1b4b;
    font-size: 16px;
    font-weight: 500;
}
.invoice-table td:last-child {
    text-align: right;
    font-weight: 700;
    font-size: 18px;
}
.invoice-item-title {
    font-weight: 700;
    margin-bottom: 5px;
}
.invoice-item-badge {
    font-size: 13px;
    color: #64748b;
    background: #f1f5f9;
    display: inline-block;
    padding: 4px 12px;
    border-radius: 12px;
}
.invoice-total-container {
    display: flex;
    justify-content: flex-end;
    margin-bottom: 40px;
}
.invoice-total-card {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    padding: 30px 40px;
    border-radius: 12px;
    min-width: 280px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.invoice-total-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
}
.invoice-total-label {
    color: #64748b;
    font-size: 15px;
    font-weight: 500;
}
.invoice-total-value {
    color: #1e1b4b;
    font-weight: 600;
    font-size: 15px;
}
.invoice-total-divider {
    padding-top: 15px;
    border-top: 2px solid #e2e8f0;
    margin-top: 15px;
}
.invoice-total-final-label {
    color: #1e1b4b;
    font-weight: 700;
    font-size: 17px;
}
.invoice-total-final-value {
    color: #6366f1;
    font-weight: 800;
    font-size: 28px;
}
.invoice-footer {
    text-align: center;
    padding-top: 30px;
    border-top: 2px solid #e2e8f0;
}
.invoice-footer-text {
    color: #64748b;
    font-size: 16px;
    margin: 0;
    font-weight: 500;
}
.invoice-footer-subtext {
    color: #94a3b8;
    font-size: 13px;
    margin: 10px 0 0 0;
}
.invoice-footer-timestamp {
    color: #94a3b8;
    font-size: 12px;
    margin: 5px 0 0 0;
}
.invoice-actions {
    display: flex;
    gap: 15px;
    margin-top: 40px;
    padding-top: 30px;
    border-top: 2px solid #e2e8f0;
}
.invoice-btn {
    flex: 1;
    padding: 14px 24px;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    font-size: 15px;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    display: block;
    text-align: center;
}
.invoice-btn-secondary {
    background: #f1f5f9;
    color: #475569;
}
.invoice-btn-primary {
    background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}
.invoice-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}
</style>

<div class="invoice-container">
    <div class="invoice-card">
        <!-- Header -->
        <div class="invoice-header">
            <div>
                <div class="invoice-logo">
                    <img src="Public/assets/img/Brand-title.png" alt="Media Library">
                </div>
                <h1 class="invoice-title">INVOICE</h1>
                <p class="invoice-number">#<?= htmlspecialchars($invoice['invoice_number']) ?></p>
            </div>
            <div style="text-align: right;">
                <div class="invoice-status"><?= htmlspecialchars($invoice['status']) ?></div>
            </div>
        </div>

        <!-- Company & Customer Info -->
        <div class="invoice-info-grid">
            <!-- From -->
            <div>
                <h3 class="invoice-section-label">From</h3>
                <div class="invoice-info-card">
                    <p class="invoice-info-name">Media Library</p>
                    <p class="invoice-info-detail">123 Library Street</p>
                    <p class="invoice-info-detail">New York, NY 10001</p>
                    <p class="invoice-info-detail">support@medialibrary.com</p>
                </div>
            </div>

            <!-- Bill To -->
            <div>
                <h3 class="invoice-section-label">Bill To</h3>
                <div class="invoice-info-card">
                    <p class="invoice-info-name"><?= htmlspecialchars($invoice['user_name']) ?></p>
                    <p class="invoice-info-detail"><?= htmlspecialchars($invoice['user_email']) ?></p>
                    <p class="invoice-info-detail">Customer ID: #<?= str_pad($invoice['user_id'], 5, '0', STR_PAD_LEFT) ?></p>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details-grid">
            <div>
                <p class="invoice-detail-label">Invoice Date</p>
                <p class="invoice-detail-value"><?= date('M j, Y', strtotime($invoice['created_at'])) ?></p>
            </div>
            <div>
                <p class="invoice-detail-label">Reservation Date</p>
                <p class="invoice-detail-value"><?= date('M j, Y', strtotime($invoice['reservation_date'])) ?></p>
            </div>
            <div>
                <p class="invoice-detail-label">Payment Method</p>
                <p class="invoice-detail-value">Stripe</p>
            </div>
        </div>

        <!-- Items Table -->
        <div class="invoice-table-container">
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="invoice-item-title"><?= htmlspecialchars($invoice['media_title']) ?></div>
                            <div class="invoice-item-badge">Media Reservation</div>
                        </td>
                        <td>$<?= number_format($invoice['amount'], 2) ?></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="invoice-total-container">
            <div class="invoice-total-card">
                <div class="invoice-total-row">
                    <span class="invoice-total-label">Subtotal</span>
                    <span class="invoice-total-value">$<?= number_format($invoice['amount'], 2) ?></span>
                </div>
                <div class="invoice-total-row">
                    <span class="invoice-total-label">Tax</span>
                    <span class="invoice-total-value">$0.00</span>
                </div>
                <div class="invoice-total-row invoice-total-divider">
                    <span class="invoice-total-final-label">Total</span>
                    <span class="invoice-total-final-value">$<?= number_format($invoice['amount'], 2) ?></span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p class="invoice-footer-text">Thank you for your payment!</p>
            <p class="invoice-footer-subtext">Media Library Management System</p>
            <p class="invoice-footer-timestamp">Generated on <?= date('F j, Y \a\t g:i A') ?></p>
        </div>

        <!-- Actions -->
        <div class="invoice-actions">
            <a href="index.php?page=invoices" class="invoice-btn invoice-btn-secondary">
                ← Back to Invoices
            </a>
            <a href="index.php?page=invoice-download&id=<?= $invoice['invoice_id'] ?>" class="invoice-btn invoice-btn-primary">
                Download PDF
            </a>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
