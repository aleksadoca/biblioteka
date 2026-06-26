<?php
/**
 * Osnovni kontroler sa zajedničkom metodom za učitavanje prikaza.
 */
class BaseController {
    protected $viewBasePath;

    public function __construct($viewBasePath) {
        $this->viewBasePath = rtrim($viewBasePath, '/');
    }

    protected function render($viewPath) {
        require $this->viewBasePath . '/' . ltrim($viewPath, '/');
    }
}
