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

    public function create(string $date, int $total_amount):Accountant{
        try {
            $sql = "INSERT INTO accountants (date, total_amount) VALUES (:date, :total_amount)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", $date, PDO::PARAM_STR);
            $stmt->bindValue(":total_amount", $total_amount, PDO::PARAM_INT);

            $stmt->execute();

            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function get_from_id(int $id):Accountant{
        try {
            $sql = "SELECT * FROM accountants WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id",$id, PDO::PARAM_INT);
            $stmt->execute();

            $accountant = $stmt->fetch(PDO::FETCH_ASSOC);
            if($accountant){
                $this->id = $accountant["id"];
                $this->date = $accountant["date"];
                $this->total_amount = $accountant["total_amount"];
                return $this;
            } else {
                throw new Exception("ID {$id} has not found.");
            }
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage());
        }
    }

    public function get_all():array|null{
        try {
            $sql = "SELECT id FROM accountants ORDER BY id ASC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $accountants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if($accountants){
                $accountants_array = [];
                foreach($accountants as $accountant){
                    $accountant_obj = new Accountant();
                    $accountants_array[] = $accountant_obj->get_from_id($accountant["id"]);
                }
                return $accountants_array;
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }
}