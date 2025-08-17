<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Borrow;
use App\Notifications\NotificationInterface;
use App\Notifications\EmailNotification;

class BorrowController extends Controller {
    private Borrow $borrow;
    private NotificationInterface $notifier;

    public function __construct() {
        $db = new Database();
        $this->borrow = new Borrow($db);
        $this->notifier = new EmailNotification(); // ممكن يكون SMSNotification
    }

    public function index(): void {
        $this->view('borrows/index', ['title'=>'Borrow']);
    }

    public function create(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = (int)($_POST['user_id'] ?? 0);
            $bookId = (int)($_POST['book_id'] ?? 0);
            $dueAt  = $_POST['due_at'] ?? date('Y-m-d H:i:s', strtotime('+7 days'));
            if ($userId && $bookId) {
                if ($this->borrow->borrowBook($userId, $bookId, $dueAt)) {
                    $this->notifier->send('user@example.com', 'Borrow confirmed');
                }
            }
            header('Location: /?controller=borrow&action=index'); exit;
        }
        $this->index();
    }
}
