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
            for ($i = 0; $i < count($quantities); $i++) {
                $total_amount += $quantities[$i];
            }
            $this->accountant = $this->accountant->create(date("Y-m-d H:i:s"), $total_amount);
            $accountant_id = $this->accountant->get_id();
            for ($i = 0; $i < count($product_ids); $i++) {
                $product = new Product();
                $product = $product->get_from_item_id($product_ids[$i]);
                $detail = new Detail();
                $this->details[] = $detail->create($accountant_id, $product->get_item()->get_id(), $quantities[$i], $product->get_item()->get_price());
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
}