-- =====================================================
-- Biblioteka - Kompletni SQL bekap
-- Struktura + Podaci
-- =====================================================
-- 
-- UPUTSTVO ZA KORIŠĆENJE:
-- 
-- 1. Kreiranje nove baze:
--    CREATE DATABASE IF NOT EXISTS biblioteka;
--    USE biblioteka;
-- 
-- 2. Importovanje bekap-a:
--    mysql -u korisnik -p biblioteka < backup.sql
-- 
-- 3. ili putem phpMyAdmin:
--    - Otvorite phpMyAdmin
--    - Kreirajte novu bazu
--    - Idite na Import tab
--    - Izaberite ovaj fajl
--    - Kliknite Go
--
-- =====================================================

-- Isključivanje provere stranih ključeva
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET AUTOCOMMIT = 0;
START TRANSACTION;

-- =====================================================
-- STRUKTURA BAZE PODATAKA
-- =====================================================

-- Brisanje postojećih tabela (ako postoje)
DROP TABLE IF EXISTS `rentals`;
DROP TABLE IF EXISTS `books`;
DROP TABLE IF EXISTS `users`;

-- -----------------------------------------------------
-- Tabela `users` - Korisnici sistema
-- -----------------------------------------------------
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
-- Tabela `books` - Knjige u biblioteci
-- -----------------------------------------------------
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
-- Tabela `rentals` - Iznajmljivanja
-- -----------------------------------------------------
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

-- =====================================================
-- PODACI
-- =====================================================

-- -----------------------------------------------------
-- Korisnici (lozinka za sve: "password")
-- -----------------------------------------------------
INSERT INTO `users` (`username`, `password`, `email`, `full_name`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@biblioteka.rs', 'Marko Marković', 'employee'),
('zaposleni1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'zaposleni1@biblioteka.rs', 'Jovan Jovanović', 'employee'),
('zaposleni2', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'zaposleni2@biblioteka.rs', 'Ana Anić', 'employee'),
('petar', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'petar@email.com', 'Petrović Petar', 'user'),
('mika', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'mika@email.com', 'Mikić Mika', 'user'),
('zika', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'zika@email.com', 'Žikić Žika', 'user'),
('ana', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ana@email.com', 'Anić Ana', 'user'),
('jelena', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jelena@email.com', 'Jelenić Jelena', 'user');

-- -----------------------------------------------------
-- Knjige
-- -----------------------------------------------------
INSERT INTO `books` (`title`, `author`, `isbn`, `genre`, `year`, `available_copies`, `total_copies`, `description`) VALUES
('Na Drini ćuprija', 'Ivo Andrić', '978-86-521-1234-5', 'Roman', 1945, 3, 3, 'Roman o istoriji mosta na Drini u Višegradu.'),
('Prokleta avlija', 'Ivo Andrić', '978-86-521-1234-6', 'Roman', 1954, 2, 2, 'Pripovest o turskom zatvoru u Carigradu.'),
('Tvrđava', 'Meša Selimović', '978-86-521-1234-7', 'Roman', 1970, 2, 2, 'Roman o borbi za slobodu u Bosni.'),
('Derviš i smrt', 'Meša Selimović', '978-86-521-1234-8', 'Roman', 1966, 3, 3, 'Psihološki roman o dervišu.'),
('Seobe', 'Miloš Crnjanski', '978-86-521-1234-9', 'Roman', 1929, 2, 2, 'Epopeja o seobama srpskog naroda.'),
('Roman o Londonu', 'Miloš Crnjanski', '978-86-521-1235-0', 'Roman', 1971, 1, 1, 'Roman o emigrantskom životu.'),
('Igrač', 'Fjodor Dostojevski', '978-86-521-1235-1', 'Roman', 1867, 2, 2, 'Roman o kockanju i strasti.'),
('Zločin i kazna', 'Fjodor Dostojevski', '978-86-521-1235-2', 'Roman', 1866, 3, 3, 'Psihološki roman o studentu Raskoljnikovu.'),
('Ana Karenjina', 'Lav Tolstoj', '978-86-521-1235-3', 'Roman', 1877, 2, 2, 'Tragična priča o zabranjenoj ljubavi.'),
('Rat i mir', 'Lav Tolstoj', '978-86-521-1235-4', 'Roman', 1869, 1, 1, 'Epopeja o Napoleonovoj invaziji.'),
('Hobit', 'J.R.R. Tolkien', '978-86-521-1235-5', 'Fantastika', 1937, 4, 4, 'Bilbo Bagins kreće na avanturu.'),
('Gospodar prstenova', 'J.R.R. Tolkien', '978-86-521-1235-6', 'Fantastika', 1954, 3, 3, 'Frodo kreće na putovanje da uništi Prsten.'),
('Harry Potter', 'J.K. Rowling', '978-86-521-1235-7', 'Fantastika', 1997, 5, 5, 'Mladi čarobnjak kreće u Hogwarts.'),
('Let iznad kukavičjeg gnezda', 'Ken Kesey', '978-86-521-1235-8', 'Roman', 1962, 2, 2, 'Roman o pobuni pacijenata.'),
('Veliki Getsbi', 'F. Scott Fitzgerald', '978-86-521-1235-9', 'Roman', 1925, 2, 2, 'Priča o američkom snu.'),
('1984', 'George Orwell', '978-86-521-1236-0', 'Distopija', 1949, 3, 3, 'Dystopijski roman o totalitarnom društvu.'),
('Fahrenheit 451', 'Ray Bradbury', '978-86-521-1236-1', 'Distopija', 1953, 2, 2, 'Roman o društvu gde su knjige zabranjene.'),
('Korak po korak', 'Stephen King', '978-86-521-1236-2', 'Triler', 1987, 2, 2, 'Triler o putovanju kroz vreme.'),
('Misery', 'Stephen King', '978-86-521-1236-3', 'Horor', 1987, 1, 1, 'Horor roman o opsednutoj obožavateljki.'),
('Ubistvo u Orijent Ekspresu', 'Agatha Christie', '978-86-521-1236-4', 'Krimi', 1934, 3, 3, 'Hercule Poirot istražuje ubistvo u vozu.');

-- -----------------------------------------------------
-- Iznajmljivanja
-- -----------------------------------------------------
INSERT INTO `rentals` (`book_id`, `user_id`, `rental_date`, `due_date`, `return_date`, `status`, `late_fee`) VALUES
(1, 4, '2026-06-01', '2026-06-15', NULL, 'active', 0.00),
(3, 5, '2026-06-10', '2026-06-24', NULL, 'active', 0.00),
(11, 6, '2026-06-05', '2026-06-19', NULL, 'active', 0.00),
(13, 7, '2026-06-15', '2026-06-29', NULL, 'active', 0.00),
(7, 4, '2026-06-12', '2026-06-26', NULL, 'active', 0.00),
(2, 5, '2026-05-20', '2026-06-03', NULL, 'late', 0.00),
(8, 6, '2026-05-25', '2026-06-08', NULL, 'late', 0.00),
(4, 4, '2026-05-01', '2026-05-15', '2026-05-14', 'returned', 0.00),
(9, 5, '2026-05-10', '2026-05-24', '2026-05-20', 'returned', 0.00),
(12, 7, '2026-04-15', '2026-04-29', '2026-05-05', 'returned', 600.00),
(15, 4, '2026-04-01', '2026-04-15', '2026-04-12', 'returned', 0.00),
(16, 6, '2026-03-20', '2026-04-03', '2026-04-10', 'returned', 700.00),
(20, 8, '2026-05-01', '2026-05-15', '2026-05-15', 'returned', 0.00),
(14, 5, '2026-06-01', '2026-06-15', '2026-06-10', 'returned', 0.00),
(6, 7, '2026-05-15', '2026-05-29', '2026-05-28', 'returned', 0.00);

-- Ažuriranje dostupnih kopija
UPDATE books b SET available_copies = total_copies - (
    SELECT COUNT(*) FROM rentals r 
    WHERE r.book_id = b.id AND r.status IN ('active', 'late')
);

-- Uključivanje provere stranih ključeva
SET FOREIGN_KEY_CHECKS = 1;
COMMIT;

-- =====================================================
-- KRAJ BEKAP-A
-- =====================================================
