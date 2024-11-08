<?php
class Order_Order
{
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    private string $date;
    public function get_date(): string
    {
        return $this->date;
    }

    private int $total_amount;
    public function get_total_amount(): int
    {
        return $this->total_amount;
    }

    private int $total_price;
    public function get_total_price(): int
    {
        return $this->total_price;
    }

    private bool $is_received;
    public function get_is_received(): bool
    {
        return $this->is_received;
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
    # 切断
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
    public function create(int $total_amount, int $total_price): Order_Order
    {
        try {
            $this->open();
            $sql = "INSERT INTO order_orders (date, total_amount, total_price, is_received) VALUES (:date, :total_amount, :total_price, :is_received)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindValue(":total_amount", $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);
            $stmt->bindValue(":is_received", false, PDO::PARAM_BOOL);

            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            $this->close();

            $this->send_notification("注文", "新しい注文 {$id} 番があります！");

            return $this->get_from_id($id);
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_id(int $id): Order_Order
    {
        try {
            $this->open();
            $sql = "SELECT * FROM order_orders WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $order = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($order) {
                $this->id = $order["id"];
                $this->date = $order["date"];
                $this->total_amount = $order["total_amount"];
                $this->total_price = $order["total_price"];
                $this->is_received = boolval($order["is_received"]);
                return $this;
            } else {
                throw new Exception("指定した注文は見つかりませんでした。", 0);
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
            $sql = "SELECT id FROM order_orders WHERE is_received = 0 ORDER BY id DESC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
            if ($orders) {
                $orders_array = [];
                foreach ($orders as $order) {
                    $order_obj = new Order_Order();
                    $orders_array[] = $order_obj->get_from_id($order["id"]);
                }
                return $orders_array;
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

    public function get_all_all(): array|null
    {
        try {
            $this->open();
            $sql = "SELECT id FROM order_orders ORDER BY id DESC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
            if ($orders) {
                $orders_array = [];
                foreach ($orders as $order) {
                    $order_obj = new Order_Order();
                    $orders_array[] = $order_obj->get_from_id($order["id"]);
                }
                return $orders_array;
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

    public function delete(): void
    {
        try {
            $this->open();
            $sql = "DELETE FROM order_orders WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();
            $this->close();
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function receive(): void
    {
        try {
            $this->open();
            $sql = "UPDATE order_orders SET is_received = 1 WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();

            $this->close();

            $this->send_notification("注文", "注文番号 {$this->id} 番の注文が受け取られました！");
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}