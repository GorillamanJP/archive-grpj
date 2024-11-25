<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClass.php";
class User extends BaseClass
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

    # パスワードのハッシュ
    private string $password_hash;
    public function get_password_hash(): string
    {
        return hash("SHA3-512", $this->password_hash);
    }

    # ソルト
    private string $salt;

    # ログイン検証
    public function verify(string $password): User
    {
        if (password_verify($password . $this->salt, $this->password_hash)) {
            return $this;
        } else {
            throw new Exception("ユーザー名またはパスワードが違います。");
        }
    }

    #ユーザー登録
    public function create(string $user_name, string $password): User
    {
        try {
            $this->open();
            # ソルト生成
            $salt = substr(uniqid(mt_rand(), true) . bin2hex(random_bytes(64)), 0, 128);
            #SQLクエリ用意
            $sql = "INSERT INTO users (user_name, password_hash, salt) VALUES (:user_name, :password_hash, :salt)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":user_name", $user_name, PDO::PARAM_STR);
            # パスワードのハッシュ化
            $stmt->bindValue(":password_hash", password_hash($password . $salt, PASSWORD_ARGON2ID), PDO::PARAM_STR);
            $stmt->bindValue(":salt", $salt, PDO::PARAM_STR);
            $stmt->execute();

            $id = $this->pdo->lastInsertId();

            $this->close();

            return $this->get_from_id($id);
        } catch (PDOException $pe) {
            $this->close();
            if ($pe->getCode() == 23000) {
                throw new Exception("指定したユーザー名は既に存在します。", 0, $pe);
            } else {
                throw new Exception("データベースエラーです。", 1, $pe);
            }
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    # ユーザー名から読み込み
    public function get_from_user_name(string $user_name): User
    {
        try {
            $this->open();
            $sql = "SELECT id FROM users WHERE user_name = :user_name";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":user_name", $user_name, PDO::PARAM_STR);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($user) {
                return $this->get_from_id($user["id"]);
            } else {
                throw new Exception("ユーザー名またはパスワードが違います。",0);
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    # ユーザーIDから読み込み
    public function get_from_id(int $id): User
    {
        try {
            $this->open();
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->close();
            if ($user) {
                $this->id = $user["id"];
                $this->user_name = $user["user_name"];
                $this->password_hash = $user["password_hash"];
                $this->salt = $user["salt"];
                return $this;
            } else {
                throw new Exception("指定したユーザーは見つかりませんでした。");
            }
        } catch (PDOException $pe) {
            $this->close();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (Throwable $th) {
            $this->close();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    # ユーザーすべてを取得
    public function get_all(): array|null
    {
        try {
            $this->open();
            $sql = "SELECT id FROM users ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->close();
            if ($users) {
                $users_array = [];
                foreach ($users as $user) {
                    $user_obj = new User();
                    $users_array[] = $user_obj->get_from_id($user["id"]);
                    $user_obj->close();
                }
                return $users_array;
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

    # ユーザー更新
    public function update(string $user_name, string $password): User
    {
        try {
            $this->open();
            # ソルト生成
            $salt = substr(uniqid(mt_rand(), true) . bin2hex(random_bytes(64)), 0, 128);

            $sql = "UPDATE users SET user_name = :user_name, password_hash = :password_hash, salt = :salt WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindValue(":user_name", $user_name, PDO::PARAM_STR);
            $stmt->bindValue(":password_hash", password_hash($password . $salt, PASSWORD_ARGON2ID), PDO::PARAM_STR);
            $stmt->bindValue(":salt", $salt, PDO::PARAM_STR);

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

    # ユーザー削除
    public function delete(): void
    {
        try {
            $this->open();
            $sql = "DELETE FROM users WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $this->id);

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