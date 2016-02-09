<!DOCTYPE html>
<html>
<head>
	<title>My Orders</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" charset="utf-8">
	<script src="js/jquery-2.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<?php
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

    <div class="container">

    </div>


</body>
</html>
