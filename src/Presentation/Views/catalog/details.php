<?php require BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="section page">
    <div class="wrapper">
        
<?php require BASE_PATH . '/src/Presentation/Views/partials/breadcrumbs.php'; ?>
        <div class="media-container">

            <div class="media-picture">
            <img
                src="<?= htmlspecialchars($item['img']); ?>"
                alt="<?= htmlspecialchars($item["title"]); ?>"
            />
        </div>
            <div class="media-details">
                <h1><?= htmlspecialchars($item["title"]); ?></h1>

                <table>
                    <tr><th>Category</th><td><?= htmlspecialchars($item["category"]); ?></td></tr>
                    <tr><th>Genre</th><td><?= htmlspecialchars($item["genre"]); ?></td></tr>
                    <tr><th>Format</th><td><?= htmlspecialchars($item["format"]); ?></td></tr>
                    <tr><th>Year</th><td><?= htmlspecialchars($item["year"]); ?></td></tr>
                    <tr><th>Price</th><td>$<?= number_format($item['price'] ?? 0.00, 2) ?></td></tr>

                    <?php if (strtolower($item["category"]) === "books"): ?>
                        <tr><th>Authors</th><td><?= implode(", ", $item["author"] ?? []); ?></td></tr>
                        <tr><th>Publisher</th><td><?= htmlspecialchars($item["publisher"]); ?></td></tr>
                        <tr><th>ISBN</th><td><?= htmlspecialchars($item["isbn"]); ?></td></tr>

                    <?php elseif (strtolower($item["category"]) === "movies"): ?>
                        <tr><th>Director</th><td><?= implode(", ", $item["director"] ?? []); ?></td></tr>
                        <tr><th>Stars</th><td><?= implode(", ", $item["star"] ?? []); ?></td></tr>

                    <?php elseif (strtolower($item["category"]) === "music"): ?>
                        <tr><th>Artist</th><td><?= implode(", ", $item["artist"] ?? []); ?></td></tr>
                    <?php endif; ?>
                </table>

                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="media-actions">
                    <a href="index.php?page=create-reservation&media_id=<?= $item['media_id'] ?>" class="btn btn-primary">
                        Reserve This Item
                    </a>
                </div>
                <?php else: ?>
                <div class="media-actions">
                    <a href="index.php?page=login&required=1" class="btn btn-secondary">
                        Login to Reserve
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
