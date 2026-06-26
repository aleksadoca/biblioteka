<?php
/**
 * Pregled svih iznajmljivanja
 * Prikazuje aktivna, vraćena i zakašnjela iznajmljivanja
 */

// Provera prijave - zahteva se autentifikacija
requireLogin();

// Kreiranje modela za rad sa iznajmljivanjima
$rentalModel = new Rental();
$bookModel = new Book();

// Dohvatanje filtera iz URL parametara
$filtori = [
    'status' => $_GET['status'] ?? '',
    'user_id' => $_GET['user_id'] ?? ''
];

// Ako korisnik nije zaposleni, prikazujemo samo njegova iznajmljivanja
if (!isEmployee()) {
    $filtori['user_id'] = $_SESSION['user_id'];
}

// Dohvatanje podataka
$iznajmljivanja = $rentalModel->getAll($filtori);
?>

<!-- Zaglavlje stranice -->
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-arrow-left-right"></i> Iznajmljivanja</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="/index.php?page=rental_create" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo iznajmljivanje
        </a>
    </div>
</div>

<!-- Filteri za status -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/index.php">
            <input type="hidden" name="page" value="rentals">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Svi statusi</option>
                        <option value="active" <?= $filtori['status'] === 'active' ? 'selected' : '' ?>>Aktivno</option>
                        <option value="late" <?= $filtori['status'] === 'late' ? 'selected' : '' ?>>Zakašnjelo</option>
                        <option value="returned" <?= $filtori['status'] === 'returned' ? 'selected' : '' ?>>Vraćeno</option>
                    </select>
                </div>
                <?php if (isEmployee()): ?>
                    <div class="col-md-4">
                        <label for="user_id" class="form-label">Korisnik</label>
                        <input type="number" class="form-control" id="user_id" name="user_id" 
                               placeholder="ID korisnika" value="<?= e($filtori['user_id']) ?>">
                    </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel"></i> Primeni filtere
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Rezultati -->
<div class="mb-3">
    <span class="text-muted">Pronađeno: <?= count($iznajmljivanja) ?> iznajmljivanja</span>
</div>

<!-- Tabela iznajmljivanja -->
<?php if (empty($iznajmljivanja)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nema iznajmljivanja za zadate kriterijume.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Knjiga</th>
                    <th>Korisnik</th>
                    <th>Datum iznajmljivanja</th>
                    <th>Rok za vraćanje</th>
                    <th>Datum vraćanja</th>
                    <th>Status</th>
                    <th>Zakasnina</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($iznajmljivanja as $iznajmljivanje): ?>
                    <tr>
                        <td><?= $iznajmljivanje['id'] ?></td>
                        <td>
                            <a href="/index.php?page=book_show&id=<?= $iznajmljivanje['book_id'] ?>">
                                <?= e($iznajmljivanje['book_title']) ?>
                            </a>
                            <br>
                            <small class="text-muted"><?= e($iznajmljivanje['book_author']) ?></small>
                        </td>
                        <td>
                            <?= e($iznajmljivanje['full_name']) ?>
                            <br>
                            <small class="text-muted">@<?= e($iznajmljivanje['username']) ?></small>
                        </td>
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
                        <td>
                            <?php if ($iznajmljivanje['status'] !== 'returned' && isEmployee()): ?>
                                <form method="POST" action="/index.php?page=rental_return" class="d-inline">
                                    <input type="hidden" name="csrf_token" value="<?= e(generateCSRFToken()) ?>">
                                    <input type="hidden" name="rental_id" value="<?= $iznajmljivanje['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-success" 
                                            onclick="return confirm('Da li ste sigurni da želite da vratite ovu knjigu?')">
                                        <i class="bi bi-check-circle"></i> Vrati
                                    </button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
