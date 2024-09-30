<?php
class Item{
    # 商品ID
    private int $id;
    public function get_id():int{
        return $this->id;
    }

    # 商品名
    private string $itemname;
    public function get_itemname():string{
        return $this->itemname;
    }

    # 価格
    private int $price;
    public function get_price():int{
        return $this->price;
    }

    # PDOオブジェクト
    private PDO $pdo;

    public function __construct(){
        try {
            $password = getenv("DB_PASSWORD");
            $db_name = getenv("DB_DATABASE");
            $dsn = "mysql:host=mariadb;dbname={$db_name}";
            $this->pdo = new PDO($dsn, "root", $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            //throw $th;
        }
    }

    # 商品登録
    public function create(string $itemname, int $price):Item|null{
        try {
            $sanitized_itemname = htmlspecialchars($itemname, encoding: "UTF-8");
            $sql = "INSERT INTO items (itemname, price) VALUES (:itemname, :price)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":itemname", $sanitized_itemname, PDO::PARAM_STR);
            $stmt->bindValue(":price", $price, PDO::PARAM_INT);
            $stmt->execute();

            $item = new Item();
            return $item->get_from_id($this->pdo->lastInsertId());
        } catch (PDOException $e) {
            return null;
        }
    }

    # 商品IDから検索
    public function get_from_id(int $id): Item|null{
        try{
            $sql = "SELECT * FROM items WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":id", $id, PDO::PARAM_INT);
            $stmt->execute();

            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            if($item){
                $this->id = $item["id"];
                $this->itemname = $item["itemname"];
                $this->price = $item["price"];
                return $this;
            } else {
                return null;
            }
        } catch (PDOException $e){
            return null;
        }
    }
}