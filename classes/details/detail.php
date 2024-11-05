<?php
class Detail
{
    private int $detail_id;
    public function get_detail_id(): int
    {
        return $this->detail_id;
    }
    private int $accountant_id;
    public function get_accountant_id(): int
    {
        return $this->accountant_id;
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
    private int $quantity;
    public function get_quantity(): int
    {
        return $this->quantity;
    }
    private int $item_price;
    public function get_item_price(): int
    {
        return $this->item_price;
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
            throw new Exception($e->getMessage(), $e->getCode(), $e);
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
            throw new Exception($e->getMessage());
        }
    }
    public function create(int $accountant_id, int $item_id, string $item_name, int $quantity, int $item_price, int $subtotal): Detail
    {
        try {
            $sql = "INSERT INTO details (accountant_id, item_id, item_name, quantity, item_price, subtotal) VALUES (:accountant_id, :item_id, :item_name, :quantity, :item_price, :subtotal)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":accountant_id", $accountant_id, PDO::PARAM_INT);
            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);
            $stmt->bindValue(":item_name", $item_name, PDO::PARAM_STR);
            $stmt->bindValue(":quantity", $quantity, PDO::PARAM_INT);
            $stmt->bindValue(":item_price", $item_price, PDO::PARAM_INT);
            $stmt->bindValue(":subtotal", $subtotal, PDO::PARAM_INT);

            $stmt->execute();

            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_id(int $detail_id): Detail
    {
        try {
            $sql = "SELECT * FROM details WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $detail_id, PDO::PARAM_INT);

            $stmt->execute();

            $detail = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($detail) {
                $this->detail_id = $detail["id"];
                $this->accountant_id = $detail["accountant_id"];
                $this->item_id = $detail["item_id"];
                $this->item_name = $detail["item_name"];
                $this->quantity = $detail["quantity"];
                $this->item_price = $detail["item_price"];
                $this->subtotal = $detail["subtotal"];
                return $this;
            } else {
                throw new Exception("ID {$detail_id} has not found.");
            }
        } catch (\Throwable $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_from_accountant_id(int $accountant_id): array
    {
        try {
            $sql = "SELECT id FROM details WHERE accountant_id = :accountant_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":accountant_id", $accountant_id, PDO::PARAM_INT);

            $stmt->execute();

            $details = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($details) {
                $details_array = [];
                foreach ($details as $detail) {
                    $detail_obj = new Detail();
                    $details_array[] = $detail_obj->get_from_id($detail["id"]);
                    $detail_obj->close();
                }
                return $details_array;
            } else {
                throw new Exception();
            }
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_total_sold(int $item_id): int
    {
        try {
            $sql = "
SELECT SUM(quantity) AS total_sold
FROM details
WHERE item_id = :item_id
GROUP BY item_id
ORDER BY total_sold DESC
";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);

            $stmt->execute();

            $total_sold = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($total_sold) {
                return (int) $total_sold["total_sold"];
            } else {
                throw new Exception("指定した商品は見つかりませんでした。", 0);
            }
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            $this->rollback();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_total_revenue(string $item_id): int
    {
        try {
            $sql = "
SELECT SUM(subtotal) AS total_revenue
FROM details
WHERE item_id = :item_id
GROUP BY item_id
ORDER BY total_revenue DESC;
";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_STR);

            $stmt->execute();

            $total_revenue = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($total_revenue) {
                return (int) $total_revenue["total_revenue"];
            } else {
                throw new Exception("指定した商品は見つかりませんでした。", 0);
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