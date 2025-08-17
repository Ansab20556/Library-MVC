<?php
namespace App\Models;

use App\Core\Database;
use PDO;

abstract class BaseModel {
    protected PDO $pdo;
    public function __construct(Database $db) { $this->pdo = $db->pdo(); }
}
