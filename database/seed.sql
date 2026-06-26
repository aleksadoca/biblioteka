-- =====================================================
-- Biblioteka - Test podaci
-- Podaci za razvoj i demonstracija aplikacije
-- =====================================================

-- -----------------------------------------------------
-- Korisnici (password: "password" za sve)
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
('Na Drini ćuprija', 'Ivo Andrić', '978-86-521-1234-5', 'Roman', 1945, 3, 3, 'Roman o istoriji mosta na Drini u Višegradu, koji simbolizuje povezivanje naroda i kultura.'),
('Prokleta avlija', 'Ivo Andrić', '978-86-521-1234-6', 'Roman', 1954, 2, 2, 'Pripovest o turskom zatvoru u Carigradu i sudbinama zatvorenika.'),
('Tvrđava', 'Meša Selimović', '978-86-521-1234-7', 'Roman', 1970, 2, 2, 'Roman o borbi za slobodu u Bosni za vreme turske vladavine.'),
('Derviš i smrt', 'Meša Selimović', '978-86-521-1234-8', 'Roman', 1966, 3, 3, 'Psihološki roman o dervišu koji traži pravdu za svog brata.'),
('Seobe', 'Miloš Crnjanski', '978-86-521-1234-9', 'Roman', 1929, 2, 2, 'Epopeja o seobama srpskog naroda u 18. veku.'),
('Roman o Londonu', 'Miloš Crnjanski', '978-86-521-1235-0', 'Roman', 1971, 1, 1, 'Roman o emigrantskom životu u Londonu.'),
('Igrač', 'Fjodor Dostojevski', '978-86-521-1235-1', 'Roman', 1867, 2, 2, 'Roman o kockanju i strasti koji je Dostojevski napisao za 26 dana.'),
('Zločin i kazna', 'Fjodor Dostojevski', '978-86-521-1235-2', 'Roman', 1866, 3, 3, 'Psihološki roman o studentu Raskoljnikovu i ubistvu.'),
('Ana Karenjina', 'Lav Tolstoj', '978-86-521-1235-3', 'Roman', 1877, 2, 2, 'Tragična priča o zabranjenoj ljubavi u ruskom visokom društvu.'),
('Rat i mir', 'Lav Tolstoj', '978-86-521-1235-4', 'Roman', 1869, 1, 1, 'Epopeja o Napoleonovoj invaziji na Rusiju i sudbinama ruske aristokratije.'),
('Hobit', 'J.R.R. Tolkien', '978-86-521-1235-5', 'Fantastika', 1937, 4, 4, 'Bilbo Bagins kreće na avanturu sa patuljcima da povrate njihovo kraljevstvo.'),
('Gospodar prstenova: Prstenova družina', 'J.R.R. Tolkien', '978-86-521-1235-6', 'Fantastika', 1954, 3, 3, 'Frodo nasleđuje Prsten moći i kreće na putovanje da ga uništi.'),
('Harry Potter i kamen mudrosti', 'J.K. Rowling', '978-86-521-1235-7', 'Fantastika', 1997, 5, 5, 'Mladi čarobnjak Harry Potter saznaje da je čarobnjak i kreće u Hogwarts.'),
('Let iznad kukavičjeg gnezda', 'Ken Kesey', '978-86-521-1235-8', 'Roman', 1962, 2, 2, 'Roman o pobuni pacijenata u psihijatrijskoj ustanovi.'),
('Veliki Getsbi', 'F. Scott Fitzgerald', '978-86-521-1235-9', 'Roman', 1925, 2, 2, 'Priča o američkom snu i tragičnoj ljubavi u doba jazza.'),
('1984', 'George Orwell', '978-86-521-1236-0', 'Distopija', 1949, 3, 3, 'Dystopijski roman o totalitarnom društvu i kontroli uma.'),
('Fahrenheit 451', 'Ray Bradbury', '978-86-521-1236-1', 'Distopija', 1953, 2, 2, 'Roman o društvu u kojem su knjige zabranjene i vatrogasci ih pale.'),
('Korak po korak', 'Stephen King', '978-86-521-1236-2', 'Triler', 1987, 2, 2, 'Triler o čoveku koji putuje kroz vreme da spreči atentat.'),
('Misery', 'Stephen King', '978-86-521-1236-3', 'Horor', 1987, 1, 1, 'Horor roman o piscu koga otima njegova opsednuta obožavateljka.'),
('Ubistvo u Orijent Ekspresu', 'Agatha Christie', '978-86-521-1236-4', 'Krimi', 1934, 3, 3, 'Hercule Poirot istražuje ubistvo u luksuznom vozu.');

-- -----------------------------------------------------
-- Iznajmljivanja
-- -----------------------------------------------------
INSERT INTO `rentals` (`book_id`, `user_id`, `rental_date`, `due_date`, `return_date`, `status`, `late_fee`) VALUES
-- Aktivna iznajmljivanja
(1, 4, '2026-06-01', '2026-06-15', NULL, 'active', 0.00),
(3, 5, '2026-06-10', '2026-06-24', NULL, 'active', 0.00),
(11, 6, '2026-06-05', '2026-06-19', NULL, 'active', 0.00),
(13, 7, '2026-06-15', '2026-06-29', NULL, 'active', 0.00),
(7, 4, '2026-06-12', '2026-06-26', NULL, 'active', 0.00),
-- Kasna iznajmljivanja (trebala su biti vraćena)
(2, 5, '2026-05-20', '2026-06-03', NULL, 'late', 0.00),
(8, 6, '2026-05-25', '2026-06-08', NULL, 'late', 0.00),
-- Vraćena iznajmljivanja
(4, 4, '2026-05-01', '2026-05-15', '2026-05-14', 'returned', 0.00),
(9, 5, '2026-05-10', '2026-05-24', '2026-05-20', 'returned', 0.00),
(12, 7, '2026-04-15', '2026-04-29', '2026-05-05', 'returned', 600.00),
(15, 4, '2026-04-01', '2026-04-15', '2026-04-12', 'returned', 0.00),
(16, 6, '2026-03-20', '2026-04-03', '2026-04-10', 'returned', 700.00),
(20, 8, '2026-05-01', '2026-05-15', '2026-05-15', 'returned', 0.00),
(14, 5, '2026-06-01', '2026-06-15', '2026-06-10', 'returned', 0.00),
(6, 7, '2026-05-15', '2026-05-29', '2026-05-28', 'returned', 0.00);

-- -----------------------------------------------------
-- Ažuriranje dostupnih kopija na osnovu aktivnih iznajmljivanja
-- -----------------------------------------------------
UPDATE books b SET available_copies = total_copies - (
    SELECT COUNT(*) FROM rentals r 
    WHERE r.book_id = b.id AND r.status IN ('active', 'late')
);
