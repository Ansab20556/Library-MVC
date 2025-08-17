<?php
namespace App\Notifications;

class EmailNotification implements NotificationInterface {
    public function send(string $to, string $message): bool {

        return true;
    }
}
