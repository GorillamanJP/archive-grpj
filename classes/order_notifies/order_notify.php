<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class Order_Notify extends BaseClass
{
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    private int $order_id;
    public function get_order_id(): int
    {
        return $this->order_id;
    }

    private string $endpoint;
    public function get_endpoint(): string
    {
        return $this->endpoint;
    }

    private string $user_public_key;
    public function get_user_public_key(): string
    {
        return $this->user_public_key;
    }

    private string $user_auth_token;
    public function get_user_auth_token(): string
    {
        return $this->user_auth_token;
    }

    private string $public_key;
    public function get_public_key(): string
    {
        return $this->public_key;
    }

    private string $private_key;
    public function get_private_key(): string
    {
        return $this->private_key;
    }

    public function __construct()
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notify_keys/order_notify_key.php";
        $key_obj = new Order_Notify_Key();
        $keys = $key_obj->create();
        $this->public_key = $keys->get_public_key();
        $this->private_key = $keys->get_private_key();
    }

    public function create(int $order_id, string $endpoint, string $user_public_key, string $user_auth_token): Order_Notify
    {
        try {
            $this->open();

            $sql = "INSERT INTO order_notify (order_id, endpoint, user_public_key, user_auth_token) VALUES (:order_id, :endpoint, :user_public_key, :user_auth_token)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":order_id", $order_id, PDO::PARAM_INT);
            $stmt->bindValue(":endpoint", $endpoint, PDO::PARAM_STR);
            $stmt->bindValue(":user_public_key", $user_public_key, PDO::PARAM_STR);
            $stmt->bindValue(":user_auth_token", $user_auth_token, PDO::PARAM_STR);

            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            $this->close();

            return $this->get_from_id($id);
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_id(int $id): Order_Notify
    {
        try {
            $this->open();

            $sql = "SELECT * FROM order_notify WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $order_notify = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->close();

            if ($order_notify) {
                $this->id = $order_notify["id"];
                $this->order_id = $order_notify["order_id"];
                $this->endpoint = $order_notify["endpoint"];
                $this->user_public_key = $order_notify["user_public_key"];
                $this->user_auth_token = $order_notify["user_auth_token"];
                return $this;
            } else {
                throw new Exception("指定した通知送信データは見つかりませんでした。", 0);
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            if ($th->getCode() == 0) {
                throw $th;
            } else {
                throw new Exception("予期しないエラーが発生しました。", -1, $th);
            }
        }
    }

    public function get_from_order_id(int $order_id): Order_Notify
    {
        try {
            $this->open();

            $sql = "SELECT id FROM order_notify WHERE order_id = :order_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":order_id", $order_id, PDO::PARAM_INT);

            $stmt->execute();

            $order_notify = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->close();

            if ($order_notify) {
                $id = $order_notify["id"];
                return $this->get_from_id($id);
            } else {
                throw new Exception("指定した通知送信データは見つかりませんでした。", 0);
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            if ($th->getCode() == 0) {
                throw $th;
            } else {
                throw new Exception("予期しないエラーが発生しました。", -1, $th);
            }
        }
    }

    public function delete(): void
    {
        try {
            $this->open();

            $sql = "DELETE FROM order_notify WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();

            $this->close();
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function call(): void
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_notifies/call.php";
        call($this);
    }
}