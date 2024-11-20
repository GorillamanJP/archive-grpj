<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class Notification extends BaseClass
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

    public function create(string $title, string $message): Notification
    {
        try {
            $this->open();
            $sql = "INSERT INTO notifications (title, message, sent_date) VALUES (:title, :message, :sent_date)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":title", $title, PDO::PARAM_STR);
            $stmt->bindValue(":message", $message, PDO::PARAM_STR);
            $stmt->bindValue(":sent_date", date("Y-m-d H:i:s"), PDO::PARAM_STR);

            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            $this->close();

            return $this->get_from_id($id);
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_id(int $id): Notification
    {
        try {
            $this->open();
            $sql = "SELECT * FROM notifications WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $notification = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
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
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_notifications_after(string $datetime): array|null
    {
        try {
            $this->open();
            $sql = "SELECT id FROM notifications WHERE sent_date > :datetime ORDER BY sent_date ASC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":datetime", $datetime, PDO::PARAM_STR);

            $stmt->execute();

            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
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
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_all(): array|null
    {
        try {
            $this->open();
            $sql = "SELECT id FROM notifications ORDER BY sent_date ASC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
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
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}