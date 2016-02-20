<?php
require 'database/model.php';
?>

<!DOCTYPE html>
<html>
<head>
	<title>My Cafe</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" charset="utf-8">
	<link rel="icon" href="images/favicon.ico" type="image/gif" sizes="32x32">
	<script src="js/jquery-2.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php

	session_start();

	if(!isset($_SESSION['user_id'])&&!isset($_COOKIE['user_id'])){
    	header("Location: Login.php");
	}

	elseif (isset($_COOKIE['user'])) {
		$user_name = $_COOKIE['user'];
		$user_pic = $_COOKIE['user'];
		
	}

	elseif ( isset($_SESSION['user']) ) {
		$user_name = $_SESSION['user'];
		$user_pic = $_SESSION['user_pic'];
	}

	?>

	<nav class="navbar navbar-inverse navbar-static-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" id="title" href="UserOrder.php">MyCafe</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="MyOrders.php">My Orders</a></li>
		      	</ul>
		      	<ul class="nav navbar-nav navbar-right">
					<li><img src="images/users/<?php echo $user_pic; ?>" alt="user" width="50px" height="50px" /></li>
		        	<li><a><?php echo $user_name; ?></a></li>
					<li>
						<form action="Logout.php" method="post" >
				            <input type="hidden" name="ss" value="any">
				            <button type="submit" class="add-user btn btn-default logout">Logout</button>
				        </form>
					</li>
				</ul>
		    </div>
		</div>
	</nav>

<div class="container">
            <div class=" col-md-3 panel panel-default"  id="create_order">
                <div class="panel panel-heading">

                </div>
                <form method="post"  >
                    <div class="row panel-body" id="create_order_products">

                    </div>
                    <label  class="col-sm-2 control-label">Notes:</label>
                    <textarea class="form-control" rows="3" id="notes"></textarea>
                    <label  class="col-sm-2 control-label">Rooms:</label>
                    <select name="room" id="room" class="form-control">
                        <?php
                        /**
                         * get all rooms numbers
                         */
                        $obj_rooms = ORM::getInstance();
                        $obj_rooms->setTable('rooms');

                        $all_rooms = $obj_rooms->select_all();

                        if ($all_rooms->num_rows > 0) {

                            while ($room = $all_rooms->fetch_assoc()) {
                                foreach ($room as $key => $value) {
                                    ?>
                                    <option value="<?php echo $room['number']; ?>"><?php echo $room['number']; ?>
                                        <?php
                                    }
                                }
                            } else {
                                ?>
                            <option><?php echo "NO Rooms" ?>
                                <?php
                            }
                            ?>
                    </select>
                    <input type='submit' name='confirm' value='Confirm' class="btn btn-primary" onclick="get_order()"><br/>
                    <label id="total_price" class=" control-label">Total Price: 0</label>
                </form>
            </div>
            <div class="col-md-9 panel panel-default">
                <div class="row panel-body">

                    <div class="panel panel-heading panel panel-warning">

                        <h1> Last order</h1>
                    </div>

                    <?php
                    /*
                     * Select all product of latest order for the user
                     */
                    $obj_order = ORM::getInstance();
                    $obj_order->setTable('orders');

                    //get user_id from session

                    $user_id = $_SESSION['user_id'];

                    // select the latest order  of this user
                    $last_order = $obj_order->select_last_row(array('user_id' => $user_id));

                    if ($last_order) {

                        $order_id = $last_order['id'];
                        /**
                         * get all products of the last order
                         */
                        $obj_order_product = ORM::getInstance();
                        $obj_order_product->setTable('order_product');

                        $order_products = $obj_order_product->select(array('order_id' => $order_id));
                        $i = 1;
                        while ($current_product = $order_products->fetch_assoc()) {
                            ?>
                            <div class="col-md-3">
                                <?php
                                ?>
                                <div class="row">
                                    <?php
                                    echo "Amount: " . $current_product['amount'];
                                    ?>
                                </div>
                                <div class="row">
                                    <?php
                                    echo " totalPrice: " . $current_product['total_price'];
                                    ?>
                                </div>
                                <?php
                                //get the name of product and all it`s info
                                $obj_product_info = ORM::getInstance();
                                $obj_product_info->setTable('products');

                                $product_info_array = $obj_product_info->select(array('id' => $current_product['product_id']));
                                $product_info = $product_info_array->fetch_assoc();
                                ?>
                                <div class="row">
                                    <?php
                                    echo " Name: " . $product_info['name'];
                                    ?>
                                </div>
                                <div class="row">
                                    <img src="<?php echo "images/products/" . $product_info['pic']; ?>" class="img-responsive img-circle"  width="80px" height="80px">
                                </div>
                            </div>

                            <?php
                            $i = $i + 1;
                        }
                    } else {
                        echo "NO Orders!!";
                    }
                    ?>
                </div>
                <div class="row">
                    <div class="row panel-body">

                        <div class="panel panel-heading panel panel-warning">

                            <h1>Menu</h1>
                        </div>
                        <div class="row">

                        <?php
                        /**
                         * Select all products
                         */
                        $obj_product = ORM::getInstance();
                        $obj_product->setTable('products');
                        $products = $obj_product->select(array("is_available" => 1));

                        //if there is more than one product while loop every time fetch row
                        if ($products->num_rows > 0) {
                            $j = 1;
                            while ($row = $products->fetch_assoc()) {
                                ?>
                                <div class="col-md-3">
                                    <img src="<?php echo "images/products/" . $row['pic']; ?>" width="100px" height="100px" class="img-responsive img-circle"
                                         onclick="add_product('<?php echo $row['name']; ?>',<?php echo $row['id']; ?>,<?php echo $row['price']; ?>)">
                                    <div class="row col-lg-offset-2 badge "> <?php echo $row['price']; ?> .LE</div>
                                </div>
                                <?php
                                $j = $j + 1;
                            }
                        } else {
                            echo "NO Products!!";
                        }
                        ?>
                    </div>
                        </div>
                </div>
            </div>

        </div>
        <script>

            //global array of products id 
            var products_id = [];
            /**
             * this function to append the product in order form 
             * @param {type} name
             * @param {type} id
             * @param {type} price
             * @returns {undefined}
             */
            function add_product(name, id, price) {
                //if condition to check id the product is ordered before in this
                //order to increase the amount or not
                //check if the id of the product in the global array or not
                if (products_id.indexOf(id) === -1) {
                    //if product isn`t orderd before push it`s id in the array of products
                    products_id.push(id);
                    //select the div of order to append on the products
                    var elem_order = document.getElementById("create_order_products");
                    //create div it`s id equal to the product id
                    var elem_product = document.createElement("div");
                    elem_product.setAttribute("id", id);
                    elem_product.setAttribute("class", "product");
                    //create label for product with it`s name
                    var elem_product_name = document.createElement("label");
                    elem_product_name.setAttribute("class", " control-label");
                    elem_product_name.innerHTML = "  Name: " + name;
                    //create input field for amount of product
                    var elem_product_amount = document.createElement("input");
                    elem_product_amount.setAttribute("class", "form-control");
                    elem_product_amount.setAttribute("type", "number");
                    elem_product_amount.setAttribute("name", "amount");
                    elem_product_amount.setAttribute("value", "1");
                    elem_product_amount.setAttribute("min", "1");
                    elem_product_amount.setAttribute("onclick", "add_amount(" + id + "," + price + ")");

                    //create label for product with it`s price
                    var elem_product_price = document.createElement("label");
                    elem_product_price.setAttribute("class", " control-label");
                    elem_product_price.innerHTML = "  Price: " + price;

                    //create button to cancel product
                    var cancel_btn = document.createElement("button");
                    cancel_btn.innerHTML = "x";
                    cancel_btn.setAttribute("class", "btn btn-danger pull-right ");


                    cancel_btn.setAttribute("onclick", "cancel(" + id + ")");

                    //appeand parameter
                    elem_product.appendChild(elem_product_name);
                    elem_product.appendChild(cancel_btn);
                    elem_product.appendChild(elem_product_amount);
                    elem_product.appendChild(elem_product_price);
                    elem_order.appendChild(elem_product);

                } else {
                    //get the div of product by it`s id number
                    var elem_exists_product = document.getElementById(id);
                    //get value of the product and increase it by one
                    var value = elem_exists_product.childNodes[2].value;
                    value = parseInt(value) + 1;
                    elem_exists_product.childNodes[2].setAttribute("value", value);
                    //increase the price by increase it`s amount
                    var new_price = price * value;
                    elem_exists_product.childNodes[3].innerHTML = "  Price: " + new_price;
                }
                //set the total price of the order in the label of total price
                //by select all products dev and get it`s price

                var total_price = 0;

                var products = document.getElementsByClassName("product");

                for (var i = 0; i < products.length; i++) {
                    total_price += parseInt(products[i].childNodes[3].innerHTML.split(" ")[3]);
                }

                var elem_order_price = document.getElementById("total_price");
                elem_order_price.innerHTML = "Total Price: " + total_price;



            }

            //open socket that listen to port 8080
            var exampleSocket = new WebSocket("ws://localhost:8000");

            /**
             * this function that get the order informations to insert in database
             * @returns {undefined}
             */

            function get_order() {


                //check if the form order has childs of products or not
                var elem_order = document.getElementById("create_order_products");

                if (elem_order.childElementCount > 0) {

                    //get value of all price of order
                    var elem_order_price = document.getElementById("total_price");
                    var orderPrice = elem_order_price.innerHTML.split(" ");

                    //get all order notes
                    var elem_order_notes = document.getElementById("notes").value;

                    //get room name from the select
                    var elem_order_room = document.getElementById("room");
                    var order_room = elem_order_room.options[elem_order_room.selectedIndex].text;

                    var product_info = "";

                    //forloop to get all product and send it in array to request
                    for (var i = 1; i <= elem_order.childElementCount; i++) {

                        var all_products = elem_order.childNodes;

                        //console.log(all_products[1]);
                        var product = all_products[i];

                        //alert(product.nodeType ? "true" : "false" );

                        var product_id = product.getAttribute("id");


                        var product_amount = product.childNodes[2].value;

                        var product_price = product.childNodes[3].innerHTML.split(" ")[3];


                        product_info += product_id + ":" + product_amount + ":" + product_price + ",";

                    }

                    //open xmlhttp request that render to user_order and send total order & products
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("POST", "user_order.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("order_price=" + orderPrice[2] + "&room=" + order_room + "&notes=" + elem_order_notes + "&array=" + product_info);

                    //on change check even the request send or not


                    xmlhttp.onreadystatechange = function () {

                        // alert(xmlhttp.readyState);
                        // alert(xmlhttp.status);
                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                            //alert(xmlhttp.responseText);

                            //alert(xmlhttp.responseText);
                            //alert(xmlhttp.responseText.split(";")[5]);

                            //prepare the information of order before send on socket
                            var order_info = xmlhttp.responseText.split(";");
                            var order_products_info = order_info[5].split("%");

                            var products = [];
                            for (var i = 0; i < order_products_info.length - 1; i++) {

                                var product_info = order_products_info[i].split("/");
                                var product = {
                                    product_name: product_info[0],
                                    product_pic: product_info[1],
                                    product_amount: product_info[2],
                                    product_price: product_info[3],
                                };

                                products.push(product);
                            }
                            var msg = {
                                action: "confirm",
                                order_id: order_info[0],
                                order_date: order_info[1],
                                user_name: order_info[2],
                                user_ext: order_info[3],
                                order_room: order_info[4],
                                order_price: order_info[6],
                                products_count: order_products_info.length,
                                products: products,
                            };

                            //alert(JSON.stringify(msg));
                            //send the resonse text via socket
                            exampleSocket.send(JSON.stringify(msg));
                            //alert("send");

                        }
                    };

                }


            }

            /**
             * this function used to increase the amount of product when changing it by hand
             * @param {type} id
             * @param {type} price
             * @returns {undefined}
             */

            function add_amount(id, price) {

                //get the div of product by it`s id number
                var elem_exists_product = document.getElementById(id);
                //get value of the product 
                var value = elem_exists_product.childNodes[2].value;
                value = parseInt(value);
                //if condition to make the value of product not decrease about 1
                if (value < 1) {
                    value = 1;
                    elem_exists_product.childNodes[2].setAttribute("value", value);
                }
                //increase the price by increase it`s amount
                var new_price = price * value;
                elem_exists_product.childNodes[3].innerHTML = "  Price: " + new_price;

                //set the total price of the order in the label of total price
                //by select all products dev and get it`s price

                var total_price = 0;

                var products = document.getElementsByClassName("product");

                for (var i = 0; i < products.length; i++) {
                    total_price += parseInt(products[i].childNodes[3].innerHTML.split(" ")[3]);
                }

                var elem_order_price = document.getElementById("total_price");
                elem_order_price.innerHTML = "Total Price: " + total_price;

            }

            /**
             * cancel function to cancel request of certain product
             */
            function cancel(id, price) {
                //get the div of product by it`s id number
                var elem_exists_product = document.getElementById(id);

                //remove the product request
                elem_exists_product.remove();

                //remove the id of element from the array
                var index = products_id.indexOf(id)
                if (index > -1) {
                    products_id.splice(index, 1);
                }

                //set the total price of the order in the label of total price
                //by select all products dev and get it`s price

                var total_price = 0;

                var products = document.getElementsByClassName("product");

                for (var i = 0; i < products.length; i++) {
                    total_price += parseInt(products[i].childNodes[3].innerHTML.split(" ")[3]);
                }

                var elem_order_price = document.getElementById("total_price");
                elem_order_price.innerHTML = "Total Price: " + total_price;


            }

        </script>
</body>
</html>
