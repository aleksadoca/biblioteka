<?php
/**
 * Stranica za prijavu korisnika
 */

// Obrada forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        setFlash('error', 'Molimo unesite korisničko ime i lozinku.');
    } elseif (loginUser($username, $password)) {
        setFlash('success', 'Uspešno ste se prijavili!');
        redirect('/index.php');
    } else {
        setFlash('error', 'Pogrešno korisničko ime ili lozinka.');
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4">
                    <i class="bi bi-box-arrow-in-right"></i> Prijava
                </h3>
                
                <form method="POST" action="/index.php?page=login">
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="bi bi-person"></i> Korisničko ime
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?= e($_POST['username'] ?? '') ?>" required autofocus>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Lozinka
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Prijavi se
                        </button>
                    </div>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p class="mb-0">Nemate nalog?</p>
                    <a href="/index.php?page=register" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-person-plus"></i> Registrujte se
                    </a>
                </div>
                
                <!-- Test pristupi -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6 class="text-muted mb-2"><i class="bi bi-info-circle"></i> Demo pristupi:</h6>
                    <small class="text-muted">
                        <strong>Zaposleni:</strong> admin / password<br>
                        <strong>Korisnik:</strong> petar / password
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
