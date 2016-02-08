<!DOCTYPE html>
<html>
<head>
	<title>Add User</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery-2.2.0.min.js"></script>
	<style type="text/css">
		#wrapper{
			width: 40%;
			margin: auto auto;
		}
	</style>
</head>
<body>
    <?php
    	session_start();
    ?>
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
				<input type="tel" name="room" class="form-control">
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
