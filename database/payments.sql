-- =========================================
-- PAYMENTS TABLE
-- =========================================

USE `Database01`;

DROP TABLE IF EXISTS `Payments`;
CREATE TABLE `Payments` (
  `payment_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `reservation_id` INT NULL,
  `stripe_session_id` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `currency` VARCHAR(3) DEFAULT 'USD',
  `payment_status` ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  `payment_type` ENUM('reservation', 'membership', 'fine') DEFAULT 'reservation',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`reservation_id`) REFERENCES `Reservations`(`reservation_id`) ON DELETE SET NULL,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_reservation_id` (`reservation_id`),
  INDEX `idx_stripe_session_id` (`stripe_session_id`),
  INDEX `idx_payment_status` (`payment_status`)
);
