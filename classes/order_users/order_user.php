<?php
/*
メモ: 例外パターンの変更について
例外のうち、何が起こったか分かるもの(
    例:
    ・データベースにつながらなかった
    ・指定したIDのものが見つからなかった
    ・ユーザー名かパスワードが違う
    など
)は例外の番号を指定する。
0: 自作の例外(下記コードだとユーザー検証失敗の部分)
1: 使ったクラスの例外(PDOExceptionなど)
2: これ以降は使ったクラスが複数ある場合や例外のパターンがいくつか増えた場合に連番で増やす。細かいことは書き換えながら考える。
-1: 予期しない例外(ThrowableやExceptionなど大きなくくりで例外を捕まえた場合)
*/
class Order_User
{
    private string $id;
    public function get_id(): string
    {
        return $this->id;
    }

    private string $hash;
    public function verify(string $fingerprint): Order_User
    {
        $sanitized_fingerprint = htmlspecialchars($fingerprint);
        $hash = hash("SHA3-512", $this->id . $sanitized_fingerprint);
        if ($this->hash === $hash) {
            return $this;
        } else {
            throw new Exception("ユーザーの検証に失敗しました。", 0);
        }
    }

    private PDO $pdo;

    public function __construct()
    {
        try {
            $password = getenv("DB_PASSWORD");
            $db_name = getenv("DB_DATABASE");
            $dsn = "mysql:host=mariadb;dbname={$db_name}";
            $this->pdo = new PDO($dsn, "root", $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("データベースの接続に失敗しました。", 1, $e);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), -1, $th);
        }
    }

    public function create(string $fingerprint):Order_User
    {
        try {
            $id = bin2hex(random_bytes(127));

            // ID被りを探しておく
            while (true) {
                try {
                    $exists_user = $this->get_from_id($id);
                } catch (\Throwable $th) {
                    break;
                }
            }

            $sanitized_fingerprint = htmlspecialchars($fingerprint);
            $hash = hash("SHA3-512", $id . $sanitized_fingerprint);

            $sql = "INSERT INTO order_users (id, hash) VALUES (:id, :hash)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $id, PDO::PARAM_STR);
            $stmt->bindValue(":hash", $hash, PDO::PARAM_STR);

            $stmt->execute();

            return $this->get_from_id($id);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 1, $e);
        } catch (\Throwable $th) {
            throw new Exception($th->getMessage(), -1, $th);
        }
    }

    public function get_from_id(string $order_user_id): Order_User
    {
        try {
            $sql = "SELECT * FROM order_users WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":id", $order_user_id, PDO::PARAM_STR);

            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $this->id = $user["id"];
                $this->hash = $user["hash"];
                return $this;
            } else {
                throw new Exception("指定したユーザーは見つかりませんでした。", 0);
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), 1, $e);
        }
    }
}