<!DOCTYPE html>
<!-- Zaglavlje stranice - sadrži navigaciju i CSS -->
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e(getPageTitle(getCurrentPage())) ?> - Biblioteka</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap ikone -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Prilagođeni stilovi -->
    <link href="/css/style.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body>
    <!-- Navigacija -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="/index.php">
                <i class="bi bi-book"></i> Biblioteka
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= activeClass('home') ?>" href="/index.php">
                            <i class="bi bi-house"></i> Početna
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= activeClass('books') ?>" href="/index.php?page=books">
                            <i class="bi bi-book"></i> Knjige
                        </a>
                    </li>
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link <?= activeClass('rentals') ?>" href="/index.php?page=rentals">
                                <i class="bi bi-arrow-left-right"></i> Iznajmljivanja
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= activeClass('rental_history') ?>" href="/index.php?page=rental_history">
                                <i class="bi bi-clock-history"></i> Istorija
                            </a>
                        </li>
                        <?php if (isEmployee()): ?>
                            <li class="nav-item">
                                <a class="nav-link <?= activeClass('users') ?>" href="/index.php?page=users">
                                    <i class="bi bi-people"></i> Korisnici
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= activeClass('statistics') ?>" href="/index.php?page=statistics">
                                    <i class="bi bi-graph-up"></i> Statistika
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= e($_SESSION['username']) ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/index.php?page=user_profile"><i class="bi bi-person"></i> Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="/index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Odjava</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link <?= activeClass('login') ?>" href="/index.php?page=login">
                                <i class="bi bi-box-arrow-in-right"></i> Prijava
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= activeClass('register') ?>" href="/index.php?page=register">
                                <i class="bi bi-person-plus"></i> Registracija
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Poruke sistema -->
    <div class="container mt-3">
        <?php if ($success = getFlash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?= e($success) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($error = getFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i> <?= e($error) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($warning = getFlash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?= e($warning) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Glavni sadržaj -->
    <main class="container py-4">
