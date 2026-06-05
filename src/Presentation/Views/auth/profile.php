<?php 
require BASE_PATH . '/src/Presentation/Views/layout/header.php';
use MediaLibrary\Presentation\Views\ItemView;
?>

<div class="section page">
    <div class="wrapper">

        <div class="auth-header">
            <?= IconHelper::userCircle('auth-icon') ?>
            <h1>My Profile</h1>
            <p class="auth-subtitle">Manage your account and preferences</p>
        </div>

        <?php if (!empty($success_message)): ?>
            <p class="message success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <?php if (!empty($error_messages)): ?>
            <div class="message">
                <?php foreach ($error_messages as $error): ?>
                    <p><?= htmlspecialchars($error) ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Profile Stats -->
        <div class="profile-stats">
            <div class="stat-card">
                <?= IconHelper::calendar('stat-icon') ?>
                <span class="stat-number"><?= count($reservations) ?></span>
                <span class="stat-label">Reservations</span>
            </div>
        </div>

        <div class="profile-sections">
            <!-- Edit Profile -->
            <div class="profile-section">
                <h3><?= IconHelper::user('section-icon') ?> Edit Profile</h3>
                <form method="post" class="auth-form">
                    <input type="hidden" name="action" value="update_profile">

                    <div class="form-group has-icon">
                        <label><?= IconHelper::userCircle('form-icon') ?> Username</label>
                        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="form-group has-icon">
                        <label><?= IconHelper::mail('form-icon') ?> Email</label>
                        <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>

                    <div class="form-group has-icon">
                        <label><?= IconHelper::messageSquare('form-icon') ?> Bio</label>
                        <textarea name="bio" rows="3" placeholder="Tell us about yourself"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                    </div>

                    <input type="submit" value="Update Profile" class="btn">
                </form>
            </div>

            <!-- Change Password -->
            <div class="profile-section">
                <h3><?= IconHelper::key('section-icon') ?> Change Password</h3>
                <form method="post" class="auth-form">
                    <input type="hidden" name="action" value="change_password">

                    <div class="form-group has-icon">
                        <label><?= IconHelper::lock('form-icon') ?> Current Password</label>
                        <input type="password" name="current_password" required>
                    </div>

                    <div class="form-group has-icon">
                        <label><?= IconHelper::lock('form-icon') ?> New Password</label>
                        <input type="password" name="new_password" required minlength="6">
                    </div>

                    <div class="form-group has-icon">
                        <label><?= IconHelper::lock('form-icon') ?> Confirm New Password</label>
                        <input type="password" name="confirm_password" required minlength="6">
                    </div>

                    <input type="submit" value="Change Password" class="btn">
                </form>
            </div>
        </div>

        <!-- Recently Viewed -->
        <?php if (!empty($recentlyViewed)): ?>
        <div class="profile-section">
            <h3><?= IconHelper::history('section-icon') ?> Recently Viewed</h3>
            <ul class="catalog">
                <?php foreach ($recentlyViewed as $item): ?>
                    <?= ItemView::render($item); ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
