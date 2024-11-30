<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClassGroup.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/temp_purchases/temp_purchase.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/temp_purchase_details/temp_purchase_detail.php";
class Purchases extends BaseClassGroup
{
    private Temp_Purchases $temp_purchase;
    public function get_temp_purchases(): Temp_Purchases
    {
        return $this->temp_purchase;
    }

    private array $temp_purchase_details;
    public function gets_temp_purchase_details(): array
    {
        return $this->temp_purchase_details;
    }

    public function __construct()
    {
        $this->temp_purchase = new Temp_Purchases();
        $this->temp_purchase_details = [];
    }

    public function create(array $product_ids, array $quantities): Purchases
    {
        try {
            $this->temp_purchase = $this->temp_purchase->create();
            $temp_purchase_id = $this->temp_purchase->get_id();

            $this->temp_purchase_details = [];
            for ($i = 0; $i < count($product_ids); $i++) {
                $temp_purchase_detail_obj = new Temp_Purchases_Detail();
                $this->temp_purchase_details[] = $temp_purchase_detail_obj->create($temp_purchase_id, $product_ids[$i], $quantities[$i]);
            }

            $this->delete_at_ttl_ended();

            $this->send_notification("会計", "誰かが会計/注文の確認画面を表示中です。内部ID: {$temp_purchase_id}");

            return $this->get_from_temp_purchases_id($temp_purchase_id);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。".$th->getMessage(), -1, $th);
        }
    }

    public function get_from_temp_purchases_id(int $temp_purchases_id): Purchases
    {
        try {
            $this->temp_purchase = $this->temp_purchase->get_from_id($temp_purchases_id);
            $details_obj = new Temp_Purchases_Detail();
            $this->temp_purchase_details = $details_obj->gets_from_temp_purchases_id($temp_purchases_id);

            $this->delete_at_ttl_ended();

            return $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function delete(): void
    {
        try {
            $id = $this->temp_purchase->get_id();
            $this->temp_purchase->delete();
            $this->temp_purchase = new Temp_Purchases();
            $this->temp_purchase_details = [];

            $this->delete_at_ttl_ended();

            $this->send_notification("会計", "会計/注文の確認画面が閉じられました。内部ID: {$id}");
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function delete_at_ttl_ended(): void
    {
        try {
            $this->temp_purchase->delete_at_ttl_ended();
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}