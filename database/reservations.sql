-- =========================================
-- RESERVATIONS TABLE
-- =========================================

USE `Database01`;

DROP TABLE IF EXISTS `Reservations`;
CREATE TABLE `Reservations` (
  `reservation_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `media_id` INT NOT NULL,
  `reservation_date` DATE NOT NULL,
  `status` ENUM('pending', 'confirmed', 'cancelled', 'completed') DEFAULT 'pending',
  `notes` TEXT,
  `amount` DECIMAL(10, 2) DEFAULT 0.00,
  `payment_status` ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
  `processed_date` TIMESTAMP NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reservation_id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`media_id`) REFERENCES `Media`(`media_id`) ON DELETE CASCADE,
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_media_id` (`media_id`),
  INDEX `idx_status` (`status`),
  INDEX `idx_reservation_date` (`reservation_date`)
);
