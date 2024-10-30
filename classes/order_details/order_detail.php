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

    public function create(int $order_id, string $item_name, int $item_price, int $quantity, int $subtotal): Order_Detail
    {
        try {
            $sql = "INSERT INTO order_details (order_id, item_name, item_price, quantity, subtotal) VALUES (:order_id, :item_name, :item_price, :quantity, :subtotal)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":order_id", $order_id, PDO::PARAM_INT);
            $stmt->bindValue(":item_name", $item_name, PDO::PARAM_STR);
            $stmt->bindValue(":item_price", $item_price, PDO::PARAM_INT);
            $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindValue(":subtotal", $subtotal, PDO::PARAM_INT);

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

    public function get_from_id(int $id): Order_Detail
    {
        try {
            $sql = "SELECT * FROM order_details WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $order_detail = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($order_detail) {
                $this->id = $order_detail["id"];
                $this->order_id = $order_detail["order_id"];
                $this->item_name = $order_detail["item_name"];
                $this->item_price = $order_detail["item_price"];
                $this->quantity = $order_detail["quantity"];
                $this->subtotal = $order_detail["subtotal"];
                return $this;
            } else {
                throw new Exception("指定された注文詳細は見つかりませんでした。", 0);
            }
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_from_order_id(int $order_id): array
    {
        try {
            $sql = "SELECT id FROM order_details WHERE order_id = :order_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":order_id", $order_id, PDO::PARAM_INT);

            $stmt->execute();

            $order_details = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}