<?php
abstract class BaseClassGroup{
    protected function send_notification(string $title, string $message)
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
        $notification = new Notification();
        $notification->create($title, $message);
    }
}