<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="auth-header">
            <?= IconHelper::library('auth-icon') ?>
            <h1>Create New Password</h1>
            <p class="auth-subtitle">Enter your new password below</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?> <a href="index.php?page=login">Login now</a></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="message"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <?php if (empty($success_message) && !empty($tokenData)): ?>
        <form method="post" class="auth-form" action="">

            <div class="form-group has-icon">
                <label for="password">
                    <?= IconHelper::lock('form-icon') ?> New Password
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter new password"
                    required
                    minlength="6"
                >
            </div>

            <div class="form-group has-icon">
                <label for="confirm_password">
                    <?= IconHelper::lock('form-icon') ?> Confirm New Password
                </label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm new password"
                    required
                    minlength="6"
                >
            </div>

            <div class="form-group show-password-group">
                <label class="show-password-label">
                    <input type="checkbox" onclick="togglePasswords()">
                    <span>Show Passwords</span>
                </label>
            </div>

            <input type="submit" value="Reset Password" class="btn">

        </form>

        <script>
        function togglePasswords() {
            const p1 = document.getElementById('password');
            const p2 = document.getElementById('confirm_password');
            const type = p1.type === 'password' ? 'text' : 'password';
            p1.type = type;
            p2.type = type;
        }
        </script>
        <?php endif; ?>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
