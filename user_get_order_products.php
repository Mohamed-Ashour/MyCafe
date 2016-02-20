<?php

require '../model/model.php';

//get or=der_id from the request
$order_id= $_POST['order_id'];
//echo $order_id;

/**
 * select query from table order_product  to get all products
 */

$obj_order = ORM::getInstance();
$obj_order->setTable('order_product');

$result=$obj_order->select(array('order_id'=>$order_id));

$info="";
//get all information in string to gend back to javascript

//getting the relation between order and product from table order_product
//then get from this relation the product_id to get information about each prosuct in the order

while( $order=$result->fetch_assoc()){

$obj_product = ORM::getInstance();
$obj_product->setTable('products');

$result_pro=$obj_product->select(array('id'=>$order['product_id']));

$result_product=$result_pro->fetch_assoc();
 
$info.=$result_product['name'].";".$result_product['pic'].";".$order['amount'].";".$order['total_price'].",";

}
echo $info;


