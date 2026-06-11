<?php require_once BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>
<link rel="stylesheet" href="Public/assets/css/admin-messages.css">

<div class="admin-messages-dashboard">

    <div class="admin-messages-header">
        <h1>Message Detail</h1>
    </div>

    <div class="msg-detail-card">

        <!-- Header bar with subject + status badge -->
        <div class="msg-detail-header-bar">
            <div class="msg-detail-subject-wrap">
                <?= IconHelper::mail('msg-detail-icon') ?>
                <h2 class="msg-detail-subject"><?= htmlspecialchars($message['subject'] ?? 'No Subject') ?></h2>
            </div>
            <?php if (($message['is_read'] ?? 0) == 0): ?>
                <span class="msg-badge msg-badge-unread">Unread</span>
            <?php else: ?>
                <span class="msg-badge msg-badge-read">Read</span>
            <?php endif; ?>
        </div>

        <!-- Meta grid -->
        <div class="msg-detail-meta">
            <div class="msg-detail-meta-item">
                <span class="msg-detail-label">From</span>
                <span class="msg-detail-value">
                    <?= IconHelper::user('msg-meta-icon') ?>
                    <?= htmlspecialchars($message['name'] ?? $message['username'] ?? 'Unknown') ?>
                    <?php if (!empty($message['email'])): ?>
                        <span class="msg-time">&lt;<?= htmlspecialchars($message['email']) ?>&gt;</span>
                    <?php endif; ?>
                </span>
            </div>
            <div class="msg-detail-meta-item">
                <span class="msg-detail-label">Received</span>
                <span class="msg-detail-value">
                    <?= IconHelper::calendar('msg-meta-icon') ?>
                    <?= date('F j, Y', strtotime($message['created_at'])) ?>
                    <span class="msg-time"><?= date('g:i A', strtotime($message['created_at'])) ?></span>
                </span>
            </div>
        </div>

        <!-- Message body -->
        <div class="msg-detail-body">
            <p class="msg-detail-body-label">Message Content</p>
            <?php
                $raw = $message['message'] ?? '';
                // Try to parse key: value lines into a structured list
                $lines = explode("\n", $raw);
                $fields = [];
                $details = [];
                $inDetails = false;
                foreach ($lines as $line) {
                    $line = trim($line);
                    if ($line === '') continue;
                    if (preg_match('/^Details:\s*(.*)/i', $line, $m)) {
                        $inDetails = true;
                        if ($m[1] !== '') $details[] = $m[1];
                        continue;
                    }
                    if ($inDetails) { $details[] = $line; continue; }
                    if (preg_match('/^([^:]+):\s*(.*)$/', $line, $m)) {
                        $fields[$m[1]] = $m[2];
                    } else {
                        $fields[] = $line;
                    }
                }
                $isParsed = count($fields) > 0;
            ?>
            <?php if ($isParsed): ?>
                <div class="msg-detail-fields">
                    <?php foreach ($fields as $key => $val): ?>
                        <?php if (is_string($key)): ?>
                        <div class="msg-field-row">
                            <span class="msg-field-key"><?= htmlspecialchars($key) ?></span>
                            <span class="msg-field-val"><?= htmlspecialchars($val) ?></span>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if (!empty($details)): ?>
                    <div class="msg-field-row msg-field-details">
                        <span class="msg-field-key">Details</span>
                        <span class="msg-field-val"><?= nl2br(htmlspecialchars(implode("\n", $details))) ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <div class="msg-detail-content">
                    <?= nl2br(htmlspecialchars($raw)) ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="msg-detail-actions">
            <a href="index.php?page=admin-messages" class="btn-secondary">&larr; Back to Messages</a>
        </div>

    </div>

</div>

<?php require_once BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>
