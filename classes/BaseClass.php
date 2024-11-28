<?php
class BaseClass
{
    protected PDO $pdo;
    protected function open(): void
    {
        try {
            $db_name = getenv("DB_DATABASE");
            $db_user = getenv("DB_USER");
            $password = getenv("DB_PASSWORD");
            $dsn = "mysql:host=mariadb;dbname={$db_name}";
            $this->pdo = new PDO($dsn, $db_user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("データベースへの接続に失敗しました。", 1);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1);
        }
    }
    protected function close(): void
    {
        unset($this->pdo);
    }
    protected function send_notification(string $title, string $message)
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
        $notification = new Notification();
        $notification->create($title, $message);
    }
    public function run_query(string $sql, array $params): PDOStatement
    {
        $this->open();

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        $this->close();

        return $stmt;
    }
}