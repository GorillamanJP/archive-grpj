<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class Transaction extends BaseClass
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

    public function create(int $accountant_id, int $total_price, int $received_price, int $returned_price): Transaction
    {
        try {
            $this->open();
            $sql = "INSERT INTO transactions (accountant_id, total_price, received_price, returned_price) VALUES (:accountant_id, :total_price, :received_price, :returned_price)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":accountant_id", $accountant_id, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);
            $stmt->bindValue(":received_price", $received_price, PDO::PARAM_INT);
            $stmt->bindValue(":returned_price", $returned_price, PDO::PARAM_INT);

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

    public function get_from_id(int $transaction_id): Transaction
    {
        try {
            $this->open();
            $sql = "SELECT * FROM transactions WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $transaction_id, PDO::PARAM_INT);

            $stmt->execute();

            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($transaction) {
                $this->id = $transaction["id"];
                $this->accountant_id = $transaction["accountant_id"];
                $this->total_price = $transaction["total_price"];
                $this->received_price = $transaction["received_price"];
                $this->returned_price = $transaction["returned_price"];
                return $this;
            } else {
                throw new Exception("指定した金銭収受データは見つかりませんでした。");
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_accountant_id(int $accountant_id): Transaction
    {
        try {
            $this->open();
            $sql = "SELECT id FROM transactions WHERE accountant_id = :accountant_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":accountant_id", $accountant_id, PDO::PARAM_INT);

            $stmt->execute();

            $transaction = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($transaction) {
                $id = $transaction["id"];
                return $this->get_from_id($id);
            } else {
                throw new Exception("指定した会計は見つかりませんでした。");
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
            $sql = "DELETE FROM transactions WHERE id = :id";

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
}