<?php
/**
 * Kontroler za korisničke profile i administraciju korisnika.
 */
class UserController extends BaseController {
    public function index() {
        $this->render('users/index.php');
    }

    public function profile() {
        $this->render('users/profile.php');
    }

    public function edit() {
        $this->render('users/edit.php');
    }

    public function delete() {
        requireEmployee();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCSRFToken($_POST['csrf_token'] ?? '')) {
                setFlash('error', 'Sigurnosni token nije validan. Pokušajte ponovo.');
                redirect('/index.php?page=users');
            }

            $userId = intval($_POST['id'] ?? 0);
            $userModel = new User();
            $result = $userModel->delete($userId);

            if ($result['success']) {
                setFlash('success', 'Korisnik uspešno obrisan.');
            } else {
                setFlash('error', $result['message']);
            }
        }

        redirect('/index.php?page=users');
    }
}
