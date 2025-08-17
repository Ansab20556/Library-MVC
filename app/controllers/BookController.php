<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Book;
use App\Traits\SearchableTrait;

class BookController extends Controller {
    use SearchableTrait;

    private Book $book;

    public function __construct() {
        $db = new Database();
        $this->book = new Book($db);
    }

    public function index(): void {
        $books = $this->book->all();
        $this->view('books/index', ['books' => $books, 'title' => 'Books']);
    }

    public function add(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title  = trim($_POST['title'] ?? '');
            $author = trim($_POST['author'] ?? '');
            $copies = (int)($_POST['copies'] ?? 1);
            if ($title && $author) {
                $this->book->create($title, $author, $copies);
            }
            header('Location: /?controller=book&action=index');
            exit;
        }
        $this->view('books/index', ['books'=>$this->book->all(), 'title'=>'Books']);
    }

    public function search(): void {
        $term = trim($_GET['q'] ?? '');
        $db   = (new \App\Core\Database())->pdo();
        $results = $term
          ? $this->searchByTwoColumns($db, 'books', 'title', 'author', $term)
          : [];
        $this->view('books/index', ['books'=>$results, 'title'=>'Search']);
    }
}
