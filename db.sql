-- ====================================
-- MTA Ticket System - Final Database
-- ====================================

CREATE DATABASE IF NOT EXISTS `mta_tickets` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `mta_tickets`;

-- ====================================
-- 1. جدول المستخدمين
-- ====================================
CREATE TABLE `users` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `discord_id` VARCHAR(100) DEFAULT NULL UNIQUE,
  `discord_username` VARCHAR(100) DEFAULT NULL,
  `discord_avatar` VARCHAR(255) DEFAULT NULL,
  `ingame_username` VARCHAR(100) DEFAULT NULL,
  `role` ENUM('player', 'admin') DEFAULT 'player',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- 2. جدول التذاكر
-- ====================================
CREATE TABLE `tickets` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `category` ENUM('technical', 'ban', 'recovery', 'complaint', 'bug', 'suggestion', 'other') NOT NULL,
  `priority` ENUM('low', 'medium', 'high') DEFAULT 'low',
  `status` ENUM('open', 'progress', 'resolved', 'closed') DEFAULT 'open',
  `description` TEXT NOT NULL,
  `assigned_admin_id` INT(11) UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `assigned_admin_id` (`assigned_admin_id`),
  CONSTRAINT `fk_ticket_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ticket_admin` FOREIGN KEY (`assigned_admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- 3. جدول الردود (الشات)
-- ====================================
CREATE TABLE `ticket_replies` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` INT(11) UNSIGNED NOT NULL,
  `user_id` INT(11) UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `fk_reply_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reply_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================
-- 4. جدول المرفقات
-- ====================================
CREATE TABLE `ticket_attachments` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ticket_id` INT(11) UNSIGNED NOT NULL,
  `file_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(500) NOT NULL,
  `uploaded_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  CONSTRAINT `fk_attachment_ticket` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;