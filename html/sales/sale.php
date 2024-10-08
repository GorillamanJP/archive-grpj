<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/accountants/accountant.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/details/detail.php";
class Sale
{
    private Accountant $accountant;
    public function get_accountant(): Accountant
    {
        return $this->accountant;
    }

    private array $details;
    public function get_details(): array
    {
        return $this->details;
    }

    public function __construct()
    {
        $this->accountant = new Accountant();
        $this->details = [];
    }

    public function create(array $product_ids, array $quantities): Sale
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/stocks/stock.php";
        try {
            $total_amount = 0;
            $total_price = 0;
            $buy_items = [];
            for ($i = 0; $i < count($product_ids); $i++) {
                $item = new Item();
                $item = $item->get_from_id($product_ids[$i]);
                $total_amount += $quantities[$i];
                $total_price += $item->get_price() * $quantities[$i];
                $buy_items[] = $item;
            }
            $this->accountant = $this->accountant->create($total_amount, $total_price);
            $accountant_id = $this->accountant->get_id();
            for ($i = 0; $i < count($buy_items); $i++) {
                $detail = new Detail();
                $this->details[] = $detail->create($accountant_id, $buy_items[$i]->get_id(), $quantities[$i], $buy_items[$i]->get_price(), $buy_items[$i]->get_price() * $quantities[$i]);
                var_dump($detail);
                $stock = new Stock();
                $stock = $stock->get_from_item_id($buy_items[$i]->get_id());
                $stock->start_transaction();
                $now_quantity = $stock->get_quantity();
                $stock = $stock->update($now_quantity - $quantities[$i]);
                $stock->commit();
            }
            return $this;
        } catch (Exception $e) {
            throw new Exception(previous: $e);
        }
    }

    public function get_from_accountant_id(int $accountant_id): Sale
    {
        try {
            $this->accountant = $this->accountant->get_from_id($accountant_id);
            $detail = new Detail();
            $this->details = $detail->gets_from_accountant_id($accountant_id);
            return $this;
        } catch (Exception $e) {
            throw new Exception(previous: $e);
        }
    }

    public function get_all(): array|null
    {
        try {
            $accountants = $this->accountant->get_all();
            if (is_null($accountants)) {
                return null;
            }
            $sales_array = [];
            foreach ($accountants as $accountant) {
                $sale_obj = new Sale();
                $sales_array[] = $sale_obj->get_from_accountant_id($accountant->get_id());
            }
            return $sales_array;
        } catch (Exception $e) {
            throw new Exception(previous: $e);
        }
    }
}