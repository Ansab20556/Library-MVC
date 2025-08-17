<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\User;

class UserController extends Controller {
    private User $user;
    public function __construct() { $this->user = new User(new Database()); }

    public function index(): void {
        $this->view('users/index', ['users'=>$this->user->all(), 'title'=>'Users']);
    }

    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            if ($name && $email) $this->user->create($name, $email);
            header('Location: /?controller=user&action=index'); exit;
        }
        $this->index();
    }
}
