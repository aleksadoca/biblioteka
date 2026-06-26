<?php
/**
 * Profil korisnika
 * Prikazuje detalje korisnika i njegova istorija iznajmljivanja
 */

// Provera prijave
requireLogin();

// Kreiranje modela
$userModel = new User();
$rentalModel = new Rental();

// Dohvatanje ID korisnika iz URL-a ili trenutnog korisnika
$userId = intval($_GET['id'] ?? $_SESSION['user_id']);

// Zaposleni mogu videti sve profile, korisnici samo svoj
if (!isEmployee() && $userId != $_SESSION['user_id']) {
    setFlash('error', 'Nemate pristup ovom profilu.');
    redirect('/index.php?page=user_profile');
}

// Dohvatanje podataka o korisniku
$korisnik = $userModel->getById($userId);

if (!$korisnik) {
    setFlash('error', 'Korisnik nije pronađen.');
    redirect('/index.php?page=home');
}

// Dohvatanje istorije iznajmljivanja
$istorijaIznajmljivanja = $userModel->getRentalHistory($userId);

// Računanje statistika
$ukupnoIznajmljivanja = count($istorijaIznajmljivanja);
$aktivnaIznajmljivanja = 0;
$kasnaIznajmljivanja = 0;
$ukupnaZakasnina = 0;

foreach ($istorijaIznajmljivanja as $iznajmljivanje) {
    if ($iznajmljivanje['status'] === 'active') {
        $aktivnaIznajmljivanja++;
    } elseif ($iznajmljivanje['status'] === 'late') {
        $kasnaIznajmljivanja++;
    }
    $ukupnaZakasnina += $iznajmljivanje['late_fee'];
}
?>

<!-- Zaglavlje stranice -->
<div class="row mb-4">
    <div class="col-md-8">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php if (isEmployee()): ?>
                    <li class="breadcrumb-item"><a href="/index.php?page=users">Korisnici</a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active">Profil</li>
            </ol>
        </nav>
        <h2><i class="bi bi-person"></i> Profil korisnika</h2>
    </div>
    <div class="col-md-4 text-end">
        <?php if (isEmployee() || $userId == $_SESSION['user_id']): ?>
            <a href="/index.php?page=user_edit&id=<?= $korisnik['id'] ?>" class="btn btn-warning">
                <i class="bi bi-pencil"></i> Izmeni profil
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <!-- Osnovni podaci -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-center mb-3">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                </div>
                <h4 class="text-center"><?= e($korisnik['full_name']) ?></h4>
                <p class="text-center text-muted">@<?= e($korisnik['username']) ?></p>
                
                <hr>
                
                <div class="mb-3">
                    <strong><i class="bi bi-envelope"></i> Email:</strong>
                    <p><?= e($korisnik['email']) ?></p>
                </div>
                
                <div class="mb-3">
                    <strong><i class="bi bi-shield"></i> Uloga:</strong>
                    <p>
                        <?php
                        $ulogaTekstovi = [
                            'employee' => 'Zaposleni',
                            'user' => 'Korisnik'
                        ];
                        $ulogaKlase = [
                            'employee' => 'bg-primary',
                            'user' => 'bg-info'
                        ];
                        ?>
                        <span class="badge <?= $ulogaKlase[$korisnik['role']] ?? 'bg-secondary' ?>">
                            <?= $ulogaTekstovi[$korisnik['role']] ?? $korisnik['role'] ?>
                        </span>
                    </p>
                </div>
                
                <div class="mb-3">
                    <strong><i class="bi bi-calendar"></i> Registrovan:</strong>
                    <p><?= formatDate($korisnik['created_at']) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistike -->
    <div class="col-md-8 mb-4">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-graph-up"></i> Statistike</h5>
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-primary"><?= $ukupnoIznajmljivanja ?></h4>
                            <small class="text-muted">Ukupno iznajmljivanja</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-success"><?= $aktivnaIznajmljivanja ?></h4>
                            <small class="text-muted">Aktivna</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-danger"><?= $kasnaIznajmljivanja ?></h4>
                            <small class="text-muted">Zakašnjela</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h4 class="text-warning"><?= formatCurrency($ukupnaZakasnina) ?></h4>
                            <small class="text-muted">Zakasnina</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Istorija iznajmljivanja -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><i class="bi bi-clock-history"></i> Istorija iznajmljivanja</h5>
                
                <?php if (empty($istorijaIznajmljivanja)): ?>
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i> Nema istorije iznajmljivanja.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>Knjiga</th>
                                    <th>Iznajmljeno</th>
                                    <th>Rok</th>
                                    <th>Vraćeno</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($istorijaIznajmljivanja as $iznajmljivanje): ?>
                                    <tr>
                                        <td>
                                            <a href="/index.php?page=book_show&id=<?= $iznajmljivanje['book_id'] ?>">
                                                <?= e($iznajmljivanje['book_title']) ?>
                                            </a>
                                        </td>
                                        <td><?= formatDate($iznajmljivanje['rental_date']) ?></td>
                                        <td><?= formatDate($iznajmljivanje['due_date']) ?></td>
                                        <td><?= formatDate($iznajmljivanje['return_date']) ?></td>
                                        <td>
                                            <?php
                                            $statusKlase = [
                                                'active' => 'bg-success',
                                                'late' => 'bg-danger',
                                                'returned' => 'bg-secondary'
                                            ];
                                            $statusTekstovi = [
                                                'active' => 'Aktivno',
                                                'late' => 'Zakašnjelo',
                                                'returned' => 'Vraćeno'
                                            ];
                                            ?>
                                            <span class="badge <?= $statusKlase[$iznajmljivanje['status']] ?? 'bg-secondary' ?>">
                                                <?= $statusTekstovi[$iznajmljivanje['status']] ?? $iznajmljivanje['status'] ?>
                                            </span>
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
