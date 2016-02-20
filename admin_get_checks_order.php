<?php

require 'database/model.php';

/**
 * select all orders according to dates
 */
$date_to=$_POST['dateTo'];
$date_from=$_POST['dateFrom'];

$user_id=$_POST['user_id'];
        
$obj_order = ORM::getInstance();
$obj_order->setTable('orders');


$result=$obj_order->select_date("datetime",$date_from,$date_to,array('user_id'=>$user_id));

$info="";
//get all information in string to gend back to javascript
while( $order=$result->fetch_assoc()){
    
 
$info.=$order['id'].";".$order['datetime'].";".$order['order_price'].",";
}
echo $info;



