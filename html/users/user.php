<?php
class User
{
    # ユーザーID
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    # ユーザー名のハッシュ
    private string $username;
    public function get_username(): string
    {
        return $this->username;
    }

    # パスワードのSHA3-512ハッシュ
    private string $password_hash;

    # ソルト
    private string $salt;

    # ログイン検証
    public function verify(string $password): User|null
    {
        $sanitized_password = htmlspecialchars($password, encoding: "UTF-8");
        if (password_verify($sanitized_password . $this->salt, $this->password_hash)) {
            return $this;
        } else {
            return null;
        }
    }
    # PDOオブジェクト
    private PDO $pdo;

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
        }
    }

    #ユーザー登録
    public function create(string $username, string $password): User|null
    {
        try {
            # 入力値サニタイズ
            $sanitized_username = htmlspecialchars($username, encoding: "UTF-8");
            $sanitized_password = htmlspecialchars($password, encoding: "UTF-8");
            # ソルト生成
            $salt = substr(uniqid(mt_rand(), true) . bin2hex(random_bytes(64)), 0, 128);
            #SQLクエリ用意
            $sql = "INSERT INTO register_user (username, password_hash, salt) VALUES (:username, :password_hash, :salt)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":username", $sanitized_username, PDO::PARAM_STR);
            # パスワードのハッシュ化
            $stmt->bindValue(":password_hash", password_hash($sanitized_password . $salt, PASSWORD_ARGON2ID), PDO::PARAM_STR);
            $stmt->bindValue(":salt", $salt, PDO::PARAM_STR);
            $stmt->execute();
            
            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            return null;
        }
    }

    # ユーザー名から読み込み
    public function get_from_username(string $username): User|null
    {
        $sanitized_username = htmlspecialchars($username, encoding: "UTF-8");
        try {
            $sql = "SELECT * FROM register_user WHERE username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":username", $sanitized_username, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $this->id = $user["id"];
                $this->username = $user["username"];
                $this->password_hash = $user["password_hash"];
                $this->salt = $user["salt"];
                return $this;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    # ユーザーIDから読み込み
    public function get_from_id(int $id): User|null
    {
        try {
            $sql = "SELECT * FROM register_user WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $this->id = $user["id"];
                $this->username = $user["username"];
                $this->password_hash = $user["password_hash"];
                $this->salt = $user["salt"];
                return $this;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    # ユーザー更新
    public function update(string $username, string $password): User|null{
        try {
            # 入力値サニタイズ
            $sanitized_username = htmlspecialchars($username, encoding: "UTF-8");
            $sanitized_password = htmlspecialchars($password, encoding: "UTF-8");
            # ソルト生成
            $salt = substr(uniqid(mt_rand(), true) . bin2hex(random_bytes(64)), 0, 128);

            $sql = "UPDATE register_user SET username = :username, password_hash = :password_hash, salt = :salt";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":username", $sanitized_username, PDO::PARAM_STR);
            $stmt->bindValue(":password", password_hash($sanitized_password . $salt, PASSWORD_ARGON2ID), PDO::PARAM_STR);
            $stmt->bindValue(":salt", $salt, PDO::PARAM_STR);

            $stmt->execute();

            return $this->get_from_id($this->id);
        } catch (PDOException $e) {
            return null;
        }
    }

    # ユーザー先所
    public function delete():void{
        try {
            $sql = "DELETE FROM register_user WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $this->id);

            $stmt->execute();
        } catch (PDOException $e) {
        }
    }
}