<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<div id="invoice-content" style="max-width: 900px; margin: 0 auto; padding: 40px 20px; background: white; font-family: Arial, sans-serif;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 50px; padding-bottom: 30px; border-bottom: 3px solid #6366f1;">
        <div>
            <h1 style="font-size: 42px; font-weight: 800; color: #1e1b4b; margin: 0; letter-spacing: -1px; font-family: Arial, sans-serif;">INVOICE</h1>
            <p style="color: #64748b; font-size: 18px; margin: 5px 0 0 0; font-weight: 500; font-family: Arial, sans-serif;">#<?= htmlspecialchars($invoice['invoice_number']) ?></p>
        </div>
        <div style="text-align: right;">
            <div style="display: inline-block; padding: 10px 24px; border-radius: 25px; font-size: 14px; font-weight: 700; background: #10b981; color: white; font-family: Arial, sans-serif;">
                <?= htmlspecialchars($invoice['status']) ?>
            </div>
        </div>
    </div>

    <!-- Company & Customer Info -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 40px; font-family: Arial, sans-serif;">
        <!-- From -->
        <div style="width: 48%;">
            <h3 style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin: 0 0 20px 0; font-family: Arial, sans-serif;">From</h3>
            <div style="background: #f8fafc; padding: 25px; border-left: 4px solid #6366f1;">
                <p style="font-size: 18px; font-weight: 700; color: #1e1b4b; margin: 0 0 8px 0; font-family: Arial, sans-serif;">Media Library</p>
                <p style="font-size: 14px; color: #64748b; margin: 0 0 5px 0; font-family: Arial, sans-serif;">123 Library Street</p>
                <p style="font-size: 14px; color: #64748b; margin: 0 0 5px 0; font-family: Arial, sans-serif;">New York, NY 10001</p>
                <p style="font-size: 14px; color: #64748b; margin: 0; font-family: Arial, sans-serif;">support@medialibrary.com</p>
            </div>
        </div>

        <!-- Bill To -->
        <div style="width: 48%;">
            <h3 style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin: 0 0 20px 0; font-family: Arial, sans-serif;">Bill To</h3>
            <div style="background: #f8fafc; padding: 25px; border-left: 4px solid #6366f1;">
                <p style="font-size: 18px; font-weight: 700; color: #1e1b4b; margin: 0 0 8px 0; font-family: Arial, sans-serif;"><?= htmlspecialchars($invoice['user_name']) ?></p>
                <p style="font-size: 14px; color: #64748b; margin: 0 0 5px 0; font-family: Arial, sans-serif;"><?= htmlspecialchars($invoice['user_email']) ?></p>
                <p style="font-size: 14px; color: #64748b; margin: 0; font-family: Arial, sans-serif;">Customer ID: #<?= str_pad($invoice['user_id'], 5, '0', STR_PAD_LEFT) ?></p>
            </div>
        </div>
    </div>

    <!-- Invoice Details -->
    <div style="display: flex; justify-content: space-between; margin-bottom: 40px; padding: 25px; background: #f8fafc; font-family: Arial, sans-serif;">
        <div style="width: 33%;">
            <p style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 8px 0; font-family: Arial, sans-serif;">Invoice Date</p>
            <p style="font-size: 16px; font-weight: 600; color: #1e1b4b; margin: 0; font-family: Arial, sans-serif;"><?= date('M j, Y', strtotime($invoice['created_at'])) ?></p>
        </div>
        <div style="width: 33%;">
            <p style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 8px 0; font-family: Arial, sans-serif;">Reservation Date</p>
            <p style="font-size: 16px; font-weight: 600; color: #1e1b4b; margin: 0; font-family: Arial, sans-serif;"><?= date('M j, Y', strtotime($invoice['reservation_date'])) ?></p>
        </div>
        <div style="width: 33%;">
            <p style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 8px 0; font-family: Arial, sans-serif;">Payment Method</p>
            <p style="font-size: 16px; font-weight: 600; color: #1e1b4b; margin: 0; font-family: Arial, sans-serif;">Stripe</p>
        </div>
    </div>

    <!-- Items Table -->
    <div style="margin-bottom: 40px;">
        <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif;">
            <thead>
                <tr style="background: #6366f1;">
                    <th style="padding: 18px 20px; text-align: left; font-weight: 700; color: white; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; font-family: Arial, sans-serif;">Description</th>
                    <th style="padding: 18px 20px; text-align: right; font-weight: 700; color: white; font-size: 14px; text-transform: uppercase; letter-spacing: 0.5px; font-family: Arial, sans-serif;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr style="background: white; border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 20px; color: #1e1b4b; font-size: 16px; font-weight: 500; font-family: Arial, sans-serif;">
                        <div style="font-weight: 700; margin-bottom: 5px; font-family: Arial, sans-serif;"><?= htmlspecialchars($invoice['media_title']) ?></div>
                        <div style="font-size: 13px; color: #64748b; background: #f1f5f9; display: inline-block; padding: 4px 12px; font-family: Arial, sans-serif;">Media Reservation</div>
                    </td>
                    <td style="padding: 20px; text-align: right; font-weight: 700; color: #1e1b4b; font-size: 18px; font-family: Arial, sans-serif;">
                        $<?= number_format($invoice['amount'], 2) ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Total -->
    <div style="display: flex; justify-content: flex-end; margin-bottom: 40px;">
        <div style="background: #f8fafc; padding: 30px 40px; min-width: 280px; font-family: Arial, sans-serif;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span style="color: #64748b; font-size: 15px; font-weight: 500; font-family: Arial, sans-serif;">Subtotal</span>
                <span style="color: #1e1b4b; font-weight: 600; font-size: 15px; font-family: Arial, sans-serif;">$<?= number_format($invoice['amount'], 2) ?></span>
            </div>
            <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                <span style="color: #64748b; font-size: 15px; font-weight: 500; font-family: Arial, sans-serif;">Tax</span>
                <span style="color: #1e1b4b; font-weight: 600; font-size: 15px; font-family: Arial, sans-serif;">$0.00</span>
            </div>
            <div style="display: flex; justify-content: space-between; padding-top: 15px; border-top: 2px solid #e2e8f0; margin-top: 15px;">
                <span style="color: #1e1b4b; font-weight: 700; font-size: 17px; font-family: Arial, sans-serif;">Total</span>
                <span style="color: #6366f1; font-weight: 800; font-size: 28px; font-family: Arial, sans-serif;">$<?= number_format($invoice['amount'], 2) ?></span>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div style="text-align: center; padding-top: 30px; border-top: 2px solid #e2e8f0; font-family: Arial, sans-serif;">
        <p style="color: #64748b; font-size: 16px; margin: 0; font-weight: 500; font-family: Arial, sans-serif;">Thank you for your payment!</p>
        <p style="color: #94a3b8; font-size: 13px; margin: 10px 0 0 0; font-family: Arial, sans-serif;">Media Library Management System</p>
        <p style="color: #94a3b8; font-size: 12px; margin: 5px 0 0 0; font-family: Arial, sans-serif;">Generated on <?= date('F j, Y \a\t g:i A') ?></p>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const element = document.getElementById('invoice-content');
    const invoiceNumber = '<?= htmlspecialchars($invoice['invoice_number']) ?>';
    
    const opt = {
        margin: [10, 10, 10, 10],
        filename: 'Invoice_' + invoiceNumber + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { 
            scale: 2,
            useCORS: true,
            letterRendering: true
        },
        jsPDF: { 
            unit: 'mm', 
            format: 'a4', 
            orientation: 'portrait',
            compress: true
        },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    html2pdf().set(opt).from(element).save();
});
</script>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
