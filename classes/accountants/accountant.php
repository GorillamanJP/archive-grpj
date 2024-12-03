<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class Accountant extends BaseClass
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
    public function get_formatted_date(): string
    {
        return date_create($this->date)->format("Y/m/d H:i:s");
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
    private string $accountant_user_name;
    public function get_accountant_user_name(): string
    {
        return $this->accountant_user_name;
    }

    public function create(int $total_amount, int $total_price, string $accountant_user_name): Accountant
    {
        try {
            $dt = new DateTime();
            $now = $dt->format("Y-m-d H:i:s.u");

            $this->open();

            $sql = "INSERT INTO accountants (date, total_amount, total_price, accountant_user_name) VALUES (:date, :total_amount, :total_price, :accountant_user_name)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", $now, PDO::PARAM_STR);
            $stmt->bindValue(":total_amount", $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);
            $stmt->bindValue(":accountant_user_name", $accountant_user_name, PDO::PARAM_STR);

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

    public function get_from_id(int $id): Accountant
    {
        try {
            $this->open();

            $sql = "SELECT * FROM accountants WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $accountant = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($accountant) {
                $this->id = $accountant["id"];
                $this->date = $accountant["date"];
                $this->total_amount = $accountant["total_amount"];
                $this->total_price = $accountant["total_price"];
                $this->accountant_user_name = $accountant["accountant_user_name"];
                return $this;
            } else {
                throw new Exception("ID {$id} has not found.");
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_all(): array|null
    {
        try {
            $this->open();

            $sql = "SELECT id FROM accountants ORDER BY id DESC";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $accountants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
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
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_range(int $offset, int $limit): array|null
    {
        try {
            $this->open();

            $sql = "SELECT id FROM accountants ORDER BY id DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();

            $accountants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
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
            $sql = "DELETE FROM accountants WHERE id = :id";

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