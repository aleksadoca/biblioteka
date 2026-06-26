<?php
/**
 * Prikaz detalja o knjizi
 * Sadrži informacije o knjizi i opciju za iznajmljivanje
 */
$bookModel = new Book();
$rentalModel = new Rental();

$bookId = intval($_GET['id'] ?? 0);
$book = $bookModel->getById($bookId);

if (!$book) {
    setFlash('error', 'Knjiga nije pronađena.');
    redirect('/index.php?page=books');
}

// Provera da li korisnik može da iznajmi knjigu
$canRent = false;
$hasActiveRental = false;
if (isLoggedIn() && !isEmployee()) {
    $canRent = $rentalModel->canUserRent($_SESSION['user_id']) && $book['available_copies'] > 0;
    // Provera da li korisnik već ima ovu knjigu
    $userRentals = $rentalModel->getUserActiveRentals($_SESSION['user_id']);
    foreach ($userRentals as $rental) {
        if ($rental['book_id'] == $bookId) {
            $hasActiveRental = true;
            break;
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/index.php?page=books">Knjige</a></li>
                <li class="breadcrumb-item active"><?= e($book['title']) ?></li>
            </ol>
        </nav>
    </div>
    <div class="col-md-4 text-end">
        <?php if (isLoggedIn() && isEmployee()): ?>
            <a href="/index.php?page=book_edit&id=<?= $book['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Izmeni
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <!-- Slika knjige -->
    <div class="col-md-4 mb-4">
        <div class="card">
            <?php if ($book['cover_image']): ?>
                <img src="<?= e($book['cover_image']) ?>" class="card-img-top" alt="<?= e($book['title']) ?>">
            <?php else: ?>
                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                    <i class="bi bi-book fs-1 text-muted"></i>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Detalji knjige -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title"><?= e($book['title']) ?></h2>
                <h5 class="text-muted mb-3"><?= e($book['author']) ?></h5>
                
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Žanr:</strong></div>
                    <div class="col-sm-8"><?= e($book['genre'] ?? 'Nepoznat') ?></div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Godina:</strong></div>
                    <div class="col-sm-8"><?= $book['year'] ?? '-' ?></div>
                </div>
                
                <?php if ($book['isbn']): ?>
                    <div class="row mb-3">
                        <div class="col-sm-4"><strong>ISBN:</strong></div>
                        <div class="col-sm-8"><?= e($book['isbn']) ?></div>
                    </div>
                <?php endif; ?>
                
                <div class="row mb-3">
                    <div class="col-sm-4"><strong>Dostupnost:</strong></div>
                    <div class="col-sm-8">
                        <span class="badge <?= $book['available_copies'] > 0 ? 'bg-success' : 'bg-danger' ?> fs-6">
                            <?= $book['available_copies'] ?> / <?= $book['total_copies'] ?> kopija
                        </span>
                    </div>
                </div>
                
                <?php if ($book['description']): ?>
                    <div class="mt-4">
                        <h6>Opis:</h6>
                        <p class="text-muted"><?= nl2br(e($book['description'])) ?></p>
                    </div>
                <?php endif; ?>
                
                <!-- Akcije -->
                <div class="mt-4">
                    <?php if (!isLoggedIn()): ?>
                        <a href="/index.php?page=login" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Prijavite se za iznajmljivanje
                        </a>
                    <?php elseif ($hasActiveRental): ?>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i> Već imate aktivno iznajmljivanje za ovu knjigu.
                        </div>
                    <?php elseif ($canRent): ?>
                        <a href="/index.php?page=rental_create&book_id=<?= $book['id'] ?>" class="btn btn-success btn-lg">
                            <i class="bi bi-cart-plus"></i> Iznajmi knjigu
                        </a>
                    <?php elseif ($book['available_copies'] <= 0): ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle"></i> Knjiga trenutno nije dostupna.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
