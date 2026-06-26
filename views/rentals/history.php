<?php
/**
 * Istorija iznajmljivanja
 * Prikazuje kompletnu istoriju svih iznajmljivanja korisnik
 */

// Provera prijave
requireLogin();

// Kreiranje modela
$rentalModel = new Rental();
$userModel = new User();

// Dohvatanje podataka
if (isEmployee()) {
    // Zaposleni vide sva iznajmljivanja
    $svaIznajmljivanja = $rentalModel->getAll();
} else {
    // Korisnici vide samo svoja iznajmljivanja
    $svaIznajmljivanja = $rentalModel->getAll(['user_id' => $_SESSION['user_id']]);
}

// Računanje statistika
$ukupnoIznajmljivanja = count($svaIznajmljivanja);
$aktivnaIznajmljivanja = 0;
$vraćenaIznajmljivanja = 0;
$kasnaIznajmljivanja = 0;
$ukupnaZakasnina = 0;

foreach ($svaIznajmljivanja as $iznajmljivanje) {
    switch ($iznajmljivanje['status']) {
        case 'active':
            $aktivnaIznajmljivanja++;
            break;
        case 'returned':
            $vraćenaIznajmljivanja++;
            break;
        case 'late':
            $kasnaIznajmljivanja++;
            break;
    }
    $ukupnaZakasnina += $iznajmljivanje['late_fee'];
}
?>

<!-- Zaglavlje stranice -->
<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-clock-history"></i> Istorija iznajmljivanja</h2>
        <?php if (!isEmployee()): ?>
            <p class="text-muted">Pregled vaših iznajmljivanja</p>
        <?php endif; ?>
    </div>
</div>

<!-- Statistike -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Ukupno</h5>
                <h2><?= $ukupnoIznajmljivanja ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Aktivno</h5>
                <h2><?= $aktivnaIznajmljivanja ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body text-center">
                <h5 class="card-title">Zakašnjelo</h5>
                <h2><?= $kasnaIznajmljivanja ?></h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center">
                <h5 class="card-title">Zakasnina</h5>
                <h2><?= formatCurrency($ukupnaZakasnina) ?></h2>
            </div>
        </div>
    </div>
</div>

<!-- Tabela istorije -->
<?php if (empty($svaIznajmljivanja)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nemate istoriju iznajmljivanja.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Knjiga</th>
                    <?php if (isEmployee()): ?>
                        <th>Korisnik</th>
                    <?php endif; ?>
                    <th>Datum iznajmljivanja</th>
                    <th>Rok za vraćanje</th>
                    <th>Datum vraćanja</th>
                    <th>Status</th>
                    <th>Zakasnina</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($svaIznajmljivanja as $iznajmljivanje): ?>
                    <tr>
                        <td><?= $iznajmljivanje['id'] ?></td>
                        <td>
                            <a href="/index.php?page=book_show&id=<?= $iznajmljivanje['book_id'] ?>">
                                <?= e($iznajmljivanje['book_title']) ?>
                            </a>
                            <br>
                            <small class="text-muted"><?= e($iznajmljivanje['book_author']) ?></small>
                        </td>
                        <?php if (isEmployee()): ?>
                            <td>
                                <?= e($iznajmljivanje['full_name']) ?>
                                <br>
                                <small class="text-muted">@<?= e($iznajmljivanje['username']) ?></small>
                            </td>
                        <?php endif; ?>
                        <td><?= formatDate($iznajmljivanje['rental_date']) ?></td>
                        <td><?= formatDate($iznajmljivanje['due_date']) ?></td>
                        <td><?= formatDate($iznajmljivanje['return_date']) ?></td>
                        <td>
                            <?php
                            // Određivanje badge-a na osnovu statusa
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
                            $klasa = $statusKlase[$iznajmljivanje['status']] ?? 'bg-secondary';
                            $tekst = $statusTekstovi[$iznajmljivanje['status']] ?? $iznajmljivanje['status'];
                            ?>
                            <span class="badge <?= $klasa ?>"><?= $tekst ?></span>
                        </td>
                        <td>
                            <?php if ($iznajmljivanje['late_fee'] > 0): ?>
                                <span class="text-danger fw-bold"><?= formatCurrency($iznajmljivanje['late_fee']) ?></span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
