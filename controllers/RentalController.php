<?php
/**
 * Kontroler za iznajmljivanja i vraćanje knjiga.
 */
class RentalController extends BaseController {
    public function index() {
        $this->render('rentals/index.php');
    }

    public function create() {
        $this->render('rentals/create.php');
    }

    public function history() {
        $this->render('rentals/history.php');
    }

    public function returnBook() {
        requireEmployee();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Sigurnosni token nije validan. Pokušajte ponovo.');
                redirect('/index.php?page=rentals');
            }

            $rentalId = intval($_POST['rental_id'] ?? 0);
            $rentalModel = new Rental();
            $result = $rentalModel->returnBook($rentalId);

            if ($result['success']) {
                setFlash('success', $result['message']);
            } else {
                setFlash('error', $result['message']);
            }
        }

        redirect('/index.php?page=rentals');
    }
}
