<?php
require 'database/model.php';
/**
 * update the statues of order acording to it`s id 
 */

$order_id=$_POST['order_id'];
$status=$_POST['status'];
$obj_order= ORM::getInstance();
$obj_order->setTable('orders');

$result=$obj_order->update(array('id'=>$order_id),array('status'=>$status));

echo $result;
