-- Update database untuk workspace system dengan folder dan recent tracking

-- Tabel untuk folders
CREATE TABLE IF NOT EXISTS `workspace_folders` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `folder_name` VARCHAR(255) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_folder_user` (`user_id`),
  CONSTRAINT `fk_folder_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel untuk user templates (hasil subscribe)
CREATE TABLE IF NOT EXISTS `user_templates` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `folder_id` INT DEFAULT NULL,
  `added_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `fk_template_user` (`user_id`),
  KEY `fk_template_product` (`product_id`),
  KEY `fk_template_folder` (`folder_id`),
  CONSTRAINT `fk_template_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_template_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_template_folder` FOREIGN KEY (`folder_id`) REFERENCES `workspace_folders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel untuk recent activity
CREATE TABLE IF NOT EXISTS `template_recent` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `accessed_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_product` (`user_id`, `product_id`),
  KEY `fk_recent_user` (`user_id`),
  KEY `fk_recent_product` (`product_id`),
  CONSTRAINT `fk_recent_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_recent_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert sample folders untuk testing (optional)
-- INSERT INTO `workspace_folders` (`user_id`, `folder_name`) VALUES
-- (2, 'My Designs'),
-- (2, 'Client Projects'),
-- (3, 'Personal');
