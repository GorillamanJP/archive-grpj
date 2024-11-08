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

    # 通知を送る
    private function send_notification(string $title, string $message)
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/notifications/notification.php";
        $notification = new Notification();
        $notification->create($title, $message);
    }

    public function create(int $total_amount, int $total_price, string $accountant_user_name): Accountant
    {
        try {
            $sql = "INSERT INTO accountants (date, total_amount, total_price, accountant_user_name) VALUES (:date, :total_amount, :total_price, :accountant_user_name)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":date", date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindValue(":total_amount", $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(":total_price", $total_price, PDO::PARAM_INT);
            $stmt->bindValue(":accountant_user_name", $accountant_user_name, PDO::PARAM_STR);

            $stmt->execute();

            $id = $this->pdo->lastInsertId();
            $this->send_notification("会計", "{$id} 番の会計が処理されました！");
            return $this->get_from_id($id);
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
                $this->accountant_user_name = $accountant["accountant_user_name"];
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
                    $accountant_obj->close();
                }
                return $accountants_array;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function gets_range(int $offset, int $limit): array|null
    {
        try {
            $sql = "SELECT id FROM accountants ORDER BY id DESC LIMIT :limit OFFSET :offset";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindValue(":offset", $offset, PDO::PARAM_INT);

            $stmt->execute();

            $accountants = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($accountants) {
                $accountants_array = [];
                foreach ($accountants as $accountant) {
                    $accountant_obj = new Accountant();
                    $accountants_array[] = $accountant_obj->get_from_id($accountant["id"]);
                    $accountant_obj->close();
                }
                return $accountants_array;
            } else {
                return null;
            }
        } catch (PDOException $pe) {
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
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