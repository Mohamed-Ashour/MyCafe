<?php
require 'database/model.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>My Orders</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" charset="utf-8">
	<link rel="icon" href="images/favicon.ico" type="image/gif" sizes="32x32">
	<script src="js/jquery-2.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php
    //get User Id from login page when session start to use it in get orders
	session_start();

	if(!isset($_SESSION['user']) && !isset($_COOKIE['user'])){
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
				<a class="navbar-brand" href="UserOrder.php">MyCafe</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li class="active"><a href="MyOrders.php">My Orders<span class="sr-only">(current)</span></a></li>
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


        <div class="container" id="contatiner">
            <div class="row">

                <div class="panel panel-heading">

                    <h1> My orders </h1>
                </div>
                <div class="col-md-5">
                    <?php
                    $datefrom = date('Y-m-d') . " 00:00:00";
                    $dateto = date('Y-m-d') . " 23:59:59";
                    ?>
                    <label  class="col-sm-2 control-label"  >From:</label>
                    <input class="form-control" type = 'date' name="date_from" id="date_from" value="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="col-md-5">
                    <label  class="col-sm-2 control-label"  >To:</label>
                    <input class="form-control" type = 'date'  tname="date_to" id="date_to" value="<?php echo date('Y-m-d'); ?>" >
                </div>
                <div class="col-sm-3 pull-right">
                    <button type="button" class="btn btn-success pull-right" onclick="select_orders()">Get my orders</button>
                    <input type="hidden" id="user_name" value="<?php
                    //get user_id from session
                    echo $user_name = $_SESSION['user_name'];
                    ?>">
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table table-bordered" >
                        <tr class="active">
                            <td class="col-md-3"> Order Date</td>
                            <td class="col-md-3"> Status</td>
                            <td class="col-md-3"> Total price</td>
                            <td class="col-md-3 "> Action</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div   id="table_row">
                    <table   id="table">
                        <?php
                        //set default orders betwwen yesterday till current time
                        $obj_orders = ORM::getInstance();
                        $obj_orders->setTable('orders');

                        $all_orders = $obj_orders->select_date("datetime", $datefrom, $dateto, array("user_id" => $_SESSION['user_id']));
                        if ($all_orders->num_rows > 0) {
                            while ($order = $all_orders->fetch_assoc()) {
                                ?>
                                <tr class="row" id="<?php echo $order['id']; ?>">
                                    <td class="col-md-3"><?php echo $order['datetime']; ?>
                                        <button class=" btn btn-success" id="<?php echo $order['id'] . " btn"; ?>" onclick="show_order('<?php echo $order['id'] . " btn"; ?>')">+</button>
                                    </td>
                                    <td class="col-md-3"><?php echo $order['status']; ?></td></td>
                                    <td class="col-md-3"><?php echo $order['order_price']; ?></td>

                                    <?php
                                    if ($order['status'] == "processing") {
                                        ?>
                                        <td class="col-md-3"><button class=" col-md-5 btn btn-danger" id="<?php echo $order['id']; ?>" onclick="cancel('<?php echo $order['id']; ?>')">Cancel</button></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td class="col-md-3"></td>
                                    </tr>
                                    <?php
                                }
                            }
                        } else {
                            echo "No Recent orders!";
                        }
                        ?>

                    </table>
                </div>
            </div>
            <div class="row" id="products_row">
                <div class="row" id="products">

                </div>
            </div>
            <div class=" row panel ">
                <div class="  col-md-2 panel-title alert alert-success" id="total">
                    Total: 0
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

            //open socket that listen to posrt 8080
            var exampleSocket = new WebSocket("ws://127.0.0.1:8000");

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

                            // alert(JSON.stringify(msg));
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
