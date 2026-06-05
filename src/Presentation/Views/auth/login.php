<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="auth-header">
            <?= IconHelper::library('auth-icon') ?>
            <h1>Welcome Back</h1>
            <p class="auth-subtitle">Sign in to access your Media Library</p>
        </div>

        <?php if (isset($_GET['required']) && $_GET['required'] === '1'): ?>
            <p class="message">Please sign in to access the Media Library.</p>
        <?php endif; ?>

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

            <div class="form-group has-icon">
                <label for="password">
                    <?= IconHelper::lock('form-icon') ?> Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >
            </div>

            <div class="form-group show-password-group">
                <label class="show-password-label">
                    <input type="checkbox" id="show-password" onclick="togglePassword('password')">
                    <span>Show Password</span>
                </label>
            </div>

            <div class="form-group" style="display:none">
                <label for="address">Address</label>
                <input type="text" id="address" name="address">
                <p>Please leave this field blank</p>
            </div>

            <input type="submit" value="Login" class="btn">

        </form>

        <div class="auth-footer">
            <p class="auth-link">
                <?= IconHelper::key('link-icon') ?>
                <a href="index.php?page=forgot-password">Forgot password?</a>
            </p>
            <p class="auth-link">
                <?= IconHelper::userPlus('link-icon') ?>
                Don't have an account? <a href="index.php?page=register">Create one now</a>
            </p>
            <p class="auth-hint">Join our community to save favorites and get personalized recommendations!</p>
        </div>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>

<script>
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
}
</script>
