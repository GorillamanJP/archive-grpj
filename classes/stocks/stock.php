<?php
class Stock
{
    # 在庫情報ID
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    # 商品ID
    private int $item_id;
    public function get_item_id(): int
    {
        return $this->item_id;
    }

    # 在庫数
    private int $quantity;
    public function get_quantity(): int
    {
        return $this->quantity;
    }

    # 最終更新時刻
    private string $last_update;
    public function get_last_update(): string
    {
        return $this->last_update;
    }

    # PDOオブジェクト
    private PDO $pdo;

    public function __construct()
    {
        try {
            $password = getenv("DB_PASSWORD");
            $db_name = getenv("DB_DATABASE");
            $dsn = "mysql:host=mariadb;dbname={$db_name}";
            $this->pdo = new PDO($dsn, "root", $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # トランザクション開始
    public function start_transaction()
    {
        try {
            $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
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
            throw new Exception($e->getMessage(), $e->getCode(), $e);
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
            throw new Exception($e->getMessage(), $e->getCode(), $e);
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

    public function create(int $item_id, int $quantity): Stock
    {
        try {
            $sql = "INSERT INTO stocks (item_id, quantity, last_update) VALUES (:item_id, :quantity, :last_update)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);
            $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindValue(":last_update", date("Y-m-d H:i:s"), PDO::PARAM_STR);

            $stmt->execute();

            require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
            $product = new Product();
            $product->get_from_item_id($item_id);

            $this->send_notification("在庫追加", "{$product->get_item_name()} の在庫が {$quantity} 個追加されました！");

            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function get_from_id(int $id): Stock
    {
        try {
            $sql = "SELECT * FROM stocks WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $stock = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stock) {
                $this->id = $stock["id"];
                $this->item_id = $stock["item_id"];
                $this->quantity = $stock["quantity"];
                $this->last_update = $stock["last_update"];
                return $this;
            } else {
                throw new Exception("ID: {$id} has not found.");
            }
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function gets_from_item_id(int $item_id): array
    {
        try {
            $sql = "SELECT id FROM stocks WHERE item_id = :item_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);

            $stmt->execute();

            $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stocks) {
                $stocks_array = [];
                foreach ($stocks as $stock) {
                    $stock_obj = new Stock();
                    $stocks_array[] = $stock_obj->get_from_id($stock["id"]);
                    $stock_obj->close();
                }
                return $stocks_array;
            } else {
                throw new Exception("指定した商品IDに対応する在庫は見つかりませんでした。", 0);
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