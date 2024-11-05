<?php
class Item
{
    # 商品ID
    private int $id;
    public function get_id(): int
    {
        return $this->id;
    }

    # 商品名
    private string $item_name;
    public function get_item_name(): string
    {
        return htmlspecialchars_decode($this->item_name);
    }

    # 価格
    private int $price;
    public function get_price(): int
    {
        return $this->price;
    }

    # 商品画像
    private string $item_image;
    public function get_item_image(): string
    {
        return $this->item_image;
    }

    # 削除フラグ
    private bool $delete_flag;
    public function get_delete_flag(): bool
    {
        return $this->delete_flag;
    }
    private function resize_image(string $image): string
    {
        # MIMEタイプのチェック
        # 左半分はそもそも画像ではない場合falseになるので反転して検知
        # 右半分は画像タイプを識別してjpeg以外を検知
        if (!getimagesize($image) || getimagesize($image)["mime"] !== "image/jpeg") {
            throw new Exception("jpeg画像以外の画像がアップロードされました。");
        }
        # 画像データの加工
        # 画像サイズ
        list($width, $height) = getimagesize($image);
        # 縮小目標サイズ
        $new_width = 400;
        $new_height = intval(($height / $width) * $new_width);
        # 加工後画像サイズ
        $thumb = imagecreatetruecolor($new_width, $new_height);
        # 画像オブジェクト生成
        $source = imagecreatefromjpeg($image);

        # 画像リサイズ(というか縮小コピー)
        # $thumbに出力される
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        # 内部バッファを使う
        ob_start();
        # バッファ内にjpeg画像を吐き出す
        imagejpeg($thumb);
        # バッファに入ったjpegをstring化して保存
        $image_data = ob_get_contents();
        # バッファを開放
        ob_end_clean();
        # 画像データの加工　ここまで

        return $image_data;
    }

    # 最終更新時刻
    private string $last_update;
    public function get_last_update(): string
    {
        return $this->last_update;
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

    # トランザクション開始
    public function start_transaction()
    {
        try {
            $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
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
            throw new Exception($e->getMessage(), $e->getCode(), $e);
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
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    # 切断
    public function close()
    {
        unset($this->pdo);
    }

    # 商品登録
    public function create(string $item_name, int $price, string $item_image): Item
    {
        try {
            $sanitized_item_name = htmlspecialchars($item_name);

            $sql = "INSERT INTO items (item_name, price, item_image, last_update, delete_flag) VALUES (:item_name, :price, :item_image, :last_update, :delete_flag)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_name", $sanitized_item_name, PDO::PARAM_STR);
            $stmt->bindValue(":price", $price, PDO::PARAM_INT);
            $stmt->bindValue(":item_image", $this->resize_image($item_image), PDO::PARAM_LOB);
            $stmt->bindValue(":last_update", date("Y-m-d H:i:s"), PDO::PARAM_STR);
            $stmt->bindValue(":delete_flag", false, PDO::PARAM_BOOL);

            $stmt->execute();
            return $this->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # 商品IDから検索
    public function get_from_id(int $id): Item
    {
        try {
            $sql = "SELECT * FROM items WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $item = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($item) {
                $this->id = $item["id"];
                $this->item_name = $item["item_name"];
                $this->price = $item["price"];
                $this->item_image = base64_encode($item["item_image"]);
                $this->last_update = $item["last_update"];
                return $this;
            } else {
                throw new Exception("ID: {$id} has not found.");
            }
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # 商品すべてを取得
    public function get_all(): array|null
    {
        try {
            $sql = "SELECT id FROM items WHERE delete_flag = 0 ORDER BY id ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();

            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($items) {
                $items_array = [];
                foreach ($items as $item) {
                    $item_obj = new Item();
                    $items_array[] = $item_obj->get_from_id($item["id"]);
                    $item_obj->close();
                }
                return $items_array;
            } else {
                return null;
            }
        } catch (PDOException $e) {
            $this->rollback();
            return null;
        }
    }

    # 商品更新
    public function update(string $item_name, int $price, string $item_image): Item
    {
        try {
            $sanitized_item_name = htmlspecialchars($item_name);

            $sql = "UPDATE items SET item_name = :item_name, price = :price, item_image = :item_image, last_update = :last_update WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);
            $stmt->bindValue(":item_name", $sanitized_item_name, PDO::PARAM_STR);
            $stmt->bindValue(":price", $price, PDO::PARAM_INT);
            $stmt->bindValue(":item_image", $this->resize_image($item_image), PDO::PARAM_LOB);
            $stmt->bindValue(":last_update", date("Y-m-d H:i:s"), PDO::PARAM_STR);

            $stmt->execute();

            return $this->get_from_id($this->id);
        } catch (PDOException $e) {
            $this->rollback();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    # 商品削除
    public function delete(): void
    {
        try {
            $sql = "UPDATE items SET delete_flag = 1 WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $this->id, PDO::PARAM_INT);

            $stmt->execute();
        } catch (PDOException $pe) {
            $this->rollback();
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}