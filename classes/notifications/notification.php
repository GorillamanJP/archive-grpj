<?php
class Notification
{
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    private string $title;
    public function get_title(): string
    {
        return $this->title;
    }

    private string $message;
    public function get_message(): string
    {
        return $this->message;
    }

    private string $sent_date;
    public function get_sent_date(): string
    {
        return $this->sent_date;
    }

    private PDO $pdo;
    # トランザクション開始
    public function start_transaction()
    {
        try {
            $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 1, $e);
        }
    }
    # ロールバック
    public function rollback()
    {
        try {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 1, $e);
        }
    }
    # コミット
    public function commit()
    {
        try {
            if ($this->pdo->inTransaction()) {
                $this->pdo->commit();
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 1, $e);
        }
    }
    # 切断
    public function close()
    {
        unset($this->pdo);
    }
    # コンストラクタ
    public function __construct()
    {
        try {
            $password = getenv("DB_PASSWORD");
            $db_name = getenv("DB_DATABASE");
            $dsn = "mysql:host=mariadb;dbname={$db_name}";
            $this->pdo = new PDO($dsn, "root", $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 1, $e);
        }
    }

    public function create(string $title, string $message): Notification
    {
        try {
            $sql = "INSERT INTO notifications (title, message, sent_date) VALUES (:title, :message, :sent_date)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":title", $title, PDO::PARAM_STR);
            $stmt->bindValue(":message", $message, PDO::PARAM_STR);
            $stmt->bindValue(":sent_date", date("Y-m-d H:i:s"), PDO::PARAM_STR);

            $stmt->execute();

            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_id(int $id): Notification
    {
        try {
            $sql = "SELECT * FROM notifications WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $notification = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($notification) {
                $this->id = $notification["id"];
                $this->title = $notification["title"];
                $this->message = $notification["message"];
                $this->sent_date = $notification["sent_date"];
                return $this;
            } else {
                throw new Exception("指定した通知は見つかりませんでした。", 0);
            }
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_notifications_after(string $datetime): array|null
    {
        try {
            $sql = "SELECT id FROM notifications WHERE sent_date > :datetime ORDER BY sent_date ASC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":datetime", $datetime, PDO::PARAM_STR);

            $stmt->execute();

            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($notifications) {
                $notifications_array = [];
                foreach ($notifications as $notification) {
                    $notification_obj = new Notification();
                    $notifications_array[] = $notification_obj->get_from_id($notification["id"]);
                    $notification_obj->close();
                }
                return $notifications_array;
            } else {
                return null;
            }
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_all():array|null{
        try {
            $sql = "SELECT id FROM notifications ORDER BY sent_date ASC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($notifications) {
                $notifications_array = [];
                foreach ($notifications as $notification) {
                    $notification_obj = new Notification();
                    $notifications_array[] = $notification_obj->get_from_id($notification["id"]);
                    $notification_obj->close();
                }
                return $notifications_array;
            } else {
                return null;
            }
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}