<?php 
require BASE_PATH . '/src/Presentation/Views/layout/header.php';
use MediaLibrary\Presentation\Views\ItemView;
?>

<div class="section catalog page">
	<div class="wrapper">

		<div class="catalog-header">
			<?php if (!empty($search)): ?>
				<h1>
					<?= IconHelper::search('section-icon') ?>
					Search Results
				</h1>
				<p class="catalog-subtitle">
					Found <?= $total_items ?> result<?= $total_items !== 1 ? 's' : '' ?> for "<strong><?= htmlspecialchars($search) ?></strong>"
					<?php if (!empty($section)): ?> in <?= ucfirst($section) ?><?php endif; ?>
				</p>
			<?php else: ?>
				<?php if (!empty($section)): ?>
					<nav class="breadcrumb">
						<a href="index.php?page=catalog"><?= IconHelper::library('breadcrumb-icon') ?> All Media</a>
						<span class="breadcrumb-separator">/</span>
						<span class="breadcrumb-current">
							<?php if ($section === 'books'): ?>
								<?= IconHelper::book('breadcrumb-icon') ?> Books
							<?php elseif ($section === 'movies'): ?>
								<?= IconHelper::film('breadcrumb-icon') ?> Movies
							<?php elseif ($section === 'music'): ?>
								<?= IconHelper::music('breadcrumb-icon') ?> Music
							<?php else: ?>
								<?= htmlspecialchars($pageTitle) ?>
							<?php endif; ?>
						</span>
					</nav>
				<?php else: ?>
					<h1><?= IconHelper::library('section-icon') ?> All Media</h1>
					<p class="catalog-subtitle">Browse our complete collection of books, movies, and music</p>
				<?php endif; ?>
			<?php endif; ?>
		</div>

		<?php if ($total_items < 1): ?>

			<?php if (!empty($section) && $found_in_full_catalog > 0): ?>

				<p>You are searching in the wrong section. Please check again.</p>

				<p>
					<a href="index.php?page=catalog&s=<?= urlencode($search) ?>">
						Search in the Full Catalog
					</a>
				</p>

			<?php else: ?>

				<p>No items were found matching that search term.</p>

				<p>
					Search again or
					<a href="index.php?page=catalog">Browse the Full Catalog.</a>
				</p>

			<?php endif; ?>

		<?php else: ?>

			<ul class="catalog">
				<?php foreach ($catalog as $item): ?>
					<?= ItemView::render($item); ?>
				<?php endforeach; ?>
			</ul>

			<?php require BASE_PATH . '/src/Presentation/Views/partials/pagination.php'; ?>

		<?php endif; ?>

	</div>
</div>

<?php require BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
