<!DOCTYPE html>
<html>
<head>
	<title>Manual Order</title>
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
					<li class="active"><a href="ManualOrder.php">Manual Order<span class="sr-only">(current)</span></a></li>
					<li><a href="Checks.php">Checks</a></li>
		      	</ul>
		      	<ul class="nav navbar-nav navbar-right">
					<li><img src="images/admin.png" alt="admin" width="50px" height="50px" /></li>
		        	<li><a>Admin</a></li>
				</ul>
		    </div>
		</div>
	</nav>

    <div class="container">
        <div class="col-lg-4 orders">
            <form class="form-horizontal" action="" method="post">
                <label class="control-label">Notes</label>
				<textarea name="notes"  class="form-control" rows="3"></textarea>
                <br>
				<label class="control-label">Room</label>
				<select name="room"  class="form-control room">
                    <option disabled selected hidden>Choose your room</option>
				</select>
                <hr>
                <button type="submit" value="Submit" class="btn btn-info orderSubmit">Confirm</button>
            </form>
        </div>
    </div>


</body>
</html>
