<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="auth-header">
            <?= IconHelper::library('auth-icon') ?>
            <h1>Reset Password</h1>
            <p class="auth-subtitle">Enter your email to receive a password reset link</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <form method="post" class="auth-form" action="">

            <div class="form-group has-icon">
                <label for="email">
                    <?= IconHelper::mail('form-icon') ?> Email Address
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($email) ?>"
                    placeholder="your@email.com"
                    required
                >
            </div>

            <input type="submit" value="Send Reset Link" class="btn">

        </form>

        <div class="auth-footer">
            <p class="auth-link">
                <?= IconHelper::logIn('link-icon') ?>
                Remember your password? <a href="index.php?page=login">Sign in</a>
            </p>
        </div>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
