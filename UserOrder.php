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

	if(!isset($_SESSION['user'])&&!isset($_COOKIE['user'])){
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
				<a class="navbar-brand" id="title" href="UserOrder.php">MyCafe</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="MyOrders.php">My Orders</a></li>
		      	</ul>
		      	<ul class="nav navbar-nav navbar-right">
					<li><img src="images/user.png" alt="user" width="50px" height="50px" /></li>
		        	<li><a>name</a></li>
				</ul>
		    </div>
		</div>
	</nav>

    <div class="container">
            <div class=" col-md-3 panel panel-default"  id="create_order">
                <div class="panel panel-heading">

                    <h1>   Order  </h1>
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
                            <option>NO Rooms
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
			/////////////////////////////////////////////
			?>
    </div>


</body>
</html>
