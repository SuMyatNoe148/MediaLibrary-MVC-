<?php require_once BASE_PATH . '/src/Presentation/Views/layout/header.php'; ?>

<div class="reservation-wrapper">

    <div class="reservation-card">

        <div class="reservation-header">
            <h2>Create Reservation</h2>
            <p>Reserve this item for later pickup.</p>
        </div>

        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success_message) ?>
            </div>
        <?php endif; ?>

        <form method="post" action="index.php?page=create-reservation">

            <input type="hidden" name="media_id" value="<?= $mediaId ?>">

            <div class="form-group">
                <label for="reservation_date">
                    📅 Reservation Date
                </label>

                <input
                    type="date"
                    name="reservation_date"
                    id="reservation_date"
                    value="<?= htmlspecialchars($reservationDate ?? '') ?>"
                    min="<?= date('Y-m-d') ?>"
                    required
                >
            </div>

            <div class="form-group">
                <label for="notes">
                    📝 Notes (Optional)
                </label>

                <textarea
                    name="notes"
                    id="notes"
                    rows="5"
                    placeholder="Any special requests or notes..."
                ><?= htmlspecialchars($notes ?? '') ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Create Reservation
                </button>

                <a href="index.php?page=reservations"
                   class="btn btn-secondary">
                    Cancel
                </a>
            </div>

        </form>

    </div>

</div>

<?php require_once BASE_PATH . '/src/Presentation/Views/layout/footer.php'; ?>