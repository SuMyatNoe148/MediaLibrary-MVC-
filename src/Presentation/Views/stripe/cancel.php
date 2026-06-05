<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>
<link rel="stylesheet" href="Public/assets/css/stripe.css">

<div class="payment-cancel-container">
    <div class="cancel-icon">❌</div>
    <h1>Payment Cancelled</h1>
    <p>Your payment was cancelled. You can try again anytime from your reservations page.</p>
    <a href="index.php?page=reservations" class="btn-return">View My Reservations</a>
    <a href="index.php?page=catalog" class="btn-reservations">Browse Catalog</a>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
