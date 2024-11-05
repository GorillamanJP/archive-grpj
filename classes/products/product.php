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
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
    public function get_from_item_id(int $item_id): Product
    {
        try {
            $this->item = $this->item->get_from_id($item_id);
            $this->stocks = $this->stock->gets_from_item_id($this->item->get_id());
            return $this;
        } catch (\Throwable $e) {
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
    public function delete(): void
    {
        try {
            $this->item->delete();
        } catch (Throwable $t) {
            throw $t;
        }
    }
}