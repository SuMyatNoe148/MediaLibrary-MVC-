-- =========================================
-- REVIEWS TABLE
-- =========================================

USE `Database01`;

CREATE TABLE IF NOT EXISTS `Reviews` (
  `review_id` INT NOT NULL AUTO_INCREMENT,
  `media_id`  INT NOT NULL,
  `user_id`   INT NOT NULL,
  `rating`    TINYINT NOT NULL DEFAULT 0 COMMENT '1-5 stars',
  `comment`   TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`review_id`),
  UNIQUE KEY `uq_user_media` (`user_id`, `media_id`),
  FOREIGN KEY (`media_id`) REFERENCES `Media`(`media_id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`)  REFERENCES `Users`(`user_id`)  ON DELETE CASCADE,
  INDEX `idx_media_id` (`media_id`),
  INDEX `idx_user_id`  (`user_id`)
);
