<?php
namespace App\Traits;

trait LoggingTrait {
    protected function logAction(string $message): void {
        $file = __DIR__ . '/../../storage.log';
        $line = '[' . date('Y-m-d H:i:s') . "] " . $message . PHP_EOL;
        @file_put_contents($file, $line, FILE_APPEND);
    }
}
