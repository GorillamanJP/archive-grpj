<?php
/*
メモ: 例外パターンの変更について
例外のうち、何が起こったか分かるもの(
    例:
    ・データベースにつながらなかった
    ・指定したIDのものが見つからなかった
    ・ユーザー名かパスワードが違う
    など
)は例外の番号を指定する。
0: 自作の例外(下記コードだとユーザー検証失敗の部分)
1: 使ったクラスの例外(PDOExceptionなど)
2: これ以降は使ったクラスが複数ある場合や例外のパターンがいくつか増えた場合に連番で増やす。細かいことは書き換えながら考える。
-1: 予期しない例外(ThrowableやExceptionなど大きなくくりで例外を捕まえた場合)
*/
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
            $sql = "INSERT INTO order_orders (date, total_amount, total_price, is_received) VALUES (:date, :total_amount, :total_price, :is_received)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindValue(":total_amount", $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);
            $stmt->bindValue(":is_received", false, PDO::PARAM_BOOL);

            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            $this->send_notification("注文", "新しい注文 {$id} 番があります！");

            return $this->get_from_id($id);
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_id(int $id): Order_Order
    {
        try {
            $sql = "SELECT * FROM order_orders WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $order = $stmt->fetch(PDO::FETCH_ASSOC);
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
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_all(): array|null
    {
        try {
            $sql = "SELECT id FROM order_orders WHERE is_received = 0 ORDER BY id DESC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($orders) {
                $orders_array = [];
                foreach ($orders as $order) {
                    $order_obj = new Order_Order();
                    $orders_array[] = $order_obj->get_from_id($order["id"]);
                    $order_obj->close();
                }
                return $orders_array;
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

    public function get_all_all(): array|null
    {
        try {
            $sql = "SELECT id FROM order_orders ORDER BY id DESC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($orders) {
                $orders_array = [];
                foreach ($orders as $order) {
                    $order_obj = new Order_Order();
                    $orders_array[] = $order_obj->get_from_id($order["id"]);
                    $order_obj->close();
                }
                return $orders_array;
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

    public function delete(): void
    {
        try {
            $sql = "DELETE FROM order_orders WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function receive(): void
    {
        try {
            $sql = "UPDATE order_orders SET is_received = 1 WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();

            $this->send_notification("注文", "注文番号 {$this->id} 番の注文が受け取られました！");
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}