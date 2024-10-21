<?php
class Accountant
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
            throw new Exception($e->getMessage());
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

    public function create(int $total_amount, int $total_price): Accountant
    {
        try {
            $sql = "INSERT INTO accountants (date, total_amount, total_price) VALUES (:date, :total_amount, :total_price)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindValue(":total_amount", $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);

            $stmt->execute();

            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function get_from_id(int $id): Accountant
    {
        try {
            $sql = "SELECT * FROM accountants WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $accountant = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($accountant) {
                $this->id = $accountant["id"];
                $this->date = $accountant["date"];
                $this->total_amount = $accountant["total_amount"];
                $this->total_price = $accountant["total_price"];
                return $this;
            } else {
                throw new Exception("ID {$id} has not found.");
            }
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function get_all(): array|null
    {
        try {
            $sql = "SELECT id FROM accountants ORDER BY id DESC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $accountants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($accountants) {
                $accountants_array = [];
                foreach ($accountants as $accountant) {
                    $accountant_obj = new Accountant();
                    $accountants_array[] = $accountant_obj->get_from_id($accountant["id"]);
                }
                return $accountants_array;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            return null;
        }
    }
    public function delete(): void
    {
        try {
            $sql = "DELETE FROM accountants WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();
        } catch (Throwable $t) {
            $this->rollback();
            throw $t;
        }
    }
}