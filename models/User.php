<?php
/**
 * Model za rad sa korisnicima
 */

class User {
    // Privatna promenljiva za konekciju sa bazom
    private $db;
    
    /**
     * Konstruktor - inicijalizuje konekciju sa bazom
     */
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Dohvatanje svih korisnika
     * 
     * @param string|null $pretraga Tekst za pretragu (opciono)
     * @return array Niz korisnika
     */
    public function getAll($search = null) {
        $sql = "SELECT id, username, email, full_name, role, created_at FROM users";
        $params = [];
        
        if ($search) {
            $sql .= " WHERE username LIKE ? OR full_name LIKE ? OR email LIKE ?";
            $searchTerm = "%$search%";
            $params = [$searchTerm, $searchTerm, $searchTerm];
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Dohvatanje korisnika po ID vrednosti
     */
    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, username, email, full_name, role, created_at FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    /**
     * Izmena korisničkog profila
     */
    public function update($id, $data) {
        $sql = "UPDATE users SET email = ?, full_name = ?";
        $params = [$data['email'], $data['full_name']];
        
        // Izmena lozinke samo ako je nova lozinka uneta
        if (!empty($data['password'])) {
            $sql .= ", password = ?";
            $params[] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Izmena uloge ako je prosleđena iz forme za zaposlene
        if (isset($data['role'])) {
            $sql .= ", role = ?";
            $params[] = $data['role'];
        }
        
        $sql .= " WHERE id = ?";
        $params[] = $id;
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }
    
    /**
     * Brisanje korisnika ako nema aktivna iznajmljivanja
     */
    public function delete($id) {
        // Provera aktivnih iznajmljivanja
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rentals WHERE user_id = ? AND status = 'active'");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() > 0) {
            return ['success' => false, 'message' => 'Korisnik ima aktivna iznajmljivanja.'];
        }
        
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return ['success' => $stmt->execute([$id])];
    }
    
    /**
     * Istorija iznajmljivanja jednog korisnika
     */
    public function getRentalHistory($userId) {
        $stmt = $this->db->prepare("
            SELECT r.*, b.title as book_title, b.author as book_author 
            FROM rentals r 
            JOIN books b ON r.book_id = b.id 
            WHERE r.user_id = ? 
            ORDER BY r.rental_date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Ukupan broj korisnika
     */
    public function count() {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return $stmt->fetchColumn();
    }
    
    /**
     * Korisnici sa najviše iznajmljivanja
     */
    public function getMostActive($limit = 10) {
        $stmt = $this->db->prepare("
            SELECT u.id, u.username, u.full_name, COUNT(r.id) as rental_count 
            FROM users u 
            LEFT JOIN rentals r ON u.id = r.user_id 
            GROUP BY u.id 
            ORDER BY rental_count DESC 
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}
