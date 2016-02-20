<!DOCTYPE html>
<html>
<head>
	<title>Forgot Password</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/style.css" charset="utf-8">
	<link rel="icon" href="images/favicon.ico" type="image/gif" sizes="32x32">
	<script src="js/jquery-2.2.0.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container">

	    <div class="container div">


	        <form method="post" action="ForgotPassword.php" class="form-horizontal loginForm">
                <?php

                session_start();

                if($_POST) {
                    require_once('database/model.php');
                    $mydb = new ORM();
                    $mydb->setTable("users");
                    $users = $mydb->select_all();
                    $found = 0;
                    while($row = $users->fetch_assoc()) {
                        if ($_POST['email'] == $row["email"]) {
                            // send mail
                            $message = "your new password is: iti";
                            mail($_POST['email'], "New Password", $message);

                            $where = array('id' => $row['id'] );
                            $new_pass = array('password' => hash("md5", "iti"));
                            $result = $mydb->update($where, $new_pass);
                            
                            echo "<h4 class='alert-success'>Mail with the new password is sent to ". $row["email"] ."</h4>";

                            $found = 1;
                        }
                    }
                    if(!$found){
                        echo "<h4 class='alert-danger'> email is incorrect</h4>";
                    }
                }

                ?>
				<h1 class="title">My Cafe</h1><br>
				<div class="form-group">
	                <label class="control-label">Email</label>
	                <input type="email" name="email" class="form-control " required><br>
	                <button type="submit" value="mail" class="btn btn-info">Send new password</button>
	            </div>
	        </form>
	    </div>
	</div>
</body>
</html>
