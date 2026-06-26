-- =====================================================
-- Biblioteka - Struktura baze podataka
-- MySQL 5.7+ / MariaDB 10.2+
-- =====================================================

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `full_name` VARCHAR(100) NOT NULL,
    `role` ENUM('user', 'employee') NOT NULL DEFAULT 'user',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_username` (`username`),
    UNIQUE KEY `uk_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `books`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `books`;
CREATE TABLE `books` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(200) NOT NULL,
    `author` VARCHAR(100) NOT NULL,
    `isbn` VARCHAR(20) DEFAULT NULL,
    `genre` VARCHAR(50) DEFAULT NULL,
    `year` INT DEFAULT NULL,
    `available_copies` INT NOT NULL DEFAULT 0,
    `total_copies` INT NOT NULL DEFAULT 1,
    `description` TEXT DEFAULT NULL,
    `cover_image` VARCHAR(255) DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_isbn` (`isbn`),
    KEY `idx_author` (`author`),
    KEY `idx_genre` (`genre`),
    KEY `idx_year` (`year`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------
-- Table `rentals`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `rentals`;
CREATE TABLE `rentals` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `book_id` INT UNSIGNED NOT NULL,
    `user_id` INT UNSIGNED NOT NULL,
    `rental_date` DATE NOT NULL,
    `due_date` DATE NOT NULL,
    `return_date` DATE DEFAULT NULL,
    `status` ENUM('active', 'returned', 'late') NOT NULL DEFAULT 'active',
    `late_fee` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_book_id` (`book_id`),
    KEY `idx_user_id` (`user_id`),
    KEY `idx_status` (`status`),
    KEY `idx_rental_date` (`rental_date`),
    KEY `idx_due_date` (`due_date`),
    CONSTRAINT `fk_rentals_book` FOREIGN KEY (`book_id`) REFERENCES `books` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT `fk_rentals_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- View for active rentals with calculated late fee
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `v_active_rentals` AS
SELECT 
    r.*,
    b.title as book_title,
    b.author as book_author,
    u.username,
    u.full_name,
    CASE 
        WHEN r.due_date < CURDATE() AND r.return_date IS NULL 
        THEN DATEDIFF(CURDATE(), r.due_date) * 100
        ELSE 0 
    END as calculated_late_fee,
    CASE 
        WHEN r.due_date < CURDATE() AND r.return_date IS NULL 
        THEN DATEDIFF(CURDATE(), r.due_date)
        ELSE 0 
    END as days_late
FROM rentals r
JOIN books b ON r.book_id = b.id
JOIN users u ON r.user_id = u.id
WHERE r.status IN ('active', 'late');

-- -----------------------------------------------------
-- View for rental statistics
-- -----------------------------------------------------
CREATE OR REPLACE VIEW `v_rental_stats` AS
SELECT 
    b.genre,
    COUNT(r.id) as total_rentals,
    COUNT(CASE WHEN r.status = 'returned' THEN 1 END) as returned_count,
    COUNT(CASE WHEN r.status = 'late' THEN 1 END) as late_count,
    SUM(r.late_fee) as total_late_fees
FROM books b
LEFT JOIN rentals r ON b.id = r.book_id
WHERE b.genre IS NOT NULL
GROUP BY b.genre;
