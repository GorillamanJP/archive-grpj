<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/items/item.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/stocks/stock.php";
class Product
{
    private Item $item;
    public function get_item_id(): int
    {
        return $this->item->get_id();
    }
    public function get_item_name(): string
    {
        return $this->item->get_item_name();
    }
    public function get_price(): int
    {
        return $this->item->get_price();
    }
    public function get_item_image(): string
    {
        return $this->item->get_item_image();
    }
    public function get_delete_flag(): bool
    {
        return $this->item->get_delete_flag();
    }

    private Stock $stock;
    public function get_stock(): Stock
    {
        return $this->stock;
    }
    public function get_now_stock(): int
    {
        $stocks = $this->stock->gets_from_item_id($this->get_item_id());
        $quantity = 0;
        foreach ($stocks as $stock) {
            $quantity += $stock->get_quantity();
        }
        $total_sales = 0;
        try {
            $sql = "SELECT item_id, SUM(quantity) AS total_sales FROM details WHERE item_id = :item_id GROUP BY item_id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(":item_id", $this->get_item_id());

            $stmt->execute();

            $total_sales_res = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($total_sales_res) {
                $total_sales = $total_sales_res["total_sales"];
            }
        } catch (PDOException $pe) {
            throw new Exception("データベースエラーです。", 1, $pe);
        } catch (\Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $pe);
        }
        return $quantity - $total_sales;
    }

    # PDOオブジェクト
    private PDO $pdo;

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
    public function __construct()
    {
        $this->item = new Item();
        $this->stock = new Stock();
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
    public function create(string $item_name, int $price, string $item_image, int $quantity): Product
    {
        try {
            $this->item = $this->item->create($item_name, $price, $item_image);
            $this->stock = $this->stock->create($this->item->get_id(), $quantity);
            return $this;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function get_from_item_id(int $item_id): Product
    {
        try {
            $this->item = $this->item->get_from_id($item_id);
            $this->stock = $this->stock->get_from_item_id($this->item->get_id());
            return $this;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function get_from_item_name(string $item_name): Product
    {
        try {
            $this->item = $this->item->get_from_item_name($item_name);
            $this->stock = $this->stock->get_from_item_id($this->item->get_id());
            return $this;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);

        }
    }
    public function get_from_stock_id(int $stock_id): Product
    {
        try {
            $this->stock = $this->stock->get_from_id($stock_id);
            $this->item = $this->item->get_from_id($this->stock->get_item_id());
            return $this;
        } catch (PDOException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function get_all(): array|null
    {
        try {
            $items = $this->item->get_all();
            if ($items) {
                $products_array = [];
                foreach ($items as $item) {
                    $product = new Product();
                    $products_array[] = $product->get_from_item_id($item->get_id());
                }
                return $products_array;
            } else {
                return null;
            }
        } catch (\Throwable $e) {
            return null;
        }
    }
    // public function update(string $item_name, int $price, string $item_image, int $quantity): Product
    // {
    //     try {
    //         $this->item = $this->item->update($item_name, $price, $item_image);
    //         $this->stock = $this->stock->update($quantity);
    //         return $this;
    //     } catch (\Throwable $e) {
    //         throw new Exception($e->getMessage(), $e->getCode(), $e);
    //     }
    // }
    public function delete(): void
    {
        try {
            $this->item->delete();
        } catch (Throwable $t) {
            throw $t;
        }
    }
}