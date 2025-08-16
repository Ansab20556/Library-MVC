<?php
namespace App\Core;

class Controller {
    protected function view(string $path, array $data = []): void {
        extract($data, EXTR_SKIP);
        require __DIR__ . "/../views/layout.php";
    }
}
