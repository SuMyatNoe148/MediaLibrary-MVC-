<?php if ($total_pages > 1): ?>
<?php
    // Helper function to build URL
    $buildUrl = function($pageNum) use ($section, $search) {
        $query = ['page' => 'catalog', 'pg' => $pageNum];
        if (!empty($section)) $query['cat'] = $section;
        if (!empty($search))  $query['s']   = $search;
        return 'index.php?' . http_build_query($query);
    };

    // Determine page range to show
    $range = 2; // Show 2 pages on each side of current
    $start = max(1, $current_page - $range);
    $end = min($total_pages, $current_page + $range);
?>
<div class="pagination-container">
    <div class="pagination-info">
        Page <?= $current_page ?> of <?= $total_pages ?> (<?= $total_items ?? '' ?> items)
    </div>

    <div class="pagination">
        <!-- Previous Button -->
        <?php if ($current_page > 1): ?>
            <a href="<?= $buildUrl($current_page - 1) ?>" class="prev-next" title="Previous Page">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Prev
            </a>
        <?php else: ?>
            <span class="prev-next disabled">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Prev
            </span>
        <?php endif; ?>

        <!-- First Page -->
        <?php if ($start > 1): ?>
            <a href="<?= $buildUrl(1) ?>" class="first-last">1</a>
            <?php if ($start > 2): ?>
                <span class="ellipsis">...</span>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Page Numbers -->
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i == $current_page): ?>
                <span><?= $i ?></span>
            <?php else: ?>
                <a href="<?= $buildUrl($i) ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <!-- Last Page -->
        <?php if ($end < $total_pages): ?>
            <?php if ($end < $total_pages - 1): ?>
                <span class="ellipsis">...</span>
            <?php endif; ?>
            <a href="<?= $buildUrl($total_pages) ?>" class="first-last"><?= $total_pages ?></a>
        <?php endif; ?>

        <!-- Next Button -->
        <?php if ($current_page < $total_pages): ?>
            <a href="<?= $buildUrl($current_page + 1) ?>" class="prev-next" title="Next Page">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </a>
        <?php else: ?>
            <span class="prev-next disabled">
                Next
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
