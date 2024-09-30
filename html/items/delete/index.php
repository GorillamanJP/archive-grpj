<?php
$id = htmlspecialchars($_GET["id"]);

require_once $_SERVER['DOCUMENT_ROOT'] . "/items/item.php";

$item = new Item();
$item = $item->get_from_id($id);
$item->delete();

echo "OK";