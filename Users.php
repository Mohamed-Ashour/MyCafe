<!DOCTYPE html>
<html>
<head>
	<title>Users</title>
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
            display: inline-block;
        }
        .image {
            height: 120px;
            width: 170px;
        }
    </style>
</head>
<body>
		<?php
            #start session
            session_start();
		?>

    <div class="container">
		 <!-- nav -->

		 <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">MyCafe</a>
    	</div>
	    	<!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		      <ul class="nav navbar-nav">
		        <li class="active"><a href="#">Products<span class="sr-only">(current)</span></a></li>
				<li><a href="#">Users</a></li>
				<li><a href="#">Manual Order</a></li>
		        <li><a href="#">Checks</a></li>
		      </ul>
		      <ul class="nav navbar-nav navbar-right">
		        <li><a href="#">Admin</a></li>
		      </ul>
	    </div>

		<!-- end nav -->
        <a href="registration.php" class="add-user btn btn-info">Add New User</a><br><br>
        <table class="table table">
			<thead>
                <tr>
                    <th>Name</th>
                    <th>Room</th>
                    <th>Image</th>
                    <th>Ext.</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
		</table>
        <p class="users-num well">
            Total number of registered users:
        </p>
    </div>


</body>
</html>
