-- =========================================
-- ADDITIONAL FEATURES TABLES
-- =========================================

USE `Database01`;

-- Password Reset Tokens
DROP TABLE IF EXISTS `Password_Resets`;
CREATE TABLE `Password_Resets` (
  `reset_id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reset_id`),
  INDEX `idx_email` (`email`),
  INDEX `idx_token` (`token`)
);

-- Remember Me Tokens
DROP TABLE IF EXISTS `Remember_Tokens`;
CREATE TABLE `Remember_Tokens` (
  `token_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`token_id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`) ON DELETE CASCADE,
  INDEX `idx_token` (`token`)
);


-- User Activity Log (for tracking)
DROP TABLE IF EXISTS `User_Activity`;
CREATE TABLE `User_Activity` (
  `activity_id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `activity_type` VARCHAR(50) NOT NULL,
  `media_id` INT NULL,
  `details` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`activity_id`),
  FOREIGN KEY (`user_id`) REFERENCES `Users`(`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`media_id`) REFERENCES `Media`(`media_id`) ON DELETE SET NULL
);


