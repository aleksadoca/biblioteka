<?php
/**
 * Prikaz liste knjiga
 * Sadrži filtere i pretragu
 */
$bookModel = new Book();

// Dohvatanje filtera iz URL parametara
$filters = [
    'search' => $_GET['search'] ?? '',
    'author' => $_GET['author'] ?? '',
    'genre' => $_GET['genre'] ?? '',
    'year' => $_GET['year'] ?? ''
];

// Dohvatanje podataka
$books = $bookModel->getAll($filters);
$genres = $bookModel->getGenres();
$authors = $bookModel->getAuthors();
$years = $bookModel->getYears();
?>

<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="bi bi-book"></i> Knjige</h2>
    </div>
    <div class="col-md-4 text-end">
        <?php if (isLoggedIn() && isEmployee()): ?>
            <a href="/index.php?page=book_create" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Dodaj knjigu
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Filteri -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="/index.php">
            <input type="hidden" name="page" value="books">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Pretraga</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="Naslov ili autor..." value="<?= e($filters['search']) ?>">
                </div>
                <div class="col-md-2">
                    <label for="author" class="form-label">Autor</label>
                    <select class="form-select" id="author" name="author">
                        <option value="">Svi autori</option>
                        <?php foreach ($authors as $author): ?>
                            <option value="<?= e($author) ?>" <?= $filters['author'] === $author ? 'selected' : '' ?>>
                                <?= e($author) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="genre" class="form-label">Žanr</label>
                    <select class="form-select" id="genre" name="genre">
                        <option value="">Svi žanrovi</option>
                        <?php foreach ($genres as $genre): ?>
                            <option value="<?= e($genre) ?>" <?= $filters['genre'] === $genre ? 'selected' : '' ?>>
                                <?= e($genre) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="year" class="form-label">Godina</label>
                    <select class="form-select" id="year" name="year">
                        <option value="">Sve godine</option>
                        <?php foreach ($years as $year): ?>
                            <option value="<?= $year ?>" <?= $filters['year'] == $year ? 'selected' : '' ?>>
                                <?= $year ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Pretraži
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Broj rezultata -->
<div class="mb-3">
    <span class="text-muted">Pronađeno: <?= count($books) ?> knjiga</span>
    <?php if (array_filter($filters)): ?>
        <a href="/index.php?page=books" class="btn btn-sm btn-outline-secondary ms-2">
            <i class="bi bi-x-circle"></i> Obriši filtere
        </a>
    <?php endif; ?>
</div>

<!-- Lista knjiga -->
<?php if (empty($books)): ?>
    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i> Nema knjiga za zadate kriterijume.
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($books as $book): ?>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title"><?= e($book['title']) ?></h6>
                        <p class="card-text text-muted small mb-1">
                            <i class="bi bi-person"></i> <?= e($book['author']) ?>
                        </p>
                        <p class="card-text text-muted small mb-1">
                            <i class="bi bi-tag"></i> <?= e($book['genre'] ?? 'Nepoznat') ?>
                        </p>
                        <p class="card-text text-muted small mb-2">
                            <i class="bi bi-calendar"></i> <?= $book['year'] ?? '-' ?>
                        </p>
                        <?php if ($book['isbn']): ?>
                            <p class="card-text text-muted small mb-2">
                                <i class="bi bi-upc"></i> <?= e($book['isbn']) ?>
                            </p>
                        <?php endif; ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge <?= $book['available_copies'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                <?= $book['available_copies'] ?> / <?= $book['total_copies'] ?> dostupno
                            </span>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex gap-2">
                            <a href="/index.php?page=book_show&id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-primary flex-grow-1">
                                <i class="bi bi-eye"></i> Detalji
                            </a>
                            <?php if (isLoggedIn() && isEmployee()): ?>
                                <a href="/index.php?page=book_edit&id=<?= $book['id'] ?>" class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
