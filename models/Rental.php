<?php
/**
 * Model za rad sa iznajmljivanjima
 */

class Rental {
    // Privatna promenljiva za konekciju sa bazom
    private $db;
    
    /**
     * Konstruktor - inicijalizuje konekciju sa bazom
     */
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Dohvatanje svih iznajmljivanja sa filterima
     * 
     * @param array $filtori Filteri za pretragu (status, user_id, book_id)
     * @return array Niz iznajmljivanja
     */
    public function getAll($filters = []) {
        $sql = "SELECT r.*, b.title as book_title, b.author as book_author, u.username, u.full_name 
                FROM rentals r 
                JOIN books b ON r.book_id = b.id 
                JOIN users u ON r.user_id = u.id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND r.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['user_id'])) {
            $sql .= " AND r.user_id = ?";
            $params[] = $filters['user_id'];
        }
        
        if (!empty($filters['book_id'])) {
            $sql .= " AND r.book_id = ?";
            $params[] = $filters['book_id'];
        }
        
        $sql .= " ORDER BY r.rental_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Dohvatanje iznajmljivanja po ID vrednosti
     */
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT r.*, b.title as book_title, b.author as book_author, u.username, u.full_name 
            FROM rentals r 
            JOIN books b ON r.book_id = b.id 
            JOIN users u ON r.user_id = u.id 
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Kreiranje novog iznajmljivanja
     */
    public function create($data) {
        $this->db->beginTransaction();
        
        try {
            // Provera dostupnih primeraka
            $stmt = $this->db->prepare("SELECT available_copies FROM books WHERE id = ?");
            $stmt->execute([$data['book_id']]);
            $book = $stmt->fetch();
            
            if (!$book || $book['available_copies'] <= 0) {
                throw new Exception('Knjiga nije dostupna za iznajmljivanje.');
            }
            
            // Upis novog iznajmljivanja
            $stmt = $this->db->prepare("
                INSERT INTO rentals (book_id, user_id, rental_date, due_date, status) 
                VALUES (?, ?, CURDATE(), ?, 'active')
            ");
            $stmt->execute([
                $data['book_id'],
                $data['user_id'],
                $data['due_date']
            ]);
            
            // Smanjenje broja dostupnih primeraka
            $stmt = $this->db->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id = ?");
            $stmt->execute([$data['book_id']]);
            
            $this->db->commit();
            return ['success' => true, 'message' => 'Knjiga uspešno iznajmljena.'];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Vraćanje knjige i računanje zakasnine
     */
    public function returnBook($id) {
        $this->db->beginTransaction();
        
        try {
            // Dohvatanje podataka o iznajmljivanju
            $stmt = $this->db->prepare("SELECT * FROM rentals WHERE id = ? AND status IN ('active', 'late')");
            $stmt->execute([$id]);
            $rental = $stmt->fetch();
            
            if (!$rental) {
                throw new Exception('Iznajmljivanje nije pronađeno ili je već vraćeno.');
            }
            
            // Računanje zakasnine
            $lateFee = calculateLateFee($rental['due_date']);
            
            // Ažuriranje iznajmljivanja
            $stmt = $this->db->prepare("
                UPDATE rentals SET return_date = CURDATE(), status = 'returned', late_fee = ? 
                WHERE id = ?
            ");
            $stmt->execute([$lateFee, $id]);
            
            // Povećanje broja dostupnih primeraka
            $stmt = $this->db->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?");
            $stmt->execute([$rental['book_id']]);
            
            $this->db->commit();
            
            $message = 'Knjiga uspešno vraćena.';
            if ($lateFee > 0) {
                $message .= " Zakasnina: " . formatCurrency($lateFee);
            }
            
            return ['success' => true, 'message' => $message, 'late_fee' => $lateFee];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Ažuriranje statusa zakasnelih iznajmljivanja
     */
    public function updateLateStatus() {
        $stmt = $this->db->prepare("
            UPDATE rentals SET status = 'late' 
            WHERE status = 'active' AND due_date < CURDATE()
        ");
        return $stmt->execute();
    }
    
    /**
     * Broj aktivnih iznajmljivanja
     */
    public function countActive() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM rentals WHERE status IN ('active', 'late')");
        return $stmt->fetchColumn();
    }
    
    /**
     * Ukupan broj iznajmljivanja
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM rentals");
        return $stmt->fetchColumn();
    }
    
    /**
     * Broj zakasnelih iznajmljivanja
     */
    public function countLate() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM rentals WHERE status = 'late'");
        return $stmt->fetchColumn();
    }
    
    /**
     * Aktivna iznajmljivanja jednog korisnika
     */
    public function getUserActiveRentals($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*, b.title as book_title, b.author as book_author 
            FROM rentals r 
            JOIN books b ON r.book_id = b.id 
            WHERE r.user_id = ? AND r.status IN ('active', 'late')
            ORDER BY r.due_date ASC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Provera da li korisnik može da iznajmi još knjiga (najviše 5 aktivnih)
     */
    public function canUserRent($userId) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ? AND status IN ('active', 'late')");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn() < 5;
    }
}
