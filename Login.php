<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css" charset="utf-8">
	<link rel="icon" href="images/favicon.ico" type="image/gif" sizes="32x32">
	<script src="js/jquery-2.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">
		<?php

		session_start();


		if($_POST) {
                require_once('database/model.php');
                $mydb = new ORM();
                $mydb->setTable("users");
                $users = $mydb->select_all();
	            if(!empty($_POST["keep_logged"])){
	                while($row = $users->fetch_assoc()) {
	                    if ($_POST['email'] == $row["email"] && hash("md5", $_POST['password']) == $row["password"] && $row["is_admin"] ) {
	                        setcookie('user', 'admin', time() + (86400 * 30));
	                        setcookie('user_id', $row["id"], time() + (86400 * 30));
	                        header("Location: AdminHome.php");
	                    } elseif ($_POST['email'] == $row["email"] && hash("md5", $_POST['password']) == $row["password"]) {
	                        setcookie('user', $row["name"], time() + (86400 * 30));
	                        setcookie('user_id', $row["id"], time() + (86400 * 30));
							setcookie('user_pic', $row["pic"], time() + (86400 * 30));
	                        header("Location: UserOrder.php");
	                    }
	                }
	            }
	            elseif(empty($_POST["keep_logged"])){
					while($row = $users->fetch_assoc()) {
	                    if ($_POST['email'] == $row["email"] && hash("md5", $_POST['password']) == $row["password"] && $row["is_admin"] ) {
	                        $_SESSION['user'] = "admin";
	                        $_SESSION['user_id'] = $row["id"];
	                        header("Location: AdminHome.php");
	                    } elseif ($_POST['email'] == $row["email"] && hash("md5", $_POST['password']) == $row["password"]) {
	                        $_SESSION['user']= $row['name'];
	                        $_SESSION['user_id'] = $row["id"];
							$_SESSION['user_pic'] = $row["pic"];
	                        header("Location: UserOrder.php");
	                    }
	                }
	            }
	        }
	    ?>

	    <div class="container div">

	        <form method="post" action="Login.php" class="form-horizontal loginForm">
				<h1 class="title">My Cafe</h1><br>
				<div class="form-group">
	                <label class="control-label">Email</label>
	                <input type="email" name="email" class="form-control " required>
	                <label class="control-label">Password</label>
	                <input type="password" name="password" class="form-control" required>
	                <div class="checkbox">
	                    <label><input type="checkbox" name="keep_logged" value="true">Remember me</label>
	                </div><br>
	                <button type="submit" value="login" class="btn btn-info">Login</button>
					<br><br><br>
					<a href="ForgotPassword.php">Forgot Password?</a>

	                <?php
	                    if($_POST){
	                        echo "<br><br><p class='alert alert-danger'>Wrong email or password</p>";
	                    }
	                ?>
	            </div>
	        </form>
	    </div>
	</div>
</body>
</html>
