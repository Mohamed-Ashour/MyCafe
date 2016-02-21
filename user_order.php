<?php

require 'database/model.php';


/*
 * this file used to insert order into database
 */

//taking the order_price
//default status=processing
$order_price = $_POST['order_price'];
$order_room=$_POST['room'];

//get user_id from session 
session_start(); 
if (isset($_COOKIE['user'])) {
        $user_id = $_COOKIE['user_id'];
		$user_name = $_COOKIE['user'];
		$user_pic = $_COOKIE['user_pic'];
		
	}

	elseif ( isset($_SESSION['user']) ) {
        $user_id = $_SESSION['user_id'];

		$user_name = $_SESSION['user'];
		$user_pic = $_SESSION['user_pic'];
	}

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


/**
 * select all user information
 */
$obj_user = ORM::getInstance();
$obj_user->setTable('users');

$user_info=$obj_user->select(array("id" =>$user_id));
$user=$user_info->fetch_assoc();


/**
 * select all order_products info
 */
$order_product = ORM::getInstance();
$order_product->setTable('order_product');

$order_info=$order_product->select(array("order_id" =>$order_id));
$products_count=$order_info->num_rows;


$string_info=$last_order['id'].";".$last_order['datetime'].";".$user['name'].";".$user['ext'].";".$last_order['room_id'].";";
//$i=0;
while($order=$order_info->fetch_assoc()){
    
    
    $order_product_info_obj = ORM::getInstance();
    $order_product_info_obj->setTable('products');

    $order_product_info=$order_product_info_obj->select(array("id" =>$order['product_id']));
    
    $product=$order_product_info->fetch_assoc();

    $string_info.=$product['name']."/".$product['pic']."/".$order['amount']."/".$order['total_price']."%";
   

}
$string_info.=";".$last_order['order_price'];
echo $string_info;

