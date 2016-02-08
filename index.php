<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/jquery-2.2.0.min.js"></script>
	<style>
		.add-user, .mynav {
			float: right;
			margin-top: 20px;
		}
		.users-num {
			text-align: center;
		}
		form {
			width: 40%;
			margin: 0 auto;
		}
		.div {
			margin-top: 10%;
		}
		.title {
			text-align: center;
		}
	</style>
</head>
<body>
	<div class="container">
		<?php
	        session_start();
	        if($_POST){
	            $users = fopen("users", "r+");
	            $arr=file("users");
	            fclose($users);
	            if(!empty($_POST["keep_logged"])){
	                foreach($arr as $line) {
	                    list($id, $fname, $lname, $gender, $skills, $username, $email, $password, $adress, $dept, $image_path) = explode(";", $line);
	                    if ($_POST['username'] == $username && $_POST['password'] == $password && $id == 0) {
	                        setcookie('user', 'admin', time() + (86400 * 30));
	                        setcookie('user_name', $username, time() + (86400 * 30));
	                        header("Location: admin.php");
	                    } elseif ($_POST['username'] == $username && $_POST['password'] == $password) {
	                        setcookie('user', 'blogger', time() + (86400 * 30));
	                        setcookie('user_name', $username, time() + (86400 * 30));
	                        header("Location: blogger_page.php");
	                    }
	                }
	            }
	            elseif(empty($_POST["keep_logged"])){
	                foreach($arr as $line) {
	                    list($id, $fname, $lname, $gender, $skills, $username, $email, $password, $adress, $dept, $image_path) = explode(";", $line);
	                    if ($_POST['username'] == $username && $_POST['password'] == $password && $id == 0) {
	                        $_SESSION['user'] = "admin";
	                        $_SESSION['user_name'] = $username;
	                        header("Location: admin.php");
	                    } elseif ($_POST['username'] == $username && $_POST['password'] == $password) {
	                        $_SESSION['user']="blogger";
	                        $_SESSION['user_name'] = $username;
	                        header("Location: blogger_page.php");
	                    }
	                }
	            }
	        }
	    ?>

	    <div class="container div">

	        <form method="post" action="login.php" class="form-horizontal">
				<h1 class="title">My Cafe</h1><br>
				<div class="form-group">
	                <label class="control-label">Emaile</label>
	                <input type="email" name="email" class="form-control " required>
	                <label class="control-label">Password</label>
	                <input type="password" name="password" class="form-control" required>
	                <div class="checkbox">
	                    <label><input type="checkbox" name="keep_logged" value="true">Remember me</label>
	                </div><br>
	                <button type="submit" value="login" class="btn btn-info">Login</button>
					<br><br><br>
					<a href="#">Forget Password</a>

	                <?php
	                    if($_POST){
	                        echo "<br><br><p class='alert alert-danger'>Wrong username or password</p>";
	                    }
	                ?>
	            </div>
	        </form>
	    </div>
	</div>
</body>
</html>
