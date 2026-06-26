<?php
/**
 * Stranica za registraciju korisnika
 */

// Obrada forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';
    $email = trim($_POST['email'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    
    // Validacija
    $errors = [];

    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Sigurnosni token nije validan. Pokušajte ponovo.';
    }
    
    if (empty($username)) {
        $errors[] = 'Korisničko ime je obavezno.';
    } elseif (strlen($username) < 3) {
        $errors[] = 'Korisničko ime mora imati najmanje 3 karaktera.';
    }
    
    if (empty($password)) {
        $errors[] = 'Lozinka je obavezna.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Lozinka mora imati najmanje 6 karaktera.';
    }
    
    if ($password !== $passwordConfirm) {
        $errors[] = 'Lozinke se ne podudaraju.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email adresa je obavezna.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email adresa nije validna.';
    }
    
    if (empty($fullName)) {
        $errors[] = 'Puno ime je obavezno.';
    }
    
    if (empty($errors)) {
        $result = registerUser($username, $password, $email, $fullName);
        
        if ($result['success']) {
            setFlash('success', $result['message']);
            redirect('/index.php?page=login');
        } else {
            $errors[] = $result['message'];
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4">
                    <i class="bi bi-person-plus"></i> Registracija
                </h3>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/index.php?page=register">
                    <input type="hidden" name="csrf_token" value="<?= e(generateCSRFToken()) ?>">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">
                            <i class="bi bi-person"></i> Puno ime
                        </label>
                        <input type="text" class="form-control" id="full_name" name="full_name" 
                               value="<?= e($_POST['full_name'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="bi bi-person-badge"></i> Korisničko ime
                        </label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?= e($_POST['username'] ?? '') ?>" required>
                        <div class="form-text">Najmanje 3 karaktera</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope"></i> Email adresa
                        </label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?= e($_POST['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock"></i> Lozinka
                        </label>
                        <input type="password" class="form-control" id="password" name="password" required>
                        <div class="form-text">Najmanje 6 karaktera</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="password_confirm" class="form-label">
                            <i class="bi bi-lock-fill"></i> Potvrdite lozinku
                        </label>
                        <input type="password" class="form-control" id="password_confirm" name="password_confirm" required>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus"></i> Registruj se
                        </button>
                    </div>
                </form>
                
                <hr>
                
                <div class="text-center">
                    <p class="mb-0">Već imate nalog?</p>
                    <a href="/index.php?page=login" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-box-arrow-in-right"></i> Prijavite se
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
