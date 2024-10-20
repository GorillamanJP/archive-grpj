<?php
class Transaction
{
    # 取引記録の内部ID
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    # 会計テーブルのID
    private int $accountant_id;
    public function get_accountant_id(): int
    {
        return $this->accountant_id;
    }

    # 会計テーブルの合計金額
    private int $total_price;
    public function get_total_price(): int
    {
        return $this->total_price;
    }

    # 受け取った金額
    private int $received_price;
    public function get_received_price(): int
    {
        return $this->received_price;
    }

    # お釣り
    private int $returned_price;
    public function get_returned_price(): int
    {
        return $this->returned_price;
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

    public function create(int $accountant_id, int $total_price, int $received_price, int $returned_price): Transaction
    {
        try {
            $sql = "INSERT INTO transactions (accountant_id, total_price, received_price, returned_price) VALUES (:accountant_id, :total_price, :received_price, :returned_price)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":accountant_id", $accountant_id, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);
            $stmt->bindValue(":received_price", $received_price, PDO::PARAM_INT);
            $stmt->bindValue(":returned_price", $returned_price, PDO::PARAM_INT);

            $stmt->execute();
            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function get_from_id(int $transaction_id): Transaction
    {
        try {
            $sql = "SELECT * FROM transactions WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $transaction_id, PDO::PARAM_INT);

            $stmt->execute();

            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($transaction) {
                $this->id = $transaction["id"];
                $this->accountant_id = $transaction["accountant_id"];
                $this->total_price = $transaction["total_price"];
                $this->received_price = $transaction["received_price"];
                $this->returned_price = $transaction["returned_price"];
                return $this;
            } else {
                throw new Exception("ID {$transaction_id} has not found.");
            }
        } catch (\Throwable $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function get_from_accountant_id(int $accountant_id): Transaction
    {
        try {
            $sql = "SELECT id FROM transaction WHERE accountant_id = :accountant_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":accountant_id", $accountant_id, PDO::PARAM_INT);

            $stmt->execute();

            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($transaction) {
                $id = $transaction["id"];
                return $this->get_from_id($id);
            } else {
                throw new Exception("Accountant ID {$accountant_id} has not found.");
            }
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function delete(): void
    {
        try {
            $sql = "DELETE FROM transactions WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();
        } catch (Throwable $t) {
            $this->rollback();
            throw $t;
        }
    }
}