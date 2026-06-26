<?php
/**
 * Kontroler za prikaz knjiga i akcije nad knjigama.
 */
class BookController extends BaseController {
    public function index() {
        $this->render('books/index.php');
    }

    public function show() {
        $this->render('books/show.php');
    }

    public function create() {
        $this->render('books/create.php');
    }

    public function edit() {
        $this->render('books/edit.php');
    }

    public function delete() {
        requireEmployee();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Sigurnosni token nije validan. Pokušajte ponovo.');
                redirect('/index.php?page=books');
            }

            $bookId = intval($_POST['id'] ?? 0);
            $bookModel = new Book();
            $result = $bookModel->delete($bookId);

            if ($result['success']) {
                setFlash('success', 'Knjiga uspešno obrisana.');
            } else {
                setFlash('error', $result['message']);
            }
        }

        redirect('/index.php?page=books');
    }
}
