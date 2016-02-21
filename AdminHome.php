<?php
require 'database/model.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Admin Home</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" charset="utf-8">
	<link rel="icon" href="images/favicon.ico" type="image/gif" sizes="64x64">
	<script src="js/jquery-2.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php

	session_start();

	if(!isset($_SESSION['user']) && !isset($_COOKIE['user'])){
        header("Location: Login.php");
    }

    if(isset($_SESSION['user'])){
        if($_SESSION['user']!="admin") {
            echo "You have no access to this page!";
            exit;
        }
    }

    if(isset($_COOKIE['user'])){
        if($_COOKIE['user']!="admin"){
            echo "You have no access to this page!";
            exit;
        }
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
				<a class="navbar-brand" id="title" href="AdminHome.php" >MyCafe</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="Products.php">Products</a></li>
					<li><a href="Users.php">Users</a></li>
					<li><a href="ManualOrder.php">Manual Order</a></li>
					<li><a href="Checks.php">Checks</a></li>
		      	</ul>
		      	<ul class="nav navbar-nav navbar-right">
					<li><img src="images/admin.png" alt="admin" width="50px" height="50px" /></li>
		        	<li><a>Admin</a></li>
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
            <div class="row">
                <div class="page-header">
                    <h1>Orders</h1>
                </div>
            </div>
            <div class="row" id="orders">
                <?php
                /**
                 * get all orders sorted by date
                 */
                $obj_orders = ORM::getInstance();
                $obj_orders->setTable('orders');

                //select all products sorted by date and it`s status not equal to done
                $all_orders = $obj_orders->select_all_sorted("datetime",array('status'=>'done'));
                if ($all_orders->num_rows > 0) {
                    while ($order = $all_orders->fetch_assoc()) {
                        ?>
                        <div class="row" id="<?php echo $order['id']; ?>">
                            <table class="table table-bordered">
                                <tr class="active">
                                    <td>Order Date</td>
                                    <td>Name</td>
                                    <td>Room</td>
                                    <td>Ext.</td>
                                    <td>Action</td>
                                </tr>
                                <?php
                                $user_name = ORM::getInstance();
                                $user_name->setTable('users');
                                $user_info_array = $user_name->select(array('id' => $order['user_id']));
                                $user_info = $user_info_array->fetch_assoc();
                                ?>
                                <tr class="warning">
                                    <td><?php echo $order['datetime']; ?></td>
                                    <td><?php echo $user_info['name']; ?></td></td>
                                    <td><?php echo $order['room_id']; ?></td>
                                    <td><?php echo $user_info['ext']; ?></td>
                                    <td>                                   
                                        <input type="radio" name="status" value="out for delivery"  onclick="action('<?php echo $order['id'] . " delivery"; ?>')">Out for delivery<br>
                                        <input type="radio" name="status" value="done" onclick="action('<?php echo $order['id'] . " done"; ?>')">Done<br>
                                    </td>
                                </tr>
                            </table>
                            <div class="row alert alert-success" >
                                <?php
                                $order_product = ORM::getInstance();
                                $order_product->setTable('order_product');
                                $order_products = $order_product->select(array('order_id' => $order['id']));
                                $i = 1;
                                while ($current_product = $order_products->fetch_assoc()) {
                                    ?>

                                    <div class="col-md-<?php echo $i + 1; ?>">
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
                                            <img src="<?php echo "images/products/" . $product_info['pic']; ?>" class="img-responsive img-circle"  width="120px" height="120px">
                                        </div>
                                    </div>

                                    <?php
                                    $i = $i + 1;
                                }
                                ?>
                                <div class="row info badge pull-right">
                                    <p>Total: <?php echo $order['order_price']; ?></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="row">
                        <p> No Orders</p>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <script>
            //open web socket to listen at port 8000
            var exampleSocket = new WebSocket("ws://127.0.0.1:8000");

            exampleSocket.onmessage = function (event) {

                //parse json object that recived from socket
                var recived_msg = JSON.parse(event.data);

                //alert(recived_msg.action);
                switch (recived_msg.action) {

                    //in case of cancel remove the order from home page   
                    case "cancel":

                        var order_id = recived_msg.order_id;
                        document.getElementById(order_id).remove();
                        break;

                        //in case of confirm append as first child to orders list
                    case "confirm":
                        //alert(recived_msg.user_name);

                        //get the parent of all orders to append on it
                        var elem_orders_parent = document.getElementById("orders");

                        var elem_order = document.createElement("div");
                        elem_order.setAttribute("id", recived_msg.order_id);
                        elem_order.setAttribute("class", "row");

                        //row of header
                        var elem_order_table = document.createElement("table");
                        var tbody=document.createElement("tbody");
                        elem_order_table.setAttribute("class", "table table-bordered active");

                        var elem_table_header = document.createElement("tr");
                        elem_table_header.setAttribute("class", "active");

                        var td_date_header = document.createElement("td");
                        td_date_header.innerHTML = "Order Date";

                        var td_name_header = document.createElement("td");
                        td_name_header.innerHTML = "Name";

                        var td_room_header = document.createElement("td");
                        td_room_header.innerHTML = "Room";

                        var td_ext_header = document.createElement("td");
                        td_ext_header.innerHTML = "Ext.";

                        var td_action_header = document.createElement("td");
                        td_action_header.innerHTML = "Action";


                        elem_table_header.appendChild(td_date_header);
                        elem_table_header.appendChild(td_name_header);
                        elem_table_header.appendChild(td_room_header);
                        elem_table_header.appendChild(td_ext_header);
                        elem_table_header.appendChild(td_action_header);

                        //row of info
                        var elem_table_info = document.createElement("tr");
                        elem_table_info.setAttribute("class", "warning");

                        var td_date_info = document.createElement("td");
                        td_date_info.innerHTML = recived_msg.order_date;

                        var td_name_info = document.createElement("td");
                        td_name_info.innerHTML = recived_msg.user_name;

                        var td_room_info = document.createElement("td");
                        td_room_info.innerHTML = recived_msg.order_room;

                        var td_ext_info = document.createElement("td");
                        td_ext_info.innerHTML = recived_msg.user_ext;
//                
                        var td_action_info = document.createElement("td");

                        var deliver = document.createElement("input");
                        deliver.setAttribute("type", "radio");
                        deliver.setAttribute("name", "status");
                        deliver.setAttribute("value", "out of delivery");

                        var deliver_label = document.createElement("label");
                        deliver_label.innerHTML = "Out of delivery";

                        var done = document.createElement("input");
                        done.setAttribute("type", "radio");
                        done.setAttribute("name", "status");
                        done.setAttribute("value", "done");

                        var done_label = document.createElement("label");
                        done_label.innerHTML = "Done";

                        var br = document.createElement("br");

                        td_action_info.appendChild(deliver);
                        td_action_info.appendChild(deliver_label);
                        td_action_info.appendChild(br);
                        td_action_info.appendChild(done);
                        td_action_info.appendChild(done_label);
                        //td_action_info.appendChild(document.createElement("label").innerHTml="Done");
                        //td_action_info.appendChild(document.createElement("br"));

                        elem_table_header.appendChild(td_date_header);
                        elem_table_header.appendChild(td_name_header);
                        elem_table_header.appendChild(td_room_header);
                        elem_table_header.appendChild(td_ext_header);
                        elem_table_header.appendChild(td_action_header);

//               
                        elem_table_info.appendChild(td_date_info);
                        elem_table_info.appendChild(td_name_info);
                        elem_table_info.appendChild(td_room_info);
                        elem_table_info.appendChild(td_ext_info);
                        elem_table_info.appendChild(td_action_info);
//               

                        tbody.appendChild(elem_table_header);
                        tbody.appendChild(elem_table_info);
                        
                        elem_order_table.appendChild(tbody);
                        elem_order_table.appendChild(tbody);


                        //append div of products
                        var elem_div_products = document.createElement("div");
                        elem_div_products.setAttribute("class", "row alert alert-success");


                        for (var i = 0; i < recived_msg.products_count - 1; i++) {


                            var product_name = recived_msg.products[i].product_name;
                            var product_pic_path = recived_msg.products[i].product_pic;
                            var product_amount = recived_msg.products[i].product_amount;
                            var product_totalPrice = recived_msg.products[i].product_price;

                            var j = i + 2;
                            var product_colum = document.createElement("div");
                            product_colum.setAttribute("class", "col-md-3" );


                            var product_name_row = document.createElement("div");
                            product_name_row.setAttribute("class", "row");

                            var product_name_label = document.createElement("label");
                            product_name_label.innerHTML = "Name: " + product_name;


                            var product_amount_row = document.createElement("div");
                            product_amount_row.setAttribute("class", "row");

                            var product_amount_label = document.createElement("label");
                            product_amount_label.innerHTML = "Amount: " + product_amount;



                            var product_price_row = document.createElement("div");
                            product_price_row.setAttribute("class", "row");

                            var product_price_label = document.createElement("label");
                            product_price_label.innerHTML = "Price: " + product_totalPrice;


                            var product_pic_row = document.createElement("div");
                            product_pic_row.setAttribute("class", "row");

                            var product_pic = document.createElement("img");
                            product_pic.setAttribute("src", "images/products/" + product_pic_path);
                            product_pic.setAttribute("class", "img-responsive img-circle");
                            product_pic.setAttribute("width", "120px");
                            product_pic.setAttribute("height", "120px");


                            product_name_row.appendChild(product_name_label);
                            product_amount_row.appendChild(product_amount_label);
                            product_price_row.appendChild(product_price_label);
                            product_pic_row.appendChild(product_pic);

                            product_colum.appendChild(product_name_row);
                            product_colum.appendChild(product_amount_row);
                            product_colum.appendChild(product_price_row);
                            product_colum.appendChild(product_pic_row);

                            elem_div_products.appendChild(product_colum);


                        }


                        var elem_total_price = document.createElement("div");
                        elem_total_price.setAttribute("class", "row info badge pull-right");
                        elem_total_price.setAttribute("height", "50px");
                        elem_total_price.innerHTML = "Total: " + recived_msg.order_price;

                        elem_order.appendChild(elem_order_table);
                        elem_order.appendChild(elem_total_price);
                        elem_order.appendChild(elem_div_products);

                        elem_orders_parent.insertBefore(elem_order, elem_orders_parent.firstChild);
                        break;

                }

            };

            /**
             * function that take the action of radio button where out of delivery or done
             */
            function action(request) {
                //get order_id that have the action 
                var order_id = request.split(" ")[0];

                var status = "";
                //set status variable according to the action of click
                switch (request.split(" ")[1]) {
                    case "delivery":
                        status = "out for delivery";
                        break;
                    case "done":
                        status = "done";
                        var elem_of_order_to_remove=document.getElementById(order_id);
                        elem_of_order_to_remove.remove();
                        break;
                }

                //open xmlhttp request that render to order_status to change it`s status
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "order_status.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                xmlhttp.send("order_id=" + order_id + "&status=" + status);

                //on change check even the request send or not and get the values of response

                xmlhttp.onreadystatechange = function () {

                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {


                        
                        var msg = {
                            action: "status",
                            status_text:status,
                            order_id: order_id,
                        };

                        //send msg as jason object
                        exampleSocket.send(JSON.stringify(msg));
                    }
                };


            }
        </script>
</body>
</html>
