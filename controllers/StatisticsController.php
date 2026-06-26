<?php
/**
 * Kontroler za statistiku biblioteke dostupnu zaposlenima.
 */
class StatisticsController extends BaseController {
    public function index() {
        $this->render('statistics/index.php');
    }
}
