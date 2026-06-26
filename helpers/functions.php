<?php
/**
 * Opšte pomoćne funkcije
 * Sadrži funkcije za formatiranje, validaciju i rad sa sesijama
 */

/**
 * Sanitizacija izlaza za HTML
 * Sprečava XSS napade
 * 
 * @param string $string Tekst za sanitizaciju
 * @return string Sanitizovani tekst
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Preusmeravanje na URL
 * 
 * @param string $url URL za preusmeravanje
 */
function redirect($url) {
    header("Location: $url");
    exit;
}

/**
 * Postavljanje flash poruke
 * 
 * @param string $type Tip poruke (success, error, warning)
 * @param string $message Tekst poruke
 */
function setFlash($type, $message) {
    $_SESSION[$type] = $message;
}

/**
 * Dohvatanje i brisanje flash poruke
 * 
 * @param string $type Tip poruke
 * @return string|null Tekst poruke ili null
 */
function getFlash($type) {
    if (isset($_SESSION[$type])) {
        $message = $_SESSION[$type];
        unset($_SESSION[$type]);
        return $message;
    }
    return null;
}

/**
 * Formatiranje datum za prikaz
 * 
 * @param string $date Datum u ISO formatu
 * @return string Formatirani datum (dd.mm.yyyy)
 */
function formatDate($date) {
    if (!$date) return '-';
    return date('d.m.Y', strtotime($date));
}

/**
 * Formatiranje valute
 * 
 * @param float $amount Iznos za formatiranje
 * @return string Formatirani iznos sa valutom
 */
function formatCurrency($amount) {
    return number_format($amount, 2, ',', '.') . ' RSD';
}

/**
 * Izračunavanje zakasnine
 * Zakasnina iznosi 100 RSD po dan kašnjenja
 * 
 * @param string $dueDate Rok za vraćanje
 * @param string $returnDate Datum vraćanja (opciono, podrazumevano danas)
 * @return float Iznos zakasnine
 */
function calculateLateFee($dueDate, $returnDate = null) {
    $due = new DateTime($dueDate);
    $end = $returnDate ? new DateTime($returnDate) : new DateTime();
    
    if ($end <= $due) return 0;
    
    $days = $end->diff($due)->days;
    return $days * 100; // 100 RSD per day
}

/**
 * Dohvatanje naslova stranice na osnovu trenutne stranice
 * 
 * @param string $page Naziv stranice
 * @return string Naslov stranice
 */
function getPageTitle($page) {
    $titles = [
        'home' => 'Početna',
        'login' => 'Prijava',
        'register' => 'Registracija',
        'books' => 'Knjige',
        'book_create' => 'Dodaj knjigu',
        'book_edit' => 'Izmeni knjigu',
        'book_show' => 'Detalji knjige',
        'rentals' => 'Iznajmljivanja',
        'rental_create' => 'Iznajmi knjigu',
        'rental_history' => 'Istorija iznajmljivanja',
        'users' => 'Korisnici',
        'user_profile' => 'Profil',
        'user_edit' => 'Izmena profila',
        'statistics' => 'Statistika',
        'logout' => 'Odjava'
    ];
    
    return $titles[$page] ?? 'Biblioteka';
}

/**
 * Dohvatanje trenutne stranice iz URL parametara
 * 
 * @return string Naziv trenutne stranice
 */
function getCurrentPage() {
    return isset($_GET['page']) ? $_GET['page'] : 'home';
}

/**
 * Provera da li je trenutna stranica jednaka zadatoj
 * 
 * @param string $page Naziv stranice za proveru
 * @return bool True ako je trenutna stranica jednaka zadatoj
 */
function isPage($page) {
    return getCurrentPage() === $page;
}

/**
 * Dohvatanje CSS klase za aktivnu navigaciju
 * 
 * @param string $page Naziv stranice
 * @return string CSS klasa 'active' ili prazan string
 */
function activeClass($page) {
    return isPage($page) ? 'active' : '';
}
