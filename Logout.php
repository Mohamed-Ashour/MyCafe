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

            if( isset($_SESSION["user"]) ) {
                $_SESSION = array();
                session_unset();
                session_destroy();
            }

            if( isset($_COOKIE["user"]) ) {
                setcookie('user', '', time() - (86400 * 30));
                setcookie('user_id', '', time() - (86400 * 30));
            }
            header("Location: Login.php");
        }

        ?>

	</div>
</body>
</html>
