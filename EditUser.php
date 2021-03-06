<!DOCTYPE html>
<html>
<head>
	<title>Edit User</title>
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
            header("Location: MyOrders.php");
        }
    }

    if(isset($_COOKIE['user'])){
        if($_COOKIE['user']!="admin"){
			header("Location: MyOrders.php");
        }
    }

	require_once('database/model.php');
	$mydb = new ORM();
	$mydb->setTable("users");

	if( isset($_POST["id"]) ) {
        $user = $mydb->select(array("id"=>$_POST["id"]));
        $row = $user->fetch_assoc();
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

	<div class="container" id="wrapper">
		<?php

		if( isset($_POST["name"]) ){
			$key=0;

			if (empty($_POST["name"])) {
				echo "<h4 class='alert-danger'> Name is required</h4>";
				$key=1;
			}

			$pattern='/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';

			if(!preg_match($pattern, $_POST["email"]))
			{
				echo "<h4 class='alert-danger'> Email is not valid</h4>";
				$key=1;
			}

			if (empty($_POST["password"])) {
				echo "<h4 class='alert-danger'> Password is required</h4>";
				$key=1;
			}

			elseif ($_POST["password"] != $_POST["password_con"]) {
				echo "<h4 class='alert-danger'> Password dose not match password confirmation</h4>";
				$key=1;
			}

			if (empty($_POST["room"])) {
				echo "<h4 class='alert-danger'> Room no is required</h4>";
				$key=1;
			}

			if (empty($_POST["ext"])) {
				echo "<h4 class='alert-danger'> User Ext. is required</h4>";
				$key=1;
			}

			if ($key==0) {
				// image handling
				if( !empty($_FILES['image']['name']) ){
					$image_path="images/users/".$_FILES['image']['name'];
					move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);

					$image = $_FILES['image']['name'];
				}
				else {
					$image = 'default.png';
				}
				// database update

				$user = array('name' => $_POST["name"] , 'email' => $_POST["email"] ,
				'password' => hash("md5", $_POST['password']) , 'room_no' => $_POST["room"] ,
				'ext' => $_POST["ext"] , 'is_admin' => 0 , 'pic' => $image );
				$where = array('id' => $_POST["id"]);
				$result = $mydb->update($where, $user);

				header("Location: Users.php");
			}
		}

		?>

        <h1>Edit User</h1>
		<form method="post" action="EditUser.php" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group panel">
				<label class="control-label">Name</label>
				<input required type="name" name="name" class="form-control" value="<?php echo $row['name']; ?>" >
                <label class="control-label">Email</label>
				<input required type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>">
                <label class="control-label">Password</label>
				<input required type="password" name="password" class="form-control">
                <label class="control-label">Confirm Password</label>
				<input required type="password" name="password_con" class="form-control">
                <label class="control-label">Room no</label>
				<input required type="number" name="room" class="form-control" value="<?php echo $row['room_no']; ?>">
                <label class="control-label">Ext.</label>
				<input required type="tel" name="ext" class="form-control" value="<?php echo $row['ext']; ?>">
                <label class="control-label">Profile picture</label>
				<input type="file" name="image" class="form-control">
				<input type="hidden" name="id" value="<?php echo $_POST["id"] ?>">
				<br><br>

				<button type="submit" value="Submit" class="btn btn-info">Submit</button>
	  			<button type="reset" value="Reset" class="btn btn-alert">Reset</button>
  			</div>
		</form>
	</div>
</body>
</html>
