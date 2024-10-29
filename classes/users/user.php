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
    private string $user_name;
    public function get_user_name(): string
    {
        return $this->user_name;
    }

    # パスワードのSHA3-512ハッシュ
    private string $password_hash;

    # ソルト
    private string $salt;

    # ログイン検証
    public function verify(string $password): User
    {
        $sanitized_password = htmlspecialchars($password, encoding: "UTF-8");
        if (password_verify($sanitized_password . $this->salt, $this->password_hash)) {
            return $this;
        } else {
            throw new Exception("Invalid Username or Password.");
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
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    #ユーザー登録
    public function create(string $user_name, string $password): User
    {
        try {
            # 入力値サニタイズ
            $sanitized_user_name = htmlspecialchars($user_name, encoding: "UTF-8");
            $sanitized_password = htmlspecialchars($password, encoding: "UTF-8");
            # ソルト生成
            $salt = substr(uniqid(mt_rand(), true) . bin2hex(random_bytes(64)), 0, 128);
            #SQLクエリ用意
            $sql = "INSERT INTO users (user_name, password_hash, salt) VALUES (:user_name, :password_hash, :salt)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":user_name", $sanitized_user_name, PDO::PARAM_STR);
            # パスワードのハッシュ化
            $stmt->bindValue(":password_hash", password_hash($sanitized_password . $salt, PASSWORD_ARGON2ID), PDO::PARAM_STR);
            $stmt->bindValue(":salt", $salt, PDO::PARAM_STR);
            $stmt->execute();

            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # ユーザー名から読み込み
    public function get_from_user_name(string $user_name): User
    {
        $sanitized_user_name = htmlspecialchars($user_name, encoding: "UTF-8");
        try {
            $sql = "SELECT id FROM users WHERE user_name = :user_name";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":user_name", $sanitized_user_name, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return $this->get_from_id($user["id"]);
            } else {
                throw new Exception("Name {$user_name} has not found.");
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # ユーザーIDから読み込み
    public function get_from_id(int $id): User
    {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $this->id = $user["id"];
                $this->user_name = $user["user_name"];
                $this->password_hash = $user["password_hash"];
                $this->salt = $user["salt"];
                return $this;
            } else {
                throw new Exception("ID {$id} has not found.");
            }
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # ユーザーすべてを取得
    public function get_all(): array|null
    {
        try {
            $sql = "SELECT id FROM users ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($users) {
                $users_array = [];
                foreach ($users as $user) {
                    $user_obj = new User();
                    $users_array[] = $user_obj->get_from_id($user["id"]);
                }
                return $users_array;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            return null;
        }
    }

    # ユーザー更新
    public function update(string $user_name, string $password): User
    {
        try {
            # 入力値サニタイズ
            $sanitized_user_name = htmlspecialchars($user_name, encoding: "UTF-8");
            $sanitized_password = htmlspecialchars($password, encoding: "UTF-8");
            # ソルト生成
            $salt = substr(uniqid(mt_rand(), true) . bin2hex(random_bytes(64)), 0, 128);

            $sql = "UPDATE users SET user_name = :user_name, password_hash = :password_hash, salt = :salt WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindValue(":user_name", $sanitized_user_name, PDO::PARAM_STR);
            $stmt->bindValue(":password_hash", password_hash($sanitized_password . $salt, PASSWORD_ARGON2ID), PDO::PARAM_STR);
            $stmt->bindValue(":salt", $salt, PDO::PARAM_STR);

            $stmt->execute();

            return $this->get_from_id($this->id);
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # ユーザー削除
    public function delete(): void
    {
        try {
            $sql = "DELETE FROM users WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $this->id);

            $stmt->execute();
        } catch (Throwable $t) {
            throw $t;
        }
    }
}