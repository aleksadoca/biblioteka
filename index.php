<?php
/**
 * Glavni ulazni fajl aplikacije
 * Svi zahtevi prolaze kroz ovaj fajl
 */

// Uključivanje konfiguracije baze podataka
require_once __DIR__ . '/config/database.php';

// Uključivanje pomoćnih funkcija
require_once __DIR__ . '/helpers/functions.php';

// Uključivanje autentifikacije (pokreće sesiju)
require_once __DIR__ . '/helpers/auth.php';

// Uključivanje modela
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Book.php';
require_once __DIR__ . '/models/Rental.php';

// Uključivanje kontrolera
require_once __DIR__ . '/controllers/BaseController.php';
require_once __DIR__ . '/controllers/PageController.php';
require_once __DIR__ . '/controllers/BookController.php';
require_once __DIR__ . '/controllers/RentalController.php';
require_once __DIR__ . '/controllers/UserController.php';
require_once __DIR__ . '/controllers/StatisticsController.php';

// Dohvatanje trenutne stranice iz URL parametara
$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

// Ažuriranje statusa zakašnjelih iznajmljivanja
$rentalModel = new Rental();
$rentalModel->updateLateStatus();

// Uključivanje zaglavlja
require_once __DIR__ . '/views/layouts/header.php';

$viewBasePath = __DIR__ . '/views';
$routes = [
    'home' => [new PageController($viewBasePath), 'home'],
    'login' => [new PageController($viewBasePath), 'login'],
    'register' => [new PageController($viewBasePath), 'register'],
    'logout' => [new PageController($viewBasePath), 'logout'],
    'books' => [new BookController($viewBasePath), 'index'],
    'book_show' => [new BookController($viewBasePath), 'show'],
    'book_create' => [new BookController($viewBasePath), 'create'],
    'book_edit' => [new BookController($viewBasePath), 'edit'],
    'book_delete' => [new BookController($viewBasePath), 'delete'],
    'rentals' => [new RentalController($viewBasePath), 'index'],
    'rental_create' => [new RentalController($viewBasePath), 'create'],
    'rental_return' => [new RentalController($viewBasePath), 'returnBook'],
    'rental_history' => [new RentalController($viewBasePath), 'history'],
    'users' => [new UserController($viewBasePath), 'index'],
    'user_profile' => [new UserController($viewBasePath), 'profile'],
    'user_edit' => [new UserController($viewBasePath), 'edit'],
    'user_delete' => [new UserController($viewBasePath), 'delete'],
    'statistics' => [new StatisticsController($viewBasePath), 'index'],
];

$route = $routes[$page] ?? [new PageController($viewBasePath), 'notFound'];
call_user_func($route);

require_once __DIR__ . '/views/layouts/footer.php';
?>