<?php
namespace App\Notifications;

class SMSNotification implements NotificationInterface {
    public function send(string $to, string $message): bool {

        return true;
    }
}
