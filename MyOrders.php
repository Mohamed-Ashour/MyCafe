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

       
       <!-- code javascript  -->
    </body>
</html>