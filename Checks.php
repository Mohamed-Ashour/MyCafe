<?php

require 'database/model.php';

?>

<!DOCTYPE html>
<html>
<head>
	<title>Checks</title>
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
				<a class="navbar-brand" href="AdminHome.php">MyCafe</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="Products.php">Products</a></li>
					<li><a href="Users.php">Users</a></li>
					<li><a href="ManualOrder.php">Manual Order</a></li>
					<li class="active"><a href="Checks.php">Checks<span class="sr-only">(current)</span></a></li>
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

   
        <div class="container" id="contatiner">
            <div class="row">

                <div class="panel panel-heading">

                    <h1> Checks </h1>
                </div>
                <div class="col-md-5">
                    <label  class="col-sm-2 control-label"  >From:</label>
                    <input class="form-control" type = 'date' name="date_from" id="date_from" >
                </div>
                <div class="col-md-5">
                    <label  class="col-sm-2 control-label"  >To:</label>
                    <input class="form-control" type = 'date'  tname="date_to" id="date_to" >
                </div>

            </div>

            <div class="row panel-body">
                <div class="col-md-6">

                    <div class="panel panel-heading">

                        <h2> User: </h2>
                    </div>

                    <select class=" form-control" id="user_name">
                        <?php
                        /**
                         * get all users name
                         */
                        $obj_users = ORM::getInstance();
                        $obj_users->setTable('users');

                        $all_users = $obj_users->select(array('is_admin'=>0));

                        if ($all_users->num_rows > 0) {

                            while ($user = $all_users->fetch_assoc()) {
                                ?>
                                <option value="<?php echo $user['name']; ?>"> <?php echo $user['name']; ?> 
                                    <?php
                                }
                            } else {
                                ?>
                            <option>NO Users
                                <?php
                            }
                            ?>
                    </select>
                </div>

            </div>

            <div class="row">
                <div class="col-md-1 pull-right">
                    <button type="button" class="btn btn-success pull-right" onclick="select_user_check()">Check</button>
                </div>
            </div>
            <div class="row table-responsive">
                <table class="table table-bordered" >
                    <tr class="active">
                        <td class="col-md-6"> Name </td>
                        <td class="col-md-6"> Total Amount </td>

                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="row table-responsive"  id="table_row_user">
                    <table id="table_user">

                    </table>
                </div>


            </div>

            <div class="row table-responsive">
                <table class="table table-bordered"  >
                    <tr class="info">
                        <td class="col-md-4"> Order Date </td>
                        <td class="col-md-4"> Amount </td>

                    </tr>
                </table>
            </div>
            <div class="row">
                <div class="row table-responsive"  id="table_row_order">
                    <table id="table_order">

                    </table>
                </div>

            </div>
            <div class="row" id="products">

            </div>
        </div>
        <script>
            /**
             * function to get the value of date to 
             * @returns {Element.value}
             */
            function get_date_to() {
                return document.getElementById("date_to").value;
            }

            /**
             * function to get the value of date from 
             * @returns {Element.value}
             */
            function get_date_from() {
                return document.getElementById("date_from").value;
            }

            /**
             * function to select the value of user_name
             * @returns {Element.value}
             */
            function get_user_name() {
                return document.getElementById("user_name").value;
            }



            /**
             * function to get check of certain user
             */
            function select_user_check() {

                var date_to = get_date_to();
                var date_from = get_date_from();
                var user_name = get_user_name();

                console.log(date_to);
                console.log(date_from);

                //get the check when the admin select dates and user name
                if (date_to !== "" && date_from !== "" && user_name !== "") {

                    //open xmlhttp request that render to admin_get_check and send date to & date from & user_name
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("POST", "admin_get_check_user.php", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.send("dateTo=" + date_to+" 23:59:59"+ "&dateFrom=" + date_from+" 00:00:00"+ "&user_name=" + user_name);

                    //on change check even the request send or not and get the values of response

                    xmlhttp.onreadystatechange = function () {

                        if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {

                            //get information of response of orders
                            console.log(xmlhttp.responseText);

                            var user_info = xmlhttp.responseText.split(";");

                            //create table of user name and total amount

                            //select the tables of old date and remove it
                            var elem_table_user = document.getElementById("table_user");
                            if (elem_table_user !== null) {
                                elem_table_user.remove();
                            }

                            var elem_table_order = document.getElementById("table_order");
                            if (elem_table_order !== null) {
                                elem_table_order.remove();
                            }
                            var elem_products = document.getElementById("products");
                            if (elem_products !== null) {
                                elem_products.remove();
                            }
                            //get row of table to append on it
                            var table_row_user = document.getElementById("table_row_user");

                            //create new table withe id table
                            var elem_table_user_new = document.createElement("table");
                            elem_table_user_new.setAttribute("id", "table_user");
                            elem_table_user_new.setAttribute("class", "table table-bordered");

                            var table_user_header = document.createElement("tr");
                            elem_table_user_new.appendChild(table_user_header);

                            table_row_user.appendChild(elem_table_user_new);

                            //select table by its id to add the information on it

                            var elem_table_user_exist = document.getElementById("table_user");

                            //set information in table of user in row it`s id by id of user

                            var tr = document.createElement("tr");
                            tr.setAttribute("class", "active");
                            tr.setAttribute("id", user_info[0]);

                            var td_user_name = document.createElement("td");
                            td_user_name.setAttribute("class", "col-md-1");
                            td_user_name.innerHTML = user_name;


                            //create button to show orders by id "user_id btn"
                            var get_order_btn = document.createElement("button");
                            get_order_btn.setAttribute("id", user_info[0] + " btn");
                            get_order_btn.setAttribute("class", "btn btn-success pull-right");
                            get_order_btn.innerHTML = "+";
                            get_order_btn.setAttribute("onclick", "show_order('" + user_info[0] + " btn" + "')");



                            var td_user_amount = document.createElement("td");
                            td_user_amount.setAttribute("class", "col-md-1");
                            td_user_amount.innerHTML = user_info[1];

                            td_user_name.appendChild(get_order_btn);
                            tr.appendChild(td_user_name);
                            tr.appendChild(td_user_amount);

                            elem_table_user_exist.appendChild(tr);






                        }
                    };
                }
            }



            /**
             * function show orders used to show all the products of the order
             */
            function show_order(bttn_id) {

                //get the id of order from bttn id
                var user_id = bttn_id.toString().split(" ")[0];

                var bttn = document.getElementById(bttn_id);
                if (bttn.innerHTML === "+") {
                    bttn.innerHTML = "-";


                    var date_to = get_date_to();
                    var date_from = get_date_from();
                    var user_name = get_user_name();

                    console.log(date_to);
                    console.log(date_from);

                    //get the check when the admin select dates and user name
                    if (date_to !== "" && date_from !== "" && user_name !== "") {

                        //open xmlhttp request that render to admin_get_check and send date to & date from & user_name
                        var xmlhttp = new XMLHttpRequest();
                        xmlhttp.open("POST", "admin_get_checks_order.php", true);
                        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                        xmlhttp.send("dateTo=" + date_to+" 23:59:59"+ "&dateFrom=" + date_from+" 00:00:00" + "&user_id=" + user_id);

                        //on change check even the request send or not and get the values of response

                        xmlhttp.onreadystatechange = function () {

                            console.log(xmlhttp.readyState);
                            console.log(xmlhttp.status);
                            if (xmlhttp.readyState === 4 && xmlhttp.status === 200) {


                                //get information of response of orders
                                console.log(xmlhttp.responseText);

                                //get information of response of orders

                                var get_order_info = xmlhttp.responseText.split(",");

                                //create table of order by order date and amount
                                //get row of table to append on it
                                var table_row_order = document.getElementById("table_row_order");

                                //create new table withe id table
                                var elem_table_order_new = document.createElement("table");
                                elem_table_order_new.setAttribute("id", "table_order");
                                elem_table_order_new.setAttribute("class", "table table-bordered");

                                var table_order_header = document.createElement("tr");
                                elem_table_order_new.appendChild(table_order_header);

                                table_row_order.appendChild(elem_table_order_new);

                                //select table by its id to add the information on it

                                var elem_table_order_exist = document.getElementById("table_order");

                                for (var i = 0; i < get_order_info.length - 1; i++) {

                                    var order = get_order_info[i];

                                    var order_info = order.split(";");

                                    //set information in table of order in row it`s id by id of order

                                    var tr = document.createElement("tr");
                                    tr.setAttribute("class", "active");
                                    tr.setAttribute("id", order_info[0]);

                                    var td_order_date = document.createElement("td");
                                    td_order_date.setAttribute("class", "col-md-1");
                                    td_order_date.innerHTML = order_info[1];


                                    //create button to show products by id "order_id productbtn"
                                    var get_product_btn = document.createElement("button");
                                    get_product_btn.setAttribute("id", order_info[0] + " productbtn");
                                    get_product_btn.setAttribute("class", "btn btn-success pull-right");
                                    get_product_btn.innerHTML = "+";
                                    get_product_btn.setAttribute("onclick", "show_product('" + order_info[0] + " productbtn" + "')");



                                    var td_order_amount = document.createElement("td");
                                    td_order_amount.setAttribute("class", "col-md-1");
                                    td_order_amount.innerHTML = order_info[2];

                                    td_order_date.appendChild(get_product_btn);
                                    tr.appendChild(td_order_date);
                                    tr.appendChild(td_order_amount);

                                    elem_table_order_exist.appendChild(tr);

                                }
                            }
                        };
                    }

                }
                else {
                    bttn.innerHTML = "+";

                    //get the table of orders to remove
                    var elem_ordertable_remove = document.getElementById("table_order");
                    elem_ordertable_remove.remove();
                }
            }




            /**
             * function to show the products of each order
             */
            function show_product(bttn_id) {
                //get the id of order from bttn id
                var order_id = bttn_id.toString().split(" ")[0];
                console.log(order_id);
                var bttn = document.getElementById(bttn_id);
                if (bttn.innerHTML === "+") {
                    bttn.innerHTML = "-";

                    //select the contatiner to append div on it
                    var elem_container = document.getElementById("contatiner");
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

                        console.log(xmlhttp.readyState);
                        console.log(xmlhttp.status);
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

                                elem_order_products.appendChild(product_colum);


                            }


                        }
                    };



                    elem_products.appendChild(elem_order_products);
                    elem_container.appendChild(elem_products);


                } else {
                    bttn.innerHTML = "+";

                    //get the order products dev to remove

                    var elem_order_products_remove = document.getElementById(order_id + " product");
                    elem_order_products_remove.remove();

                }


            }




        </script>
    </body>
</html>
