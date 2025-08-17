<?php
namespace App\Models;

use App\Traits\LoggingTrait;
use DateTime;
use Exception;

class Borrow extends BaseModel {
    use LoggingTrait;

    public function borrowBook(int $userId, int $bookId, string $dueAt): bool {
        try {
            $this->pdo->beginTransaction();

            // تأكد من توفر نسخة
            $stmt = $this->pdo->prepare("SELECT available_copies FROM books WHERE id=:id FOR UPDATE");
            $stmt->execute([':id'=>$bookId]);
            $row = $stmt->fetch();
            if (!$row || (int)$row['available_copies'] <= 0) {
                throw new Exception('No copies available');
            }

            // سجل إعارة
            $stmt = $this->pdo->prepare(
                "INSERT INTO borrows (user_id, book_id, borrowed_at, due_at)
                 VALUES (:u,:b,NOW(),:due)"
            );
            $stmt->execute([':u'=>$userId, ':b'=>$bookId, ':due'=>$dueAt]);

            // نقص نسخة متاحة
            $stmt = $this->pdo->prepare(
                "UPDATE books SET available_copies = available_copies - 1 WHERE id=:id"
            );
            $stmt->execute([':id'=>$bookId]);

            $this->pdo->commit();
            $this->logAction("Borrowed: user {$userId} -> book {$bookId}");
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            $this->logAction("Borrow failed: " . $e->getMessage());
            return false;
        }
    }

    public function returnBook(int $borrowId, string $returnedAt): bool {
        try {
            $this->pdo->beginTransaction();

            // اجلب بيانات الإعارة
            $stmt = $this->pdo->prepare("SELECT * FROM borrows WHERE id=:id FOR UPDATE");
            $stmt->execute([':id'=>$borrowId]);
            $b = $stmt->fetch();
            if (!$b) { throw new Exception('Borrow not found'); }

            // احسب غرامة التأخير
            $fee = $this->calculateLateFee($b['due_at'], $returnedAt);

            // حدث الإرجاع والغرامة
            $stmt = $this->pdo->prepare(
                "UPDATE borrows SET returned_at=:r, late_fee=:f WHERE id=:id"
            );
            $stmt->execute([':r'=>$returnedAt, ':f'=>$fee, ':id'=>$borrowId]);

            // زوّد نسخة متاحة
            $stmt = $this->pdo->prepare(
                "UPDATE books SET available_copies = available_copies + 1 WHERE id=:id"
            );
            $stmt->execute([':id'=>$b['book_id']]);

            $this->pdo->commit();
            $this->logAction("Returned borrow {$borrowId} with fee {$fee}");
            return true;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            $this->logAction("Return failed: " . $e->getMessage());
            return false;
        }
    }

    // Extra Feature: غرامة التأخير 
    public function calculateLateFee(string $dueAt, string $returnedAt, float $perDay = 100.0): float {
        $due = new DateTime($dueAt);
        $ret = new DateTime($returnedAt);
        if ($ret <= $due) return 0.0;
        $days = (int)$due->diff($ret)->format('%a');
        return $days * $perDay;
    }
}
