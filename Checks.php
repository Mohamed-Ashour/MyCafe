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

    <div class="container">
		<br>
        <input type="date" class="form-control date" name="date" >
        <input type="date" class="form-control date" name="date" >
        <select class="form-control user" name="user">
            <option disabled selected hidden>Users</option>
        </select>
    </div>

</body>
</html>
