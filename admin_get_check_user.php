<?php

require 'database/model.php';
$date_to= $_POST['dateTo'];
$date_from= $_POST['dateFrom'];
$user_name=$_POST['user_name'];

/**
 * select from table users the user_id by user name
 */

$obj_user= ORM::getInstance();
$obj_user->setTable('users');

$result=$obj_user->select(array('name'=>$user_name));

$user=$result->fetch_assoc();
$user_id=$user['id'];


/**
 * select the sumation of orders to specific user between 2 dates
 */

$obj_order= ORM::getInstance();
$obj_order->setTable('orders');

$result_order=$obj_order->select_sum("order_price",array('user_id'=>$user_id),"user_id","datetime",$date_from,$date_to);
$order=$result_order->fetch_assoc();

echo $user_id.";".$order['sum(order_price)'];