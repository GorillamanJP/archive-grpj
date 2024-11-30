<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class Temp_Purchases extends BaseClass
{
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    public function create(): Temp_Purchases
    {
        try {
            $this->open();

            $sql = "INSERT INTO temp_purchases (ttl) VALUES (:ttl)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":ttl", date("Y-m-d H:i:s", strtotime("+30 seconds", strtotime(date("Y-m-d H:i:s")))), PDO::PARAM_STR);

            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            return $this->get_from_id($id);
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。" . $th->getMessage(), -1, $th);
        }
    }

    public function get_from_id(int $id): Temp_Purchases
    {
        try {
            $this->open();
            $sql = "SELECT * FROM temp_purchases WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_INT);

            $stmt->execute();

            $temp_purchase = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($temp_purchase) {
                $this->id = $temp_purchase["id"];
                return $this;
            } else {
                throw new Exception("会計一時情報 {$id} は見つかりませんでした。");
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
            // ttl切れで消えてたら消去を諦める(もう消えてるので)
            try {
                $this->get_from_id($this->id);
            } catch (Throwable $th) {
                return;
            }

            $this->open();
            $sql = "DELETE FROM temp_purchases WHERE id = :id";

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

    public function delete_at_ttl_ended(): void
    {
        try {
            $this->open();
            $sql = "SELECT id FROM temp_purchases WHERE ttl <= :date";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", date("Y-m-d H:i:s"), PDO::PARAM_STR);

            $stmt->execute();

            $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->close();

            if ($purchases) {
                foreach ($purchases as $p) {
                    $p_obj = new Temp_Purchases();
                    $p_obj = $p_obj->get_from_id($p["id"]);
                    $p_obj->delete();
                }
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function extension(): Temp_Purchases
    {
        try {
            $this->open();
            $sql = "UPDATE temp_purchases SET ttl = :ttl WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":ttl", date("Y-m-d H:i:s", strtotime("+30 seconds", strtotime(date("Y-m-d H:i:s")))), PDO::PARAM_STR);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();

            $this->close();

            return $this->get_from_id($this->id);
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}