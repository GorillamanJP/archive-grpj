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
        require_once $_SERVER['DOCUMENT_ROOT'] . "/products/product.php";
        try {
            $total_amount = 0;
            $total_price = 0;
            $buy_items = [];
            for ($i = 0; $i < count($product_ids); $i++) {
                $product = new Product();
                $product = $product->get_from_item_id($product_ids[$i]);
                $total_amount += $quantities[$i];
                $total_price += $product->get_item()->get_price() * $quantities[$i];
                $buy_items[] = $product;
            }
            $this->accountant = $this->accountant->create($total_amount, $total_price);
            $accountant_id = $this->accountant->get_id();
            $qi = 0;
            foreach ($buy_items as $item) {
                $detail = new Detail();
                $this->details[] = $detail->create($accountant_id, $item->get_item()->get_id(), $quantities[$qi], $item->get_item()->get_price(), $item->get_item()->get_price() * $quantities[$qi]);
                $qi++;
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