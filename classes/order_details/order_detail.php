<?php
class Order_Detail
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

    private int $item_id;
    public function get_item_id(): int
    {
        return $this->item_id;
    }

    private string $item_name;
    public function get_item_name(): string
    {
        return $this->item_name;
    }

    private int $item_price;
    public function get_item_price(): int
    {
        return $this->item_price;
    }

    private int $quantity;
    public function get_quantity(): int
    {
        return $this->quantity;
    }

    private int $subtotal;
    public function get_subtotal(): int
    {
        return $this->subtotal;
    }

    private PDO $pdo;
    # 接続
    public function open()
    {
        try {
            $password = getenv("DB_PASSWORD");
            $db_name = getenv("DB_DATABASE");
            $dsn = "mysql:host=mariadb;dbname={$db_name}";
            $this->pdo = new PDO($dsn, "root", $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function close()
    {
        unset($this->pdo);
    }
    # 通知を送る
    private function send_notification(string $title, string $message)
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
        $notification = new Notification();
        $notification->create($title, $message);
    }

    public function create(int $order_id, int $item_id, string $item_name, int $item_price, int $quantity, int $subtotal): Order_Detail
    {
        try {
            $this->open();
            $sql = "INSERT INTO order_details (order_id, item_id, item_name, item_price, quantity, subtotal) VALUES (:order_id, :item_id, :item_name, :item_price, :quantity, :subtotal)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":order_id", $order_id, PDO::PARAM_INT);
            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);
            $stmt->bindValue(":item_name", $item_name, PDO::PARAM_STR);
            $stmt->bindValue(":item_price", $item_price, PDO::PARAM_INT);
            $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindValue(":subtotal", $subtotal, PDO::PARAM_INT);

            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            $this->close();

            $this->send_notification("注文詳細", "{$item_name} が {$quantity} 個注文されました！");

            return $this->get_from_id($id);
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_id(int $id): Order_Detail
    {
        try {
            $this->open();
            $sql = "SELECT * FROM order_details WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $order_detail = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($order_detail) {
                $this->id = $order_detail["id"];
                $this->order_id = $order_detail["order_id"];
                $this->item_id = $order_detail["item_id"];
                $this->item_name = $order_detail["item_name"];
                $this->item_price = $order_detail["item_price"];
                $this->quantity = $order_detail["quantity"];
                $this->subtotal = $order_detail["subtotal"];
                return $this;
            } else {
                throw new Exception("指定された注文詳細は見つかりませんでした。", 0);
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_from_order_id(int $order_id): array
    {
        try {
            $this->open();
            $sql = "SELECT id FROM order_details WHERE order_id = :order_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":order_id", $order_id, PDO::PARAM_INT);

            $stmt->execute();

            $order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
            if ($order_details) {
                $order_details_array = [];
                foreach ($order_details as $order_detail) {
                    $order_detail_obj = new Order_Detail();
                    $order_details_array[] = $order_detail_obj->get_from_id($order_detail["id"]);
                }
                return $order_details_array;
            } else {
                throw new Exception("指定した注文番号に紐づけられたデータがありません。", 0);
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