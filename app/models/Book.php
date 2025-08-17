<?php
namespace App\Models;

use App\Traits\LoggingTrait;

class Book extends BaseModel {
    use LoggingTrait;

    public function create(string $title, string $author, int $copies = 1): int {
    $sql = "INSERT INTO books (title, author, total_copies, available_copies)
            VALUES (:title, :author, :total, :avail)";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':author'=> $author,
        ':total' => $copies,
        ':avail' => $copies
    ]);
    $id = (int)$this->pdo->lastInsertId();
    $this->logAction("Book added: {$title}");
    return $id;
    }

    public function updateInfo(int $id, string $title, string $author): bool {
        $stmt = $this->pdo->prepare("UPDATE books SET title=:t, author=:a WHERE id=:id");
        return $stmt->execute([':t'=>$title, ':a'=>$author, ':id'=>$id]);
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM books WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }

    public function all(): array {
        return $this->pdo->query("SELECT * FROM books ORDER BY id DESC")->fetchAll();
    }

    public function byId(int $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM books WHERE id=:id");
        $stmt->execute([':id'=>$id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }
}
