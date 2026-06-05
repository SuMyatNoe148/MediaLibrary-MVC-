-- Users Table
DROP TABLE IF EXISTS `Users`;
CREATE TABLE `Users` (
  `user_id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `is_verified` TINYINT(1) DEFAULT 0,
  `is_admin` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
);

-- Insert default admin user (password: Amara@123)
INSERT INTO `Users`(`username`, `email`, `password`, `is_admin`, `is_verified`) VALUES 
('admin', 'amaramay268@gmail.com', '$2y$10$s4VlJ0fMUScEevFwwqDoquX/nbNkzfVKLjtwRsfWczOoi3Dcx1NvK', 1, 1);
