<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/regi/users/login_check_bool.php";
if (!login_check()) {
    http_response_code(403);
    exit();
}

session_start();

if (!isset($_POST["page_offset"]) || !isset($_POST["page_limit"])) {
    http_response_code(400);
    exit();
}

$last_update = date("Y/m/d H:i:s");
$_SESSION["regi"]["order"]["list"]["last_update"] = $last_update;

$data = "";
try {
    require_once $_SERVER['DOCUMENT_ROOT'] . "/../classes/orders/order.php";
    $order_obj = new Order();
    $orders = $order_obj->get_all();
    if (!is_null($orders)) {

        foreach ($orders as $order) {
            $details = "";
            foreach ($order->get_order_details() as $detail) {
                $details .= "
        <tr>
            <td>{$detail->get_item_name()}</td>
            <td>{$detail->get_quantity()}</td>
        </tr>";
            }
            $data .= "
<tr>
    <td>{$order->get_order_order()->get_id()}</td>
    <td>{$order->get_order_order()->get_date()}</td>
    <td>
        <table class='table table-bordered table-hover'>
            <thead>
                <tr>
                    <th>品名</th>
                    <th>数量</th>
                </tr>
            </thead>
            <tbody>
                {$details}
            <tbody>
        </table>
    </td>
    <td>
        <form action='../receive/' method='post'>
            <input type='hidden' name='order_id' id='order_id'
                value='{$order->get_order_order()->get_id()}'>
            <button type='submit'>受け取り</button>
        </form>
    </td>
</tr>";
        }
    } else {
        $data = "
        <tr>
            <td colspan='4'>
                <h3>受け取り待ちの注文はありません。</h3>
            </td>
        </tr>
        ";
    }
    echo json_encode([
        "time" => $last_update,
        "table" => $data,
    ]);
    exit();

} catch (Exception $e) {
    http_response_code(500);
    exit();
}