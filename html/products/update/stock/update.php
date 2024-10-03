<?php
$id = htmlspecialchars($_POST["id"]);
$quantity = htmlspecialchars($_POST["quantity"]);

if (update($id, $quantity)) {
    echo "OK";
} else {
    echo "NG";
}

function update($id, $quantity): bool
{
    require_once $_SERVER['DOCUMENT_ROOT'] . "/stocks/stock.php";
    $stock = new Stock();
    $stock = $stock->get_from_id($id);
    $stock = $stock->update($quantity);
    if (!is_null($stock)) {
        return true;
    } else {
        return false;
    }
}