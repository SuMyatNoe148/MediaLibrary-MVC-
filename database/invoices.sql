-- =========================================
-- INVOICES TABLE
-- =========================================

USE `Database01`;

DROP TABLE IF EXISTS `Invoices`;
CREATE TABLE `Invoices` (
  `invoice_id` INT NOT NULL AUTO_INCREMENT,
  `invoice_number` VARCHAR(50) UNIQUE NOT NULL,
  `reservation_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `payment_intent_id` VARCHAR(255),
  `amount` DECIMAL(10, 2) NOT NULL,
  `currency` VARCHAR(10) DEFAULT 'USD',
  `status` ENUM('PAID', 'REFUNDED') DEFAULT 'PAID',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`invoice_id`),
  FOREIGN KEY (`reservation_id`) REFERENCES `Reservations`(`reservation_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`) ON DELETE CASCADE,
  INDEX `idx_invoice_number` (`invoice_number`),
  INDEX `idx_user_id` (`user_id`),
  INDEX `idx_reservation_id` (`reservation_id`),
  INDEX `idx_status` (`status`)
);
