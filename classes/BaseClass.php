<?php
abstract class BaseClass
{
    protected PDO $pdo;
    public function open(): void
    {
        try {
            $password = getenv("DB_PASSWORD");
            $db_name = getenv("DB_DATABASE");
            $dsn = "mysql:host=mariadb;dbname={$db_name}";
            $this->pdo = new PDO($dsn, "root", $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("データベースへの接続に失敗しました。", 1);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1);
        }
    }
    public function close(): void
    {
        unset($this->pdo);
    }
    public function send_notification(string $title, string $message)
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
        $notification = new Notification();
        $notification->create($title, $message);
    }
    public function verify_int_value(...$values): bool
    {
        foreach ($values as $value) {
            if ($value > 2147483647 || $value < -2147483648) {
                return false;
            }
        }
        return true;
    }
    abstract protected function create(...$args);
    abstract protected function get_from_id(int $id);
    abstract protected function get_all();
    abstract protected function update(...$args);
    abstract protected function delete();
}