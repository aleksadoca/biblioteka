<?php
/**
 * Stranica sa statistika
 * Prikazuje razne statistike o biblioteci
 * Samo zaposleni imaju pristup
 */

// Provera da li je korisnik zaposleni
requireEmployee();

// Kreiranje modela
$bookModel = new Book();
$rentalModel = new Rental();
$userModel = new User();

// Dohvatanje statistika
$ukupnoKnjiga = $bookModel->count();
$dostupneKnjige = $bookModel->countAvailable();
$ukupnoKorisnika = $userModel->count();
$aktivnaIznajmljivanja = $rentalModel->countActive();
$kasnaIznajmljivanja = $rentalModel->countLate();
$ukupnoIznajmljivanja = $rentalModel->count();

// Dohvatanje top knjiga
$najcitanijeKnjige = $bookModel->getMostRented(10);

// Dohvatanje najaktivnijih korisnika
$najaktivnijiKorisnici = $userModel->getMostActive(10);

// Dohvatanje statistika po žanrovima
$statistikePoZanru = $bookModel->getRentalsByGenre();
?>

<!-- Zaglavlje stranice -->
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-graph-up"></i> Statistike</h2>
        <p class="text-muted">Pregled statistika biblioteke</p>
    </div>
</div>

<!-- Osnovne statistike -->
<div class="row g-4 mb-5">
    <div class="col-md-2">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-book fs-3"></i>
                <h3 class="mt-2"><?= $ukupnoKnjiga ?></h3>
                <small>Ukupno knjiga</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-3"></i>
                <h3 class="mt-2"><?= $dostupneKnjige ?></h3>
                <small>Dostupno</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-people fs-3"></i>
                <h3 class="mt-2"><?= $ukupnoKorisnika ?></h3>
                <small>Korisnika</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-warning text-dark h-100">
            <div class="card-body text-center">
                <i class="bi bi-arrow-left-right fs-3"></i>
                <h3 class="mt-2"><?= $aktivnaIznajmljivanja ?></h3>
                <small>Aktivna</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-danger text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle fs-3"></i>
                <h3 class="mt-2"><?= $kasnaIznajmljivanja ?></h3>
                <small>Zakašnjela</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card bg-secondary text-white h-100">
            <div class="card-body text-center">
                <i class="bi bi-clock-history fs-3"></i>
                <h3 class="mt-2"><?= $ukupnoIznajmljivanja ?></h3>
                <small>Ukupno</small>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Najčitanije knjige -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Najčitanije knjige</h5>
            </div>
            <div class="card-body">
                <?php if (empty($najcitanijeKnjige)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Nema podataka o iznajmljivanjima.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Knjiga</th>
                                    <th>Autor</th>
                                    <th class="text-center">Iznajmljivanja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($najcitanijeKnjige as $indeks => $knjiga): ?>
                                    <tr>
                                        <td><?= $indeks + 1 ?></td>
                                        <td>
                                            <a href="/index.php?page=book_show&id=<?= $knjiga['id'] ?>">
                                                <?= e($knjiga['title']) ?>
                                            </a>
                                        </td>
                                        <td><?= e($knjiga['author']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-primary"><?= $knjiga['rental_count'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Najaktivniji korisnici -->
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-star"></i> Najaktivniji korisnici</h5>
            </div>
            <div class="card-body">
                <?php if (empty($najaktivnijiKorisnici)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Nema podataka o korisnicima.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Korisnik</th>
                                    <th>Ime</th>
                                    <th class="text-center">Iznajmljivanja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($najaktivnijiKorisnici as $indeks => $korisnik): ?>
                                    <tr>
                                        <td><?= $indeks + 1 ?></td>
                                        <td>
                                            <a href="/index.php?page=user_profile&id=<?= $korisnik['id'] ?>">
                                                @<?= e($korisnik['username']) ?>
                                            </a>
                                        </td>
                                        <td><?= e($korisnik['full_name']) ?></td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= $korisnik['rental_count'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Statistike po žanrovima -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-pie-chart"></i> Iznajmljivanja po žanrovima</h5>
            </div>
            <div class="card-body">
                <?php if (empty($statistikePoZanru)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Nema podataka o žanrovima.
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($statistikePoZanru as $zanr): ?>
                            <div class="col-md-4 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= e($zanr['genre']) ?></h6>
                                        <div class="d-flex justify-content-between">
                                            <span>Ukupno:</span>
                                            <strong><?= $zanr['rental_count'] ?></strong>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Vraćeno:</span>
                                            <span class="text-success"><?= $zanr['returned_count'] ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span>Zakašnjelo:</span>
                                            <span class="text-danger"><?= $zanr['late_count'] ?></span>
                                        </div>
                                        <?php if ($zanr['total_late_fees'] > 0): ?>
                                            <div class="d-flex justify-content-between">
                                                <span>Zakasnina:</span>
                                                <span class="text-warning"><?= formatCurrency($zanr['total_late_fees']) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
