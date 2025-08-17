<?php
namespace App\Traits;

use PDO;

trait SearchableTrait {
    public function searchByTwoColumns(PDO $pdo, string $table, string $col1, string $col2, string $term): array {
        $sql = "SELECT * FROM {$table} WHERE {$col1} LIKE :t OR {$col2} LIKE :t";
        $stmt = $pdo->prepare($sql);
        $like = "%{$term}%";
        $stmt->bindParam(':t', $like);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
