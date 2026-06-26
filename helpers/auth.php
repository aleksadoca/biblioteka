<?php
/**
 * Pomoćne funkcije za autentifikaciju
 * Upravljanje sesijama, prijava, odjava i registracija korisnika
 */

// Pokretanje sesije
session_start();

/**
 * Proverava da li je korisnik prijavljen
 * 
 * @return bool True ako je korisnik prijavljen
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Dohvata podatke o trenutnom korisniku
 * 
 * @return array|null Podaci o korisniku ili null ako nije prijavljen
 */
function getCurrentUser() {
    if (!isLoggedIn()) return null;
    
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, email, full_name, role, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

/**
 * Proverava da li je trenutni korisnik zaposleni
 * 
 * @return bool True ako je korisnik zaposleni
 */
function isEmployee() {
    if (!isLoggedIn()) return false;
    return $_SESSION['user_role'] === 'employee';
}

/**
 * Zahteva prijavu - preusmerava na stranicu za prijavu ako korisnik nije autentifikovan
 */
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /index.php?page=login');
        exit;
    }
}

/**
 * Zahteva ulogu zaposlenog - preusmerava na početnu stranicu ako nema ovlašćenja
 */
function requireEmployee() {
    requireLogin();
    if (!isEmployee()) {
        $_SESSION['error'] = 'Nemate pristup ovoj stranici.';
        header('Location: /index.php?page=home');
        exit;
    }
}

/**
 * Prijava korisnika u sistem
 * 
 * @param string $username Korisničko ime
 * @param string $password Lozinka
 * @return bool True ako je prijava uspešna
 */
function loginUser($username, $password) {
    $db = getDB();
    $stmt = $db->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        return true;
    }
    
    return false;
}

/**
 * Odjava korisnika iz sistema
 * Uništava sesiju i preusmerava na stranicu za prijavu
 */
function logoutUser() {
    session_destroy();
    header('Location: /index.php?page=login');
    exit;
}

/**
 * Registracija novog korisnika
 * 
 * @param string $username Korisničko ime
 * @param string $password Lozinka
 * @param string $email Email adresa
 * @param string $fullName Puno ime korisnika
 * @return array Rezultat registracije sa statusom i porukom
 */
function registerUser($username, $password, $email, $fullName) {
    $db = getDB();
    
    // Provera da li korisničko ime već postoji
    $stmt = $db->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Korisničko ime već postoji.'];
    }
    
    // Provera da li email adresa već postoji
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        return ['success' => false, 'message' => 'Email adresa je već registrovana.'];
    }
    
    // Ubacivanje novog korisnika u bazu
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (username, password, email, full_name, role) VALUES (?, ?, ?, ?, 'user')");
    
    if ($stmt->execute([$username, $hashedPassword, $email, $fullName])) {
        return ['success' => true, 'message' => 'Registracija uspešna! Možete se prijaviti.'];
    }
    
    return ['success' => false, 'message' => 'Greška prilikom registracije.'];
}

/**
 * Generisanje CSRF tokena za zaštitu formi
 * 
 * @return string CSRF token
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verifikacija CSRF tokena
 * 
 * @param string $token Token za verifikaciju
 * @return bool True ako je token validan
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
