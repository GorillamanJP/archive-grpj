<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClassGroup.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/items/item.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/stocks/stock.php";
class Product extends BaseClassGroup
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
    private array $stocks;
    public function get_now_stock(): int
    {
        $stocks = $this->stock->gets_from_item_id($this->get_item_id());
        $quantity = 0;
        foreach ($stocks as $stock) {
            $quantity += $stock->get_quantity();
        }
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/details/detail.php";
        $detail = new Detail();
        $total_sales = $detail->get_total_sold($this->get_item_id());
        return $quantity - $total_sales;
    }
    public function get_buy_available_count(): int
    {
        $now_stock = $this->get_now_stock();

        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_details/order_detail.php";
        $order_detail = new Order_Detail();

        return $now_stock - $order_detail->get_now_order_total($this->get_item_id());
    }
    public function __construct()
    {
        $this->item = new Item();
        $this->stock = new Stock();
        $this->stocks = [];
    }
    public function create(string $item_name, int $price, string $item_image, int $quantity): Product
    {
        try {
            $this->item = $this->item->create($item_name, $price, $item_image);
            $this->stock = $this->stock->create($this->item->get_id(), $quantity);
            return $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
    public function get_from_item_id(int $item_id): Product
    {
        try {
            $this->item = $this->item->get_from_id($item_id);
            $this->stocks = $this->stock->gets_from_item_id($this->item->get_id());
            return $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
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
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
    public function delete(): void
    {
        try {
            $this->item->delete();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_total_sold(): int
    {
        try {
            require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/details/detail.php";
            $detail = new Detail();
            return $detail->get_total_sold($this->get_item_id());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_total_revenue(): int
    {
        try {
            require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/details/detail.php";
            $detail = new Detail();
            return $detail->get_total_revenue($this->get_item_id());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}