<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/BaseClassGroup.php";

require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_orders/order_order.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/order_details/order_detail.php";
class Order extends BaseClassGroup
{
    private Order_Order $order_order;
    public function get_order_order(): Order_Order
    {
        return $this->order_order;
    }

    private array $order_details;
    public function get_order_details(): array
    {
        return $this->order_details;
    }

    public function __construct()
    {
        $this->order_order = new Order_Order();
        $this->order_details = [];
    }

    public function create(array $product_ids, array $product_names, array $product_prices, array $quantities, array $subtotals, int $total_amount, int $total_price): Order
    {
        require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/products/product.php";
        try {
            $this->order_order = $this->order_order->create($total_amount, $total_price);
            $order_id = $this->order_order->get_id();

            $order_detail = new Order_Detail();

            $product = new Product();

            for ($i = 0; $i < count($product_ids); $i++) {
                $id = $product_ids[$i];
                $name = $product_names[$i];
                $price = $product_prices[$i];
                $quantity = $quantities[$i];
                $subtotal = $subtotals[$i];

                if ($quantity > 10) {
                    throw new Exception("一つの商品につき10個まで注文ができます。それ以上お買い求めいただく場合は、店頭までお越しください。", 0);
                }

                $available_left = $product->get_from_item_id($id)->get_buy_available_count();

                if ($available_left - $quantity < 0) {
                    throw new Exception("注文可能な商品数が不足しています。", 0);
                }

                $this->order_details[] = $order_detail->create($order_id, $id, $name, $price, $quantity, $subtotal);
            }
            // $this->send_notification("注文", "新しい注文 {$order_id} 番があります！");
            return $this;
        } catch (Exception $e) {
            $this->order_order->delete();
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            $this->order_order->delete();
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_from_order_id(int $order_id): Order
    {
        try {
            $this->order_order = $this->order_order->get_from_id($order_id);
            $order_detail = new Order_Detail();
            $this->order_details = $order_detail->gets_from_order_id($order_id);
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
            $orders_orders = $this->order_order->get_all();
            if (is_null($orders_orders)) {
                return null;
            }
            $orders_array = [];
            foreach ($orders_orders as $orders_order) {
                $orders_obj = new Order();
                $orders_array[] = $orders_obj->get_from_order_id($orders_order->get_id());
            }
            return $orders_array;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function get_all_all(): array|null
    {
        try {
            $orders_orders = $this->order_order->get_all_all();
            if (is_null($orders_orders)) {
                return null;
            }
            $orders_array = [];
            foreach ($orders_orders as $orders_order) {
                $orders_obj = new Order();
                $orders_array[] = $orders_obj->get_from_order_id($orders_order->get_id());
            }
            return $orders_array;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }

    public function gets_range(int $offset, int $limit): array|null
    {
        try {
            $orders_orders = $this->order_order->gets_range($offset, $limit);
            if (is_null($orders_orders)) {
                return null;
            }
            $orders_array = [];
            foreach ($orders_orders as $orders_order) {
                $order_obj = new Order();
                $orders_array[] = $order_obj->get_from_order_id($orders_order->get_id());
            }
            return $orders_array;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        } catch (Throwable $th) {
            throw new Exception("予期しないエラーが発生しました。", -1, $th);
        }
    }
}