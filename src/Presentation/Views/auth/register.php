<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">

        <div class="auth-header">
            <?= IconHelper::library('auth-icon') ?>
            <h1>Join Media Library</h1>
            <p class="auth-subtitle">Create your free account to explore books, movies & music</p>
        </div>

        <?php if (!empty($error_messages)): ?>
            <div class="message">
                <?php foreach ($error_messages as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="auth-intro">Start your personal media collection today!</p>
        <?php endif; ?>

        <form method="post" class="auth-form" action="">

            <div class="form-group has-icon">
                <label for="username">
                    <?= IconHelper::userCircle('form-icon') ?> Username (required)
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= htmlspecialchars($username) ?>"
                    placeholder="Choose a username"
                    required
                    minlength="3"
                >
            </div>

            <div class="form-group has-icon">
                <label for="email">
                    <?= IconHelper::mail('form-icon') ?> Email Address (required)
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
                    <?= IconHelper::lock('form-icon') ?> Password (required, min 6 characters)
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a secure password"
                    required
                    minlength="6"
                    onkeyup="checkPasswordStrength()"
                >
                <div id="password-strength" class="password-strength"></div>
            </div>

            <div class="form-group has-icon">
                <label for="confirm_password">
                    <?= IconHelper::lock('form-icon') ?> Confirm Password (required)
                </label>
                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    required
                    minlength="6"
                >
            </div>

            <div class="form-group show-password-group">
                <label class="show-password-label">
                    <input type="checkbox" id="show-password" onclick="togglePasswords()">
                    <span>Show Passwords</span>
                </label>
            </div>

            <div class="form-group" style="display:none">
                <label for="address">Address</label>
                <input type="text" id="address" name="address">
                <p>Please leave this field blank</p>
            </div>

            <input type="submit" value="Register" class="btn">

        </form>

        <div class="auth-footer">
            <p class="auth-link">
                <?= IconHelper::logIn('link-icon') ?>
                Already have an account? <a href="index.php?page=login">Sign in</a>
            </p>
            <p class="auth-hint">By registering, you can save favorites, get recommendations, and suggest new media!</p>
        </div>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>

<script>
function togglePasswords() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm_password');
    const checkbox = document.getElementById('show-password');

    if (checkbox.checked) {
        passwordInput.type = 'text';
        confirmInput.type = 'text';
    } else {
        passwordInput.type = 'password';
        confirmInput.type = 'password';
    }
}

function checkPasswordStrength() {
    const password = document.getElementById('password').value;
    const strengthDiv = document.getElementById('password-strength');

    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;

    const labels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const colors = ['#e74c3c', '#e74c3c', '#f39c12', '#f1c40f', '#27ae60', '#27ae60'];

    strengthDiv.textContent = password.length > 0 ? 'Strength: ' + labels[strength] : '';
    strengthDiv.style.color = colors[strength];
}
</script>
