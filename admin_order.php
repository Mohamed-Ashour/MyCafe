<?php

require 'database/model.php';
session_start();

/*
 * this file used to insert order into database
 */

//taking the order_price
//default status=processing
$order_price = $_POST['order_price'];
$order_room=$_POST['room'];
$order_user=$_POST['user'];

/**
 * select from table of users the user id by user name
 */

$obj_order_user = ORM::getInstance();
$obj_order_user->setTable('users');
$user=$obj_order_user->select(array('name'=>$order_user));

$user_info = $user->fetch_assoc();
$user_id=$user_info['id'];

$status = "processing";

/**
 * insert order into database
 */
$obj_order = ORM::getInstance();
$obj_order->setTable('orders');

$result = $obj_order->insert(array("user_id" => $user_id, "status" => $status, "order_price" => $order_price,"room_id"=>$order_room));

/**
 * select the last order to get order_id
 */
$obj_order_id = ORM::getInstance();
$obj_order_id->setTable('orders');

$last_order=$obj_order_id->select_last_row(array("user_id" =>$user_id));

$order_id = $last_order['id'];


/**
 *  insert into table order_product all products of the order
 */
$notes = $_POST['notes'];

$products_array = $_POST['array'];

//getting all products separated by comma
$products =explode(",", $products_array);

//getting information obout each product and insert it into order_product table
for ($i = 0; $i < count($products)- 1; $i++) {
    
     $product = explode(":", $products[$i]);
     
     $obj_order_product = ORM::getInstance();
     $obj_order_product->setTable('order_product');
     
     $product_id = $product[0];
     $product_amount = $product[1];
     $product_price = $product[2];
     
     $obj_order_product->insert(array("order_id"=>$order_id,"product_id"=>$product_id ,"amount"=>$product_amount,"total_price"=>$product_price,"notes"=>$notes));
    
}



