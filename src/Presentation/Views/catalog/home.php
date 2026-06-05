<?php 
require BASE_PATH . '/src/Presentation/Views/layout/header.php';
use MediaLibrary\Presentation\Views\ItemView;
?>


<main class="wrapper">
    <h2 class="title">May we suggest something?</h2>

    <ul class="catalog">
        <?php foreach ($random as $item): ?>
            <?= ItemView::render($item); ?>
        <?php endforeach; ?>
    </ul>
</main>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
