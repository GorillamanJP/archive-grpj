<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../vendor/autoload.php";

use Minishlink\WebPush\VAPID;

class Order_Notify_Key extends BaseClass
{
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    private string $public_key;
    public function get_public_key(): string
    {
        return $this->public_key;
    }

    private string $private_key;
    public function get_private_key(): string
    {
        return $this->private_key;
    }

    public function create(): Order_Notify_Key
    {
        try {
            $this->open();

            $sql_check_exists = "SELECT COUNT(*) FROM notify_keys";

            $stmt_check_exists = $this->pdo->prepare($sql_check_exists);

            $stmt_check_exists->execute();

            $count = $stmt_check_exists->fetchColumn();

            if ($count <= 0) {

                $keys = VAPID::createVapidKeys();

                $sql_create_keys = "INSERT INTO notify_keys (id, public_key, private_key) VALUES (:id, :public_key, :private_key)";

                $stmt_create_keys = $this->pdo->prepare($sql_create_keys);

                $stmt_create_keys->bindValue(":id", 1, PDO::PARAM_INT);  // IDは1固定
                $stmt_create_keys->bindValue(":public_key", $keys["publicKey"], PDO::PARAM_STR);
                $stmt_create_keys->bindValue(":private_key", $keys["privateKey"], PDO::PARAM_STR);

                $stmt_create_keys->execute();

                $id = $this->pdo->lastInsertId();
            }

            $this->close();

            return $this->get();
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get(): Order_Notify_Key
    {
        try {
            $this->open();

            $sql = "SELECT * FROM notify_keys WHERE id = 1";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute();

            $keys = $stmt->fetch(PDO::FETCH_ASSOC);

            $this->close();

            if ($keys) {
                $this->id = $keys["id"];
                $this->public_key = $keys["public_key"];
                $this->private_key = $keys["private_key"];
                return $this;
            } else {
                throw new Exception("鍵が見つかりませんでした。", 0);
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