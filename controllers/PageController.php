<?php
/**
 * Kontroler za početnu stranicu, prijavu, registraciju i odjavu.
 */
class PageController extends BaseController {
    public function home() {
        $this->render('home.php');
    }

    public function login() {
        $this->render('auth/login.php');
    }

    public function register() {
        $this->render('auth/register.php');
    }

    public function logout() {
        logoutUser();
    }

    public function notFound() {
        echo '<div class="alert alert-warning">';
        echo '<h4><i class="bi bi-exclamation-triangle"></i> Stranica nije pronađena</h4>';
        echo '<p>Stranica koju tražite ne postoji.</p>';
        echo '<a href="/index.php" class="btn btn-primary"><i class="bi bi-house"></i> Početna stranica</a>';
        echo '</div>';
    }
}
