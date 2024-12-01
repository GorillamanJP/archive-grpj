<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class Order_Order extends BaseClass
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

    private bool $is_call;
    public function get_is_call(): bool
    {
        return $this->is_call;
    }

    private bool $is_cancel;
    public function get_is_cancel(): bool
    {
        return $this->is_cancel;
    }

    private bool $is_received;
    public function get_is_received(): bool
    {
        return $this->is_received;
    }

    public function create(int $total_amount, int $total_price): Order_Order
    {
        try {
            $this->open();
            $sql = "INSERT INTO order_orders (date, total_amount, total_price, is_call, is_cancel, is_received) VALUES (:date, :total_amount, :total_price, :is_call, :is_cancel, :is_received)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindValue(":total_amount", $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);
            $stmt->bindValue(":is_call", false, PDO::PARAM_BOOL);
            $stmt->bindValue(":is_cancel", false, PDO::PARAM_BOOL);
            $stmt->bindValue(":is_received", false, PDO::PARAM_BOOL);

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
                $this->is_call = boolval($order["is_call"]);
                $this->is_cancel = boolval($order["is_cancel"]);
                $this->is_received = boolval($order["is_received"]);
                return $this;
            } else {
                throw new Exception("指定した注文は見つかりませんでした。", 0);
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_all(): array|null
    {
        try {
            $this->open();
            $sql = "SELECT id FROM order_orders WHERE is_received = 0 AND is_cancel = 0 ORDER BY id ASC";

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
        } catch (Throwable $th) {
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
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_range(int $offset, int $limit): array|null
    {
        try {
            $this->open();
            $sql = "SELECT id FROM order_orders ORDER BY id DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

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
        } catch (Throwable $th) {
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
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function receive(): void
    {
        try {
            $this->open();
            $sql = "UPDATE order_orders SET is_received = 1, is_call = 0, is_cancel = 0 WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();

            $this->close();

            $this->send_notification("注文", "注文番号 {$this->id} 番の注文が受け取られました！");

            $this->get_from_id($this->id);
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
        try {
            $sql = "UPDATE order_orders SET is_call = :is_call WHERE id = :id";

            $params = [
                ":id" => $this->id,
                ":is_call" => !$this->is_call ? "1" : "0"
            ];

            $stmt = $this->run_query($sql, $params);

            $this->get_from_id($this->id);

            if ($this->is_call) {
                $this->send_notification("注文", "注文番号 {$this->id} 番を呼び出しました！");
            }else{
                $this->send_notification("注文", "注文番号 {$this->id} 番の呼び出しをキャンセルしました！");
            }

        } catch (PDOException $pe) {
            throw new Exception("データベースエラーです。" . $pe->getMessage(), 1, $pe);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function cancel(): void
    {
        try {
            $sql = "UPDATE order_orders SET is_cancel = 1, is_call = 0, is_received = 0 WHERE id = :id";

            $params = [
                ":id" => $this->id
            ];

            $stmt = $this->run_query($sql, $params);

            $this->send_notification("注文", "注文番号 {$this->id} 番はキャンセルされました。");

            $this->get_from_id($this->id);
        } catch (PDOException $pe) {
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}