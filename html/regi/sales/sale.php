<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/accountants/accountant.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/details/detail.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/transactions/transaction.php";
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

    private Transaction $transaction;
    public function get_transaction(): Transaction
    {
        return $this->transaction;
    }

    public function __construct()
    {
        $this->accountant = new Accountant();
        $this->details = [];
        $this->transaction = new Transaction();
    }

    public function create(array $product_ids, array $product_prices, array $quantities, array $subtotals, int $total_amount, int $total_price, int $received_price, int $returned_price): Sale
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/items/item.php";
        require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/stocks/stock.php";
        try {
            $this->accountant = $this->accountant->create($total_amount, $total_price);
            $accountant_id = $this->accountant->get_id();
            $detail = new Detail();
            $stock = new Stock();
            $stock->start_transaction();

            for($i = 0; $i < count($product_ids); $i++){
                // 配列バラしゾーン
                $id = $product_ids[$i];
                $price = $product_prices[$i];
                $quantity = $quantities[$i];
                $subtotal = $subtotals[$i];

                // 在庫チェック
                $stock = $stock->get_from_item_id($id);
                $quantity_left = $stock->get_quantity();
                if($quantity_left - $quantity < 0){
                    throw new Exception("在庫が不足しています。");
                }

                $this->details[] = $detail->create($accountant_id, $id, $quantity, $price, $subtotal);

                $stock->update($quantity_left - $quantity);
            }

            $stock->commit();
            $this->transaction = $this->transaction->create($accountant_id, $total_price, $received_price, $returned_price);
            return $this;
        } catch (\Throwable $e) {
            $stock->rollback();
            $this->accountant->delete();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function get_from_accountant_id(int $accountant_id): Sale
    {
        try {
            $this->accountant = $this->accountant->get_from_id($accountant_id);
            $detail = new Detail();
            $this->details = $detail->gets_from_accountant_id($accountant_id);
            $this->transaction = $this->transaction->get_from_accountant_id($accountant_id);
            return $this;
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
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
        } catch (\Throwable $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }
    }
}