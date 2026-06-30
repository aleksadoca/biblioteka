<?php
/**
 * Model za rad sa knjigama
 */

class Book {
    // Privatna promenljiva za konekciju sa bazom
    private $db;
    
    /**
     * Konstruktor - inicijalizuje konekciju sa bazom
     */
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Dohvatanje svih knjiga sa filterima
     * 
     * @param array $filtori Filteri za pretragu (search, author, genre, year)
     * @return array Niz knjiga
     */
    public function getAll($filters = []) {
        $sql = "SELECT * FROM books WHERE 1=1";
        $params = [];
        
        // Filter za pretragu po naslov ili autoru
        if (!empty($filters['search'])) {
            $sql .= " AND (title LIKE ? OR author LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        // Filter za autora
        if (!empty($filters['author'])) {
            $sql .= " AND author LIKE ?";
            $params[] = "%{$filters['author']}%";
        }
        
        if (!empty($filters['genre'])) {
            $sql .= " AND genre = ?";
            $params[] = $filters['genre'];
        }
        
        if (!empty($filters['year'])) {
            $sql .= " AND year = ?";
            $params[] = $filters['year'];
        }
        
        $sql .= " ORDER BY title ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Dohvatanje knjige po ID vrednosti
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM books WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Dodavanje nove knjige
     */
    public function create($data) {
        $sql = "INSERT INTO books (title, author, isbn, genre, year, available_copies, total_copies, description, cover_image) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['isbn'],
            $data['genre'],
            $data['year'],
            $data['available_copies'],
            $data['total_copies'],
            $data['description'],
            $data['cover_image'] ?? null
        ]);
    }
    
    /**
     * Izmena podataka o knjizi
     */
    public function update($id, $data) {
        $sql = "UPDATE books SET 
                title = ?, author = ?, isbn = ?, genre = ?, year = ?, 
                available_copies = ?, total_copies = ?, description = ?, cover_image = ?
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['author'],
            $data['isbn'],
            $data['genre'],
            $data['year'],
            $data['available_copies'],
            $data['total_copies'],
            $data['description'],
            $data['cover_image'] ?? null,
            $id
        ]);
    }
    
    /**
     * Brisanje knjige ako nema aktivna iznajmljivanja
     */
    public function delete($id) {
        // Provera aktivnih iznajmljivanja
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rentals WHERE book_id = ? AND status = 'active'");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Knjiga ima aktivna iznajmljivanja.'];
        }
        
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = ?");
        return ['success' => $stmt->execute([$id])];
    }
    
    /**
     * Dohvatanje svih žanrova koji postoje u tabeli knjiga
     */
    public function getGenres() {
        $stmt = $this->db->query("SELECT DISTINCT genre FROM books WHERE genre IS NOT NULL ORDER BY genre");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Dohvatanje svih autora koji postoje u tabeli knjiga
     */
    public function getAuthors() {
        $stmt = $this->db->query("SELECT DISTINCT author FROM books ORDER BY author");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Dohvatanje svih godina izdanja koje postoje u tabeli knjiga
     */
    public function getYears() {
        $stmt = $this->db->query("SELECT DISTINCT year FROM books WHERE year IS NOT NULL ORDER BY year DESC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    /**
     * Ažuriranje broja dostupnih kopija
     */
    public function updateCopies($id, $change) {
        $stmt = $this->db->prepare("UPDATE books SET available_copies = available_copies + ? WHERE id = ? AND available_copies + ? >= 0");
        return $stmt->execute([$change, $id, $change]);
    }
    
    /**
     * Broj svih knjiga
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM books");
        return $stmt->fetchColumn();
    }
    
    /**
     * Broj dostupnih primeraka
     */
    public function countAvailable() {
        $stmt = $this->db->query("SELECT SUM(available_copies) FROM books");
        return $stmt->fetchColumn() ?: 0;
    }
    
    /**
     * Knjige koje su najčešće iznajmljivane
     */
    public function getMostRented($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT b.*, COUNT(r.id) as rental_count 
            FROM books b 
            LEFT JOIN rentals r ON b.id = r.book_id 
            GROUP BY b.id 
            ORDER BY rental_count DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Broj iznajmljivanja po žanru
     */
    public function getRentalsByGenre() {
        $stmt = $this->db->query("
            SELECT b.genre, COUNT(r.id) as rental_count 
            FROM books b 
            LEFT JOIN rentals r ON b.id = r.book_id 
            WHERE b.genre IS NOT NULL 
            GROUP BY b.genre 
            ORDER BY rental_count DESC
        ");
        return $stmt->fetchAll();
    }
}
