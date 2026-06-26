<?php
/**
 * Pregled svih korisnika
 * Samo zaposleni imaju pristup ovoj stranica
 */

// Provera da li je korisnik zaposleni
requireEmployee();

// Kreiranje modela
$userModel = new User();

// Dohvatanje parametara za pretragu
$pretraga = $_GET['search'] ?? '';

// Dohvatanje svih korisnika
$korisnici = $userModel->getAll($pretraga);
?>

<!-- Zaglavlje stranice -->
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-people"></i> Korisnici</h2>
        <p class="text-muted">Pregled i upravljanje registrovanim korisnicima</p>
    </div>
</div>

<!-- Pretraga korisnika -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/index.php">
            <input type="hidden" name="page" value="users">
            <div class="row g-3">
                <div class="col-md-8">
                    <label for="search" class="form-label">Pretraga korisnika</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Pretraži po imenu, korisničkom imenu ili emailu..." 
                           value="<?= e($pretraga) ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Pretraži
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Rezultati -->
<div class="mb-3">
    <span class="text-muted">Pronađeno: <?= count($korisnici) ?> korisnika</span>
</div>

<!-- Tabela korisnika -->
<?php if (empty($korisnici)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nema korisnika za zadate kriterijume.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Korisničko ime</th>
                    <th>Puno ime</th>
                    <th>Email</th>
                    <th>Uloga</th>
                    <th>Registrovan</th>
                    <th>Akcije</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($korisnici as $korisnik): ?>
                    <tr>
                        <td><?= $korisnik['id'] ?></td>
                        <td>
                            <strong><?= e($korisnik['username']) ?></strong>
                        </td>
                        <td><?= e($korisnik['full_name']) ?></td>
                        <td><?= e($korisnik['email']) ?></td>
                        <td>
                            <?php
                            // Određivanje badge-a na osnovu uloge
                            $ulogaKlase = [
                                'employee' => 'bg-primary',
                                'user' => 'bg-info'
                            ];
                            $ulogaTekstovi = [
                                'employee' => 'Zaposleni',
                                'user' => 'Korisnik'
                            ];
                            $klasa = $ulogaKlase[$korisnik['role']] ?? 'bg-secondary';
                            $tekst = $ulogaTekstovi[$korisnik['role']] ?? $korisnik['role'];
                            ?>
                            <span class="badge <?= $klasa ?>"><?= $tekst ?></span>
                        </td>
                        <td><?= formatDate($korisnik['created_at']) ?></td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="/index.php?page=user_edit&id=<?= $korisnik['id'] ?>" 
                                   class="btn btn-outline-warning" title="Izmeni">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="/index.php?page=user_profile&id=<?= $korisnik['id'] ?>" 
                                   class="btn btn-outline-info" title="Profil">
                                    <i class="bi bi-person"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>
