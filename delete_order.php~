<?php

require '../model/model.php';

$obj = ORM::getInstance();
$order_id = $_POST['order_id'];
//first delete from table orders

$obj->setTable('orders');
$no_row_afected = $obj->delete(array('id' => $order_id));
if ($no_row_afected > 0) {

    $obj->setTable('order_product');
    $no_product_afected = $obj->delete(array('order_id' => $order_id));
} 
echo $no_row_afected;
echo $no_product_afected;