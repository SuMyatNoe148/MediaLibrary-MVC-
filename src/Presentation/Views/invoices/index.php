<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 40px 20px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 600; color: #333;">My Invoices</h1>
    </div>

    <?php if (empty($invoices)): ?>
        <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 8px;">
            <p style="color: #666; font-size: 16px;">No invoices found.</p>
            <a href="index.php?page=catalog" style="display: inline-block; margin-top: 20px; padding: 10px 20px; background: #6366f1; color: white; text-decoration: none; border-radius: 6px;">Browse Media</a>
        </div>
    <?php else: ?>
        <div style="background: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); overflow: hidden;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                        <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #495057;">Invoice No</th>
                        <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #495057;">Media</th>
                        <th style="padding: 15px 20px; text-align: right; font-weight: 600; color: #495057;">Amount</th>
                        <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #495057;">Status</th>
                        <th style="padding: 15px 20px; text-align: left; font-weight: 600; color: #495057;">Date</th>
                        <th style="padding: 15px 20px; text-align: center; font-weight: 600; color: #495057;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($invoices as $invoice): ?>
                        <tr style="border-bottom: 1px solid #e9ecef;">
                            <td style="padding: 15px 20px; font-weight: 500; color: #333;">
                                <?= htmlspecialchars($invoice['invoice_number']) ?>
                            </td>
                            <td style="padding: 15px 20px; color: #666;">
                                <?= htmlspecialchars($invoice['media_title']) ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: right; font-weight: 600; color: #333;">
                                $<?= number_format($invoice['amount'], 2) ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <span style="display: inline-block; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; background: #d1fae5; color: #065f46;">
                                    <?= htmlspecialchars($invoice['status']) ?>
                                </span>
                            </td>
                            <td style="padding: 15px 20px; color: #666;">
                                <?= date('M j, Y', strtotime($invoice['created_at'])) ?>
                            </td>
                            <td style="padding: 15px 20px; text-align: center;">
                                <a href="index.php?page=invoice-view&id=<?= $invoice['invoice_id'] ?>" 
                                   style="display: inline-block; padding: 8px 16px; background: #6366f1; color: white; text-decoration: none; border-radius: 6px; font-size: 14px;">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
