<!DOCTYPE html>
<html>
<head>
	<title>Add User</title>
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
					<li><a href="ManualOrder.php">Manual Order</a></li>
					<li><a href="Checks.php">Checks</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li><img src="images/admin.png" alt="admin" width="50px" height="50px" /></li>
					<li><a>Admin</a></li>
				</ul>
			</div>
		</div>
	</nav>

	<div class="container" id="wrapper">
        <h1>Add User</h1>
		<form method="post" action="done.php" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group panel">
				<label class="control-label">Name</label>
				<input type="name" name="name" class="form-control ">
                <label class="control-label">Email</label>
				<input type="email" name="email" class="form-control" >
                <label class="control-label">Password</label>
				<input type="password" name="password" class="form-control">
                <label class="control-label">Confirm Password</label>
				<input type="password" name="password_con" class="form-control">
                <label class="control-label">Room no</label>
				<input type="number" name="room" class="form-control">
                <label class="control-label">Ext.</label>
				<input type="tel" name="ext" class="form-control">
                <label class="control-label">Profile picture</label>
				<input type="file" name="image" class="form-control">
				<br><br>

				<button type="submit" value="Submit" class="btn btn-info">Submit</button>
	  			<button type="reset" value="Reset" class="btn btn-alert">Reset</button>
  			</div>
		</form>
	</div>
</body>
</html>
