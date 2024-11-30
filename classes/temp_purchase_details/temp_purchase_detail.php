<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class Temp_Purchases_Detail extends BaseClass
{
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    private int $temp_purchases_id;
    public function get_temp_purchases_id(): int
    {
        return $this->temp_purchases_id;
    }

    private int $item_id;
    public function get_item_id(): int
    {
        return $this->item_id;
    }

    private int $quantity;
    public function get_quantity(): int
    {
        return $this->quantity;
    }

    public function create(int $temp_purchases_id, int $item_id, int $quantity): Temp_Purchases_Detail
    {
        try {
            $this->open();

            $sql = "INSERT INTO temp_purchase_details (temp_purchases_id, item_id, quantity) VALUES (:temp_purchases_id, :item_id, :quantity)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":temp_purchases_id", $temp_purchases_id, PDO::PARAM_INT);
            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);
            $stmt->bindValue(":quantity", $quantity, PDO::PARAM_STR);

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

    public function get_from_id(int $id): Temp_Purchases_Detail
    {
        try {
            $this->open();

            $sql = "SELECT * FROM temp_purchase_details WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $temp_purchase_detail = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($temp_purchase_detail) {
                $this->id = $temp_purchase_detail["id"];
                $this->temp_purchases_id = $temp_purchase_detail["temp_purchases_id"];
                $this->item_id = $temp_purchase_detail["item_id"];
                $this->quantity = $temp_purchase_detail["quantity"];
                return $this;
            } else {
                throw new Exception("会計一時情報詳細 {$id} は見つかりませんでした。");
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_from_temp_purchases_id(int $temp_purchases_id): array|null
    {
        try {
            $this->open();

            $sql = "SELECT id FROM temp_purchase_details WHERE temp_purchases_id = :temp_purchases_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":temp_purchases_id", $temp_purchases_id, PDO::PARAM_INT);

            $stmt->execute();

            $temp_purchase_details = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->close();

            if ($temp_purchase_details) {
                $details_array = [];
                foreach ($temp_purchase_details as $detail) {
                    $detail_obj = new Temp_Purchases_Detail();
                    $details_array[] = $detail_obj->get_from_id($detail["id"]);
                }
                return $details_array;
            } else {
                return null;
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_exists_temp_quantity_from_item_id(int $item_id): int
    {
        try {
            $this->open();

            $sql = "SELECT SUM(quantity) as exists_quantity FROM temp_purchase_details WHERE item_id = :item_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $item_id, PDO::PARAM_INT);

            $stmt->execute();

            $exists_quantity = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($exists_quantity) {
                return intval($exists_quantity["exists_quantity"]);
            } else {
                return 0;
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}