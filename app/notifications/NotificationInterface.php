<?php
namespace App\Notifications;

interface NotificationInterface {
    public function send(string $to, string $message): bool;
}
