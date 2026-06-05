<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>
<link rel="stylesheet" href="Public/assets/css/stripe.css">

<div class="payment-success-container">
    <div class="success-icon">✅</div>
    <h1>Payment Successful!</h1>
    <p>Your payment has been processed successfully. Your reservation is now confirmed and marked as completed.</p>
    <div class="success-actions">
        <a href="index.php?page=reservations" class="btn-return">View My Reservations</a>
        <a href="index.php?page=catalog" class="btn-browse">Browse Catalog</a>
    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
