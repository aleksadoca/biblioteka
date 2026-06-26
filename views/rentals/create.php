<?php
/**
 * Kreiranje novog iznajmljivanja
 * Korisnik bira knjigu i datum vraćanje
 */

// Provera prijave
requireLogin();

// Kreiranje modela
$rentalModel = new Rental();
$bookModel = new Book();

// Dohvatanje knjige iz URL-a (ako je prosleđena)
$bookId = intval($_GET['book_id'] ?? 0);
$selectedBook = null;

if ($bookId > 0) {
    $selectedBook = $bookModel->getById($bookId);
}

// Dohvatanje svih dostupnih knjiga
$dostupneKnjige = $bookModel->getAll();
$dostupneKnjige = array_filter($dostupneKnjige, function($knjiga) {
    return $knjiga['available_copies'] > 0;
});

// Podrazumevani rok za vraćanje (14 dana od danas)
$podrazumevaniRok = date('Y-m-d', strtotime('+14 days'));

// Obrada forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = intval($_POST['book_id'] ?? 0);
    $dueDate = $_POST['due_date'] ?? '';
    
    // Validacija
    $greske = [];

    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $greske[] = 'Sigurnosni token nije validan. Pokušajte ponovo.';
    }
    
    if ($bookId <= 0) {
        $greske[] = 'Molimo izaberite knjigu.';
    }
    
    if (empty($dueDate)) {
        $greske[] = 'Datum vraćanja je obavezan.';
    } elseif (strtotime($dueDate) <= strtotime('today')) {
        $greske[] = 'Datum vraćanja mora biti u budućnosti.';
    }
    
    // Provera da li korisnik može da iznajmi
    if (!$rentalModel->canUserRent($_SESSION['user_id'])) {
        $greske[] = 'Dostigli ste maksimalan broj aktivnih iznajmljivanja (5).';
    }
    
    // Provera da li korisnik već ima ovu knjigu
    $aktivnaIznajmljivanja = $rentalModel->getUserActiveRentals($_SESSION['user_id']);
    foreach ($aktivnaIznajmljivanja as $iznajmljivanje) {
        if ($iznajmljivanje['book_id'] == $bookId) {
            $greske[] = 'Već imate aktivno iznajmljivanje za ovu knjigu.';
            break;
        }
    }
    
    if (empty($greske)) {
        $podaci = [
            'book_id' => $bookId,
            'user_id' => $_SESSION['user_id'],
            'due_date' => $dueDate
        ];
        
        $rezultat = $rentalModel->create($podaci);
        
        if ($rezultat['success']) {
            setFlash('success', $rezultat['message']);
            redirect('/index.php?page=rentals');
        } else {
            $greske[] = $rezultat['message'];
        }
    }
}
?>

<!-- Zaglavlje stranice -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/index.php?page=rentals">Iznajmljivanja</a></li>
                <li class="breadcrumb-item active">Novo iznajmljivanje</li>
            </ol>
        </nav>
        <h2><i class="bi bi-cart-plus"></i> Novo iznajmljivanje</h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <!-- Prikaz grešaka -->
                <?php if (!empty($greske)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($greske as $greska): ?>
                                <li><?= e($greska) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <!-- Informacije o korisniku -->
                <div class="alert alert-info mb-4">
                    <i class="bi bi-person"></i> 
                    <strong>Korisnik:</strong> <?= e($_SESSION['username']) ?>
                    <br>
                    <small>Možete imati maksimalno 5 aktivnih iznajmljivanja u isto vreme.</small>
                </div>
                
                <form method="POST" action="/index.php?page=rental_create">
                    <input type="hidden" name="csrf_token" value="<?= e(generateCSRFToken()) ?>">
                    <div class="row g-3">
                        <!-- Izbor knjige -->
                        <div class="col-12">
                            <label for="book_id" class="form-label">
                                <i class="bi bi-book"></i> Izaberite knjigu *
                            </label>
                            <select class="form-select" id="book_id" name="book_id" required>
                                <option value="">-- Izaberite knjigu --</option>
                                <?php foreach ($dostupneKnjige as $knjiga): ?>
                                    <option value="<?= $knjiga['id'] ?>" 
                                            <?= ($bookId == $knjiga['id']) ? 'selected' : '' ?>
                                            <?= ($knjiga['available_copies'] <= 0) ? 'disabled' : '' ?>>
                                        <?= e($knjiga['title']) ?> - <?= e($knjiga['author']) ?>
                                        (<?= $knjiga['available_copies'] ?> dostupno)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <!-- Prikaz detalja izabrane knjige -->
                        <?php if ($selectedBook): ?>
                            <div class="col-12">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6><?= e($selectedBook['title']) ?></h6>
                                        <p class="mb-1"><strong>Autor:</strong> <?= e($selectedBook['author']) ?></p>
                                        <p class="mb-1"><strong>Žanr:</strong> <?= e($selectedBook['genre'] ?? 'Nepoznat') ?></p>
                                        <p class="mb-0"><strong>Dostupno:</strong> <?= $selectedBook['available_copies'] ?> kopija</p>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Datum iznajmljivanja (prikaz samo) -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-calendar"></i> Datum iznajmljivanja
                            </label>
                            <input type="text" class="form-control" value="<?= date('d.m.Y') ?>" readonly>
                            <input type="hidden" name="rental_date" value="<?= date('Y-m-d') ?>">
                        </div>
                        
                        <!-- Rok za vraćanje -->
                        <div class="col-md-6">
                            <label for="due_date" class="form-label">
                                <i class="bi bi-calendar-check"></i> Rok za vraćanje *
                            </label>
                            <input type="date" class="form-control" id="due_date" name="due_date" 
                                   value="<?= e($_POST['due_date'] ?? $podrazumevaniRok) ?>"
                                   min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                   required>
                            <div class="form-text">Podrazumevani rok je 14 dana</div>
                        </div>
                        
                        <!-- Informacije o pravilima -->
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-body">
                                    <h6 class="card-title text-warning">
                                        <i class="bi bi-exclamation-triangle"></i> Pravila iznajmljivanja
                                    </h6>
                                    <ul class="mb-0 small">
                                        <li>Knjige se iznajmljuju na maksimalno 30 dana</li>
                                        <li>Zakasnina iznosi 100 RSD po danu kašnjenja</li>
                                        <li>Maksimalno 5 aktivnih iznajmljivanja u isto vreme</li>
                                        <li>Knjigu možete vratiti i pre roka</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Dugmad -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle"></i> Potvrdi iznajmljivanje
                            </button>
                            <a href="/index.php?page=books" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Nazad na knjige
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
