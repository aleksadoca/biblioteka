<?php
/**
 * Izmena profila korisnika
 * Omogućava izmenu osnovnih podataka i lozinka
 */

// Provera prijave
requireLogin();

// Kreiranje modela
$userModel = new User();

// Dohvatanje ID korisnika
$userId = intval($_GET['id'] ?? $_SESSION['user_id']);

// Zaposleni mogu menjati sve profile, korisnici samo svoj
if (!isEmployee() && $userId != $_SESSION['user_id']) {
    setFlash('error', 'Nemate pristup za izmenu ovog profila.');
    redirect('/index.php?page=user_profile');
}

// Dohvatanje podataka o korisniku
$korisnik = $userModel->getById($userId);

if (!$korisnik) {
    setFlash('error', 'Korisnik nije pronađen.');
    redirect('/index.php?page=home');
}

// Obrada forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $greske[] = 'Sigurnosni token nije validan. Pokušajte ponovo.';
    }

    $podaci = [
        'email' => trim($_POST['email'] ?? ''),
        'full_name' => trim($_POST['full_name'] ?? ''),
        'password' => $_POST['password'] ?? ''
    ];
    
    // Dodavanje role samo za zaposlene
    if (isEmployee() && isset($_POST['role'])) {
        $podaci['role'] = $_POST['role'];
    }
    
    // Validacija
    $greske = $greske ?? [];
    
    if (empty($podaci['email'])) {
        $greske[] = 'Email adresa je obavezna.';
    } elseif (!filter_var($podaci['email'], FILTER_VALIDATE_EMAIL)) {
        $greske[] = 'Email adresa nije validna.';
    }
    
    if (empty($podaci['full_name'])) {
        $greske[] = 'Puno ime je obavezno.';
    }
    
    // Provera lozinke samo ako je uneta
    if (!empty($podaci['password']) && strlen($podaci['password']) < 6) {
        $greske[] = 'Lozinka mora imati najmanje 6 karaktera.';
    }
    
    // Provera jedinstvenosti emaila
    if (empty($greske)) {
        $postojeciKorisnik = $userModel->getAll($podaci['email']);
        foreach ($postojeciKorisnik as $pk) {
            if ($pk['email'] === $podaci['email'] && $pk['id'] != $userId) {
                $greske[] = 'Email adresa je već u upotrebi.';
                break;
            }
        }
    }
    
    if (empty($greske)) {
        if ($userModel->update($userId, $podaci)) {
            setFlash('success', 'Profil uspešno ažuriran.');
            redirect('/index.php?page=user_profile&id=' . $userId);
        } else {
            $greske[] = 'Greška prilikom ažuriranja profila.';
        }
    }
}
?>

<!-- Zaglavlje stranice -->
<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <?php if (isEmployee()): ?>
                    <li class="breadcrumb-item"><a href="/index.php?page=users">Korisnici</a></li>
                <?php endif; ?>
                <li class="breadcrumb-item"><a href="/index.php?page=user_profile&id=<?= $korisnik['id'] ?>">Profil</a></li>
                <li class="breadcrumb-item active">Izmena</li>
            </ol>
        </nav>
        <h2><i class="bi bi-pencil"></i> Izmena profila</h2>
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
                
                <form method="POST" action="/index.php?page=user_edit&id=<?= $korisnik['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(generateCSRFToken()) ?>">
                    <div class="row g-3">
                        <!-- Korisničko ime (prikaz samo) -->
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="bi bi-person-badge"></i> Korisničko ime
                            </label>
                            <input type="text" class="form-control" value="<?= e($korisnik['username']) ?>" readonly>
                            <div class="form-text">Korisničko ime ne može biti promenjeno</div>
                        </div>
                        
                        <!-- Puno ime -->
                        <div class="col-md-6">
                            <label for="full_name" class="form-label">
                                <i class="bi bi-person"></i> Puno ime *
                            </label>
                            <input type="text" class="form-control" id="full_name" name="full_name" 
                                   value="<?= e($_POST['full_name'] ?? $korisnik['full_name']) ?>" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope"></i> Email adresa *
                            </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= e($_POST['email'] ?? $korisnik['email']) ?>" required>
                        </div>
                        
                        <!-- Uloga (samo za zaposlene) -->
                        <?php if (isEmployee()): ?>
                            <div class="col-md-6">
                                <label for="role" class="form-label">
                                    <i class="bi bi-shield"></i> Uloga
                                </label>
                                <select class="form-select" id="role" name="role">
                                    <option value="user" <?= ($_POST['role'] ?? $korisnik['role']) === 'user' ? 'selected' : '' ?>>
                                        Korisnik
                                    </option>
                                    <option value="employee" <?= ($_POST['role'] ?? $korisnik['role']) === 'employee' ? 'selected' : '' ?>>
                                        Zaposleni
                                    </option>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Nova lozinka -->
                        <div class="col-md-6">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock"></i> Nova lozinka
                            </label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="form-text">Ostavite prazno ako ne želite da promenite lozinku</div>
                        </div>
                        
                        <!-- Potvrda lozinke -->
                        <div class="col-md-6">
                            <label for="password_confirm" class="form-label">
                                <i class="bi bi-lock-fill"></i> Potvrdite lozinku
                            </label>
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm">
                        </div>
                        
                        <!-- Dugmad -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Sačuvaj izmene
                            </button>
                            <a href="/index.php?page=user_profile&id=<?= $korisnik['id'] ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Nazad
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript za potvrdu lozinke -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const forma = document.querySelector('form');
    const lozinka = document.getElementById('password');
    const potvrda = document.getElementById('password_confirm');
    
    forma.addEventListener('submit', function(e) {
        if (lozinka.value && lozinka.value !== potvrda.value) {
            e.preventDefault();
            alert('Lozinke se ne podudaraju!');
            potvrda.focus();
        }
    });
});
</script>
