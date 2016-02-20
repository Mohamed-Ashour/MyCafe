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
					<li><img src="images/user.png" alt="user" width="50px" height="50px" /></li>
		        	<li><a>name</a></li>
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


            /**
             * function to get the value of date to when key up
             * @returns {Element.value}
             */
            function get_date_to() {
                return document.getElementById("date_to").value;
            }

            /**
             * function to get the value of date from when key up
             * @returns {Element.value}
             */
            function get_date_from() {
                return document.getElementById("date_from").value;
            }

            //get the total price for default date
            var date_to = get_date_to();
            var date_from = get_date_from();
            set_total_price(date_to, date_from, "<?php echo $_SESSION['user_name']; ?>");


            /**
             * if there is the value for date to and date from select orders
             */

            function select_orders() {


                var date_to = get_date_to();
                var date_from = get_date_from();
                //get the user name
                var user_name = document.getElementById("user_name").value;
                console.log(user_name);

                //get orders when the user select dates
                if (date_to !== "" && date_from !== "") {



                    //open xmlhttp request that render to user_get_order and send date to & date from
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("POST", "user_get_orders.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("dateTo=" + date_to + " 23:59:59" + "&dateFrom=" + date_from + " 00:00:00");

                    //on change check even the request send or not and get the values of response

                    xmlhttp.onreadystatechange = function () {

                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                            //get information of response of orders

                            var get_order_info = xmlhttp.responseText.split(",");

                            //select the table of old date and remove it

                            var elem_table = document.getElementById("table");
                            if (elem_table !== null) {
                                elem_table.remove();
                            }
                            var elem_products = document.getElementById("products");
                            if (elem_products !== null) {
                                elem_products.remove();
                            }

                            //get row of table to append on it
                            var table_row = document.getElementById("table_row");

                            //create new table withe id table
                            var elem_table_new = document.createElement("table");
                            elem_table_new.setAttribute("id", "table");
                            elem_table_new.setAttribute("class", "table table-bordered");

                            var table_header = document.createElement("tr");
                            elem_table_new.appendChild(table_header);

                            table_row.appendChild(elem_table_new);

                            //select table by its id to add the information on it
                            var elem_table_exist = document.getElementById("table");

                            console.log(get_order_info);
                            console.log(get_order_info.length);

                            for (var i = 0; i < get_order_info.length - 1; i++) {

                                var order = get_order_info[i];
                                console.log(order);

                                //set information in table of each order in rows

                                var tr = document.createElement("tr");
                                tr.setAttribute("class", "active");


                                //get array of colums in each row

                                var order_data = order.split(";");

                                //set tr by id equal to order id
                                tr.setAttribute("id", order_data[0]);

                                var td_date = document.createElement("td");
                                td_date.setAttribute("class", "col-md-3");
                                td_date.innerHTML = order_data[1];

                                //create button to show order by id "order_id btn"
                                var get_product_btn = document.createElement("button");
                                get_product_btn.setAttribute("id", order_data[0] + " btn");
                                get_product_btn.setAttribute("class", "btn btn-success pull-right");
                                get_product_btn.innerHTML = "+";
                                get_product_btn.setAttribute("onclick", "show_order('" + order_data[0] + " btn" + "')");


                                var td_status = document.createElement("td");
                                td_status.setAttribute("class", "col-md-3");
                                td_status.innerHTML = order_data[2];



                                var td_totalprice = document.createElement("td");
                                td_totalprice.innerHTML = order_data[3];
                                td_status.setAttribute("class", "col-md-3");

                                //if the statues is processing add button cancel
                                if (td_status.innerHTML === "processing") {


                                    var td_cancel = document.createElement("td");
                                    var cancel_btn = document.createElement("button");
                                    cancel_btn.innerHTML = "Cancel";
                                    cancel_btn.setAttribute("class", "col-md-5 btn btn-danger pull-right ");

                                    //get id of the order to send it to the function onclick
                                    var order_id = tr.getAttribute("id");
                                    cancel_btn.setAttribute("onclick", "cancel(" + order_id + ")");

                                }

                                td_date.appendChild(get_product_btn);
                                tr.appendChild(td_date);


                                tr.appendChild(td_status);


                                tr.appendChild(td_totalprice);


                                if (td_status.innerHTML === "processing") {

                                    td_cancel.appendChild(cancel_btn);

                                    tr.appendChild(td_cancel);

                                }

                                elem_table_exist.appendChild(tr);

                            }
                        }

                    };

                    set_total_price(date_to, date_from, user_name);
                }

            }

            //open the service socket at port 8000
            var exampleSocket = new WebSocket("ws://127.0.0.1:8000");


            /**
             * Cancel function that is calling when click on cancel action
             * will send to server to cancel the order at the admin
             */
            function cancel(order_id) {
                console.log(order_id);

                //first remove the order from database
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "delete_order.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("order_id=" + order_id);

                //on change check even the request send or not and get the values of response

                xmlhttp.onreadystatechange = function () {

                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {
                        console.log(xmlhttp.responseText);

                        //send on socket the action first then send order_id
                        //to remove the order at the admin

                        var msg = {
                            action: "cancel",
                            order_id: order_id,
                        };

                        //send msg as jason object
                        exampleSocket.send(JSON.stringify(msg));

                    }
                };
                //remove the element from table
                document.getElementById(order_id).remove();
                //calling set total price
                var date_to = get_date_to();
                var date_from = get_date_from();
                //get the user name
                var user_name = document.getElementById("user_name").value;

                set_total_price(date_to, date_from, user_name);
            }


            /**
             * function show orders used to show all the products of the order
             */
            function show_order(bttn_id) {

                //get the id of order from bttn id
                var order_id = bttn_id.toString().split(" ")[0];

                var bttn = document.getElementById(bttn_id);
                if (bttn.innerHTML === "+") {
                    bttn.innerHTML = "-";

                    //select the contatiner to append div on it
                    var elem_product_row = document.getElementById("products_row");
                    // create the div with id products to append on it the products of the order
                    var elem_products = document.createElement("div");
                    elem_products.setAttribute("id", "products");
                    elem_products.setAttribute("class", "row");

                    //create new row with id equal to "order_id product"
                    var elem_order_products = document.createElement("div");
                    elem_order_products.setAttribute("id", order_id + " product");
                    elem_order_products.setAttribute("class", "row alert alert-success");

                    //open xmlhttp request that render to user_get_order_products and send order_id to get all products
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("POST", "user_get_order_products.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("order_id=" + order_id);

                    //on change check even the request send or not


                    xmlhttp.onreadystatechange = function () {

                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                            console.log(xmlhttp.responseText);


                            //get information of response of order products with comma separated

                            var get_order_info = xmlhttp.responseText.split(",");


                            for (var i = 0; i < get_order_info.length - 1; i++) {

                                var order = get_order_info[i];

                                //get information about products by semicolum separated
                                var order_products = order.split(";");

                                var product_name = order_products[0];
                                var product_pic_path = order_products[1];
                                var product_amount = order_products[2];
                                var product_totalPrice = order_products[3];

                                var j = i + 2;
                                var product_colum = document.createElement("div");
                                product_colum.setAttribute("class", "col-md-" + j);


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
                                product_pic.setAttribute("src", "../images/products/" + product_pic_path);
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

                                elem_order_products.appendChild(product_colum);


                            }


                        }
                    };



                    elem_products.appendChild(elem_order_products);
                    elem_product_row.appendChild(elem_products);


                } else {
                    bttn.innerHTML = "+";

                    //get the order products dev to remove

                    var elem_order_products_remove = document.getElementById(order_id + " product");
                    elem_order_products_remove.remove();

                }

            }

            /**
             * this function used to setthe total price
             * @param {type} date_to
             * @param {type} date_from
             * @param {type} user_name
             * @returns {undefined}
             */
            function set_total_price(date_to, date_from, user_name) {

                //open xmlhttp request that render to admin_get_check and send date to & date from & user_name
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "admin_get_check_user.php", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.send("dateTo=" + date_to + " 23:59:59" + "&dateFrom=" + date_from + " 00:00:00" + "&user_name=" + user_name);

                console.log("dateTo=" + date_to + " 23:59:59" + "&dateFrom=" + date_from + " 00:00:00" + "&user_name=" + user_name);
                //on change check even the request send or not and get the values of response

                xmlhttp.onreadystatechange = function () {

                    if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                        //get information of response of orders
                        console.log(xmlhttp.responseText);

                        var user_info = xmlhttp.responseText.split(";");

                        var total_price = document.getElementById("total");
                        if (user_info[1] !== "") {
                            total_price.innerHTML = "Total: " + user_info[1];
                        } else {
                            total_price.innerHTML = "Total: " + "0";
                        }
                    }
                };
            }

            /**
             * this function to listen to recive status
             * @param {type} event
             * @returns {undefined}
             */

            exampleSocket.onmessage = function (event) {

                //parse json object that recived from socket
                var recived_msg = JSON.parse(event.data);

                //alert(recived_msg.action);
                switch (recived_msg.action) {

                    case "status":

                        var order_id = recived_msg.order_id;
                        var status = recived_msg.status_text;


                        //select order of tables and change it`s statues
                        var elem_order = document.getElementById(order_id);
                        var elem_order_childs = elem_order.childNodes;


                        //set the status in the child of status only  and remove the cancel button

                        if (elem_order_childs[1].innerHTML === "processing" || elem_order_childs[1].innerHTML === "out for delivery" || 			elem_order_childs[1].innerHTML === "done") {

                            if (elem_order_childs[1].innerHTML === "processing") {
                                elem_order_childs[3].childNodes[0].remove();
                                elem_order_childs[3].innerHTML = " ";
                            }
                            elem_order_childs[1].innerHTML = status;



                        } else {
                            elem_order_childs[3].innerHTML = status;

                            if (elem_order_childs[3].innerHTML === "processing") {
                                elem_order_childs[7].childNodes[0].remove();
                                elem_order_childs[7].innerHTML = " ";
                            }
                        }



                        break;
                }
            };


        </script>

    </body>
</html>
