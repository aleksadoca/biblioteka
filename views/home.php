<?php
/**
 * Prikaz početne stranice
 * Sadrži statistike i brze akcije
 */
$bookModel = new Book();
$rentalModel = new Rental();
$userModel = new User();

$totalBooks = $bookModel->count();
$availableBooks = $bookModel->countAvailable();
$activeRentals = $rentalModel->countActive();
$totalUsers = $userModel->count();
?>

<div class="row mb-5">
    <div class="col-12">
        <div class="hero-section">
            <div class="position-relative" style="z-index: 1;">
                <h1 class="display-3 fw-bold mb-3">
                    <i class="bi bi-book"></i> Dobrodošli u Biblioteku
                </h1>
                <p class="lead opacity-90 mb-4" style="max-width: 600px; font-size: 1.2rem;">
                    Aplikacija za evidenciju knjiga i iznajmljivanja. Pregledajte katalog, iznajmite knjigu i pratite istoriju.
                </p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="/index.php?page=books" class="btn btn-light btn-lg px-4">
                        <i class="bi bi-search me-2"></i> Pretraži knjige
                    </a>
                    <?php if (!isLoggedIn()): ?>
                        <a href="/index.php?page=login" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Prijavi se
                        </a>
                    <?php else: ?>
                        <a href="/index.php?page=rental_create" class="btn btn-success btn-lg px-4">
                            <i class="bi bi-plus-circle me-2"></i> Iznajmi knjigu
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statističke kartice -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="stat-card stat-card-books">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p>Ukupno knjiga</p>
                    <h2><?= $totalBooks ?></h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-book"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-available">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p>Dostupne knjige</p>
                    <h2><?= $availableBooks ?></h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-rentals">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p>Aktivna iznajmljivanja</p>
                    <h2><?= $activeRentals ?></h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-arrow-left-right"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-card-users">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <p>Registrovani korisnici</p>
                    <h2><?= $totalUsers ?></h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Brze akcije -->
<div class="row g-4 mb-5">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-book"></i> Pretražite knjige</h5>
                <p class="card-text">Pronađite knjige po naslovu, autoru, žanru ili godini izdanja.</p>
                <a href="/index.php?page=books" class="btn btn-primary">
                    <i class="bi bi-search"></i> Pretraži knjige
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-arrow-left-right"></i> Iznajmite knjigu</h5>
                <p class="card-text">Prijavite se i iznajmite željenu knjigu. Možete imati do 5 aktivnih iznajmljivanja.</p>
                <?php if (isLoggedIn()): ?>
                    <a href="/index.php?page=books" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Iznajmi knjigu
                    </a>
                <?php else: ?>
                    <a href="/index.php?page=login" class="btn btn-outline-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Prijavite se
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Skorašnje knjige -->
<?php
$recentBooks = $bookModel->getAll(['search' => '']);
$recentBooks = array_slice($recentBooks, 0, 4);
?>
<div class="row mb-4">
    <div class="col-12">
        <h3 class="mb-3"><i class="bi bi-clock"></i> Najnovije u kolekciji</h3>
    </div>
</div>
<div class="row g-4">
    <?php foreach ($recentBooks as $book): ?>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="card-title"><?= e($book['title']) ?></h6>
                    <p class="card-text text-muted small mb-1">
                        <i class="bi bi-person"></i> <?= e($book['author']) ?>
                    </p>
                    <p class="card-text text-muted small mb-2">
                        <i class="bi bi-tag"></i> <?= e($book['genre'] ?? 'Nepoznat') ?>
                    </p>
                    <span class="badge <?= $book['available_copies'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                        <?= $book['available_copies'] > 0 ? 'Dostupna' : 'Nedostupna' ?>
                    </span>
                </div>
                <div class="card-footer bg-transparent">
                    <a href="/index.php?page=book_show&id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary w-100">
                        Detalji
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Pravila korišćenja -->
<div class="row mt-5">
    <div class="col-12">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-info-circle"></i> Pravila korišćenja</h5>
                <div class="row">
                    <div class="col-md-4">
                        <h6><i class="bi bi-clock"></i> Rokovi</h6>
                        <p class="small text-muted">Podrazumevani rok je 14 dana, a može se izabrati i do 30 dana pri iznajmljivanju.</p>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="bi bi-cash"></i> Zakasnina</h6>
                        <p class="small text-muted">Zakasnina iznosi 100 RSD po danu kašnjenja. Plaća se pri vraćanju knjige.</p>
                    </div>
                    <div class="col-md-4">
                        <h6><i class="bi bi-book"></i> Limit</h6>
                        <p class="small text-muted">Svaki korisnik može imati maksimalno 5 aktivnih iznajmljivanja u isto vreme.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
