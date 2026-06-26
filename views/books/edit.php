<?php
/**
 * Forma za izmenu knjige
 * Samo zaposleni imaju pristup
 */
requireEmployee();

$bookModel = new Book();

$bookId = intval($_GET['id'] ?? 0);
$book = $bookModel->getById($bookId);

if (!$book) {
    setFlash('error', 'Knjiga nije pronađena.');
    redirect('/index.php?page=books');
}

// Obrada forme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Sigurnosni token nije validan. Pokušajte ponovo.';
    }

    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'author' => trim($_POST['author'] ?? ''),
        'isbn' => trim($_POST['isbn'] ?? ''),
        'genre' => trim($_POST['genre'] ?? ''),
        'year' => intval($_POST['year'] ?? 0),
        'available_copies' => intval($_POST['available_copies'] ?? 0),
        'total_copies' => intval($_POST['total_copies'] ?? 1),
        'description' => trim($_POST['description'] ?? ''),
        'cover_image' => trim($_POST['cover_image'] ?? '')
    ];
    
    // Validacija
    $errors = $errors ?? [];
    
    if (empty($data['title'])) {
        $errors[] = 'Naslov je obavezan.';
    }
    
    if (empty($data['author'])) {
        $errors[] = 'Autor je obavezan.';
    }
    
    if ($data['total_copies'] < 1) {
        $errors[] = 'Ukupan broj kopija mora biti najmanje 1.';
    }
    
    if ($data['available_copies'] > $data['total_copies']) {
        $errors[] = 'Dostupne kopije ne mogu biti veće od ukupnog broja.';
    }
    
    if (empty($errors)) {
        if ($bookModel->update($bookId, $data)) {
            setFlash('success', 'Knjiga uspešno ažurirana.');
            redirect('/index.php?page=book_show&id=' . $bookId);
        } else {
            $errors[] = 'Greška prilikom ažuriranja knjige.';
        }
    }
}
?>

<div class="row mb-4">
    <div class="col-12">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/index.php?page=books">Knjige</a></li>
                <li class="breadcrumb-item"><a href="/index.php?page=book_show&id=<?= $book['id'] ?>"><?= e($book['title']) ?></a></li>
                <li class="breadcrumb-item active">Izmena</li>
            </ol>
        </nav>
        <h2><i class="bi bi-pencil"></i> Izmena knjige</h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= e($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/index.php?page=book_edit&id=<?= $book['id'] ?>">
                    <input type="hidden" name="csrf_token" value="<?= e(generateCSRFToken()) ?>">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label for="title" class="form-label">Naslov *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= e($_POST['title'] ?? $book['title']) ?>" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" 
                                   value="<?= e($_POST['isbn'] ?? $book['isbn']) ?>">
                        </div>
                        
                        <div class="col-md-6">
                            <label for="author" class="form-label">Autor *</label>
                            <input type="text" class="form-control" id="author" name="author" 
                                   value="<?= e($_POST['author'] ?? $book['author']) ?>" required>
                        </div>
                        
                        <div class="col-md-3">
                            <label for="genre" class="form-label">Žanr</label>
                            <input type="text" class="form-control" id="genre" name="genre" 
                                   value="<?= e($_POST['genre'] ?? $book['genre']) ?>">
                        </div>
                        
                        <div class="col-md-3">
                            <label for="year" class="form-label">Godina</label>
                            <input type="number" class="form-control" id="year" name="year" 
                                   value="<?= e($_POST['year'] ?? $book['year']) ?>" min="1000" max="<?= date('Y') ?>">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="total_copies" class="form-label">Ukupno kopija *</label>
                            <input type="number" class="form-control" id="total_copies" name="total_copies" 
                                   value="<?= e($_POST['total_copies'] ?? $book['total_copies']) ?>" min="1" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label for="available_copies" class="form-label">Dostupno kopija</label>
                            <input type="number" class="form-control" id="available_copies" name="available_copies" 
                                   value="<?= e($_POST['available_copies'] ?? $book['available_copies']) ?>" min="0">
                        </div>
                        
                        <div class="col-md-4">
                            <label for="cover_image" class="form-label">URL slike</label>
                            <input type="url" class="form-control" id="cover_image" name="cover_image" 
                                   value="<?= e($_POST['cover_image'] ?? $book['cover_image']) ?>">
                        </div>
                        
                        <div class="col-12">
                            <label for="description" class="form-label">Opis</label>
                            <textarea class="form-control" id="description" name="description" rows="4"><?= e($_POST['description'] ?? $book['description']) ?></textarea>
                        </div>
                        
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Sačuvaj izmene
                            </button>
                            <a href="/index.php?page=book_show&id=<?= $book['id'] ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Nazad
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
