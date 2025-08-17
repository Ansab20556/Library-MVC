<?php
namespace App\Models;

use App\Traits\LoggingTrait;

class User extends BaseModel {
    use LoggingTrait;

    public function create(string $name, string $email): int {
        $stmt = $this->pdo->prepare("INSERT INTO users (name,email) VALUES (:n,:e)");
        $stmt->execute([':n'=>$name, ':e'=>$email]);
        $id = (int)$this->pdo->lastInsertId();
        $this->logAction("User added: {$name}");
        return $id;
    }

    public function delete(int $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id=:id");
        return $stmt->execute([':id'=>$id]);
    }

    public function all(): array {
        return $this->pdo->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
    }
}
