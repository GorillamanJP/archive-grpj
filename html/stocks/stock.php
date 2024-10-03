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
            throw new Exception($e->getMessage());
        }
    }

    # トランザクション開始
    public function start_transaction()
    {
        try {
            $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());
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
            throw new Exception($e->getMessage());
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
            throw new Exception($e->getMessage());
        }
    }

    public function create(int $item_id, int $quantity): Stock
    {
        try {
            $sql = "INSERT INTO stocks (item_id, quantity) VALUES (:item_id, :quantity)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);
            $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);

            $stmt->execute();

            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
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
                return $this;
            } else {
                throw new Exception("ID: {$id} has not found.");
            }
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function get_from_item_id(int $item_id): Stock
    {
        try {
            $sql = "SELECT id FROM stocks WHERE item_id = :item_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);

            $stmt->execute();

            $id = $stmt->fetch(PDO::FETCH_ASSOC)["id"];

            return $this->get_from_id($id);
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function get_all(): array
    {
        try {
            $sql = "SELECT id FROM stocks ORDER BY id ASC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $stocks = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($stocks) {
                $stocks_array = [];
                foreach ($stocks as $stock) {
                    $stock_obj = new Stock();
                    $stocks_array[] = $stock_obj->get_from_id($stock["id"]);
                }
                return $stocks_array;
            } else {
                throw new Exception();
            }
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function update(int $quantity): Stock
    {
        try {
            $sql = "UPDATE stocks SET quantity = :quantity WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();

            return $this->get_from_id($this->id);
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(): void
    {
        try {
            $sql = "DELETE FROM stocks WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();
        } catch (Throwable $t) {
            $this->rollback();
            throw $t;
        }
    }
}