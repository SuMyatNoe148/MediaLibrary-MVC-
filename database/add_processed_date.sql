-- Add processed_date column to Reservations table
USE `Database01`;

ALTER TABLE `Reservations` ADD COLUMN `processed_date` TIMESTAMP NULL AFTER `payment_status`;
