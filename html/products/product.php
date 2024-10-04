<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/stocks/stock.php";
class Product
{
    private Item $item;
    public function get_item(): Item
    {
        return $this->item;
    }
    private Stock $stock;
    public function get_stock(): Stock
    {
        return $this->stock;
    }

    public function __construct()
    {
        $this->item = new Item();
        $this->stock = new Stock();
    }
    public function create(string $item_name, int $price, string $item_image, int $quantity): Product
    {
        try {
            $this->item = $this->item->create($item_name, $price, $item_image);
            $this->stock = $this->stock->create($this->item->get_id(), $quantity);
            return $this;
        } catch (Exception $e) {
            throw new Exception(previous:$e);
        }
    }
    public function get_from_item_id(int $item_id): Product
    {
        try {
            $this->item = $this->item->get_from_id($item_id);
            $this->stock = $this->stock->get_from_item_id($this->item->get_id());
            return $this;
        } catch (Exception $e) {
            throw new Exception(previous:$e);
        }
    }
    public function get_from_item_name(string $item_name): Product
    {
        try {
            $this->item = $this->item->get_from_item_name($item_name);
            $this->stock = $this->stock->get_from_item_id($this->item->get_id());
            return $this;
        } catch (Exception $e) {
            throw new Exception(previous:$e);

        }
    }
    public function get_from_stock_id(int $stock_id): Product
    {
        try {
            $this->stock = $this->stock->get_from_id($stock_id);
            $this->item = $this->item->get_from_id($this->stock->get_item_id());
            return $this;
        } catch (PDOException $e) {
            throw new Exception(previous:$e);
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
            return null;
        }
    }
    public function update(string $item_name, int $price, string $item_image, int $quantity): Product
    {
        try {
            $this->item = $this->item->update($item_name, $price, $item_image);
            $this->stock = $this->stock->update($quantity);
            return $this;
        } catch (Exception $e) {
            throw new Exception(previous:$e);
        }
    }
    public function delete(): void
    {
        try {
            $this->item->delete();
            $this->stock->delete();
        } catch (Throwable $t) {
            throw $t;
        }
    }
}