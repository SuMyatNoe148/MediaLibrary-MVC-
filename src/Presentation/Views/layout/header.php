<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once BASE_PATH . '/src/Presentation/Views/partials/icons.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Media Library') ?></title>

    <link rel="stylesheet" href="Public/assets/css/style.css">
    <?php if (isset($_GET['page']) && strpos($_GET['page'], 'admin') === 0): ?>
    <link rel="stylesheet" href="Public/assets/css/admin.css?v=5">
    <?php endif; ?>
    <?php if (isset($_GET['page']) && ($_GET['page'] === 'invoice-view' || $_GET['page'] === 'invoices')): ?>
    <link rel="stylesheet" href="/ITVisionHub/media_library/Public/assets/css/invoice.css?v=1">
    <?php endif; ?>
</head>
<body>

<div class="page-container">
<div class="content">

<header class="header">
    <div class="wrapper">

        <!-- LOGO -->
        <h1 class="logo">
            <a href="index.php">
                <img src="Public/assets/img/Brand-title.png" alt="Media Library">
            </a>
        </h1>

        <!-- NAVIGATION -->
        <ul class="nav">
            <li class="<?= ($section === 'books') ? 'on' : '' ?>">
                <a href="index.php?page=catalog&cat=books">
                    <?= IconHelper::book('nav-icon') ?> Books
                </a>
            </li>

            <li class="<?= ($section === 'movies') ? 'on' : '' ?>">
                <a href="index.php?page=catalog&cat=movies">
                    <?= IconHelper::film('nav-icon') ?> Movies
                </a>
            </li>

            <li class="<?= ($section === 'music') ? 'on' : '' ?>">
                <a href="index.php?page=catalog&cat=music">
                    <?= IconHelper::music('nav-icon') ?> Music
                </a>
            </li>

            <li class="<?= ($section === 'suggest') ? 'on' : '' ?>">
                <a href="index.php?page=suggest">
                    <?= IconHelper::messageSquare('nav-icon') ?> Suggest
                </a>
            </li>
        </ul>

        <!-- USER NAVIGATION -->
        <div class="user-nav">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Notification Bell -->
                <div class="notification-menu">
                    <button class="notification-btn" id="notificationBtn">
                        <?= IconHelper::bell('nav-icon-small') ?>
                        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
                    </button>
                    <div class="notification-dropdown" id="notificationDropdown">
                        <div class="notification-header">
                            <h3>Notifications</h3>
                            <a href="#" id="markAllRead" class="mark-read">Mark all as read</a>
                        </div>
                        <div class="notification-list" id="notificationList">
                            <div class="notification-loading">Loading...</div>
                        </div>
                        <div class="notification-footer">
                            <a href="<?= !empty($_SESSION['is_admin']) ? 'index.php?page=admin-messages' : 'index.php?page=notifications' ?>">View All</a>
                        </div>
                    </div>
                </div>

                <div class="user-menu">
                    <a href="index.php?page=profile" class="user-link">
                        <?= IconHelper::user('nav-icon-small') ?>
                        <span><?= htmlspecialchars($_SESSION['username']) ?></span>
                    </a>
                    <div class="user-dropdown">
                        <a href="index.php?page=profile"><?= IconHelper::settings('nav-icon-small') ?> Profile</a>
                        <?php if (empty($_SESSION['is_admin'])): ?>
                            <a href="index.php?page=reservations"><?= IconHelper::calendar('nav-icon-small') ?> Reservations</a>
                            <a href="index.php?page=invoices"><?= IconHelper::fileText('nav-icon-small') ?> Invoices</a>
                        <?php endif; ?>
                        <?php if (!empty($_SESSION['is_admin'])): ?>
                            <hr>
                            <a href="index.php?page=admin"><?= IconHelper::activity('nav-icon-small') ?> Admin Dashboard</a>
                        <?php endif; ?>
                        <hr>
                        <a href="index.php?page=logout"><?= IconHelper::logOut('nav-icon-small') ?> Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="index.php?page=login"><?= IconHelper::logIn('nav-icon-small') ?> Login</a>
                <a href="index.php?page=register"><?= IconHelper::userPlus('nav-icon-small') ?> Register</a>
            <?php endif; ?>
        </div>

    </div>
</header>

<!-- SEARCH BAR -->
 <?php if (empty($hideSearch)): ?>
<div class="search">
    <div class="wrapper">
        <form method="get" action="index.php">
            <input type="hidden" name="page" value="catalog">

            <?php if (!empty($section)): ?>
                <input type="hidden" name="cat" value="<?= htmlspecialchars($section) ?>">
            <?php endif; ?>

            <label for="s">Search:</label>
            <input type="text" name="s" id="s">
            <input type="submit" value="Go">
        </form>
    </div>
</div>
<?php endif; ?>

<main id="content">
