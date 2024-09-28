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
    public function verify(string $password): bool
    {
        if (password_verify($password . $this->salt, $this->password_hash)) {
            return true;
        } else {
            return false;
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
            echo $e->getMessage();
        }
    }

    #ユーザー登録
    public function create(string $username, string $password): bool
    {
        try {
            $salt = substr(uniqid(mt_rand(), true).bin2hex(random_bytes(64)),0,128);
            $sql = "INSERT INTO register_user (username, password_hash, salt) VALUES (:username, :password_hash, :salt)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":username", $username, PDO::PARAM_STR);
            $stmt->bindValue(":password_hash", password_hash($password . $salt, PASSWORD_ARGON2ID), PDO::PARAM_STR);
            $stmt->bindValue(":salt", $salt, PDO::PARAM_STR);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    # ユーザー名から読み込み
    public function get_from_username(string $username): User|null
    {
        try {
            $sql = "SELECT * FROM register_user WHERE username = :username";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":username", $username, PDO::PARAM_STR);
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
}