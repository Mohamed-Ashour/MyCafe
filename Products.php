<!DOCTYPE html>
<html>
<head>
	<title>Products</title>
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
	require_once('database/model.php');
	$mydb = new ORM();
	$mydb->setTable("products");
	$products = $mydb->select_all();

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
					<li class="active"><a href="Products.php">Products<span class="sr-only">(current)</span></a></li>
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

    <div class="container">
        <a href="AddProduct.php" class="add-user btn btn-info">Add Product</a><br><br>
        <table class="table table">
			<thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
					<th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                while($row = $products->fetch_assoc()) {
                    $id = $row['id'];
                    echo "<tr>";
                    echo "<td>".$row["name"]."</td>";
                    echo "<td>".$row["price"]."</td>";
                    echo "<td><img src=".$row["pic"]." class='image'></td>";
                    echo "<td>".
                        "
                            <form method='post' action='EditProduct.php' class='action-btns'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' class='btn btn-success'>Edit</button>
                            </form>
                            <form method='post' action='Products.php' class='action-btns'>
                                <input type='hidden' name='id' value='$id'>
                                <button type='submit' class='btn btn-danger'>Delete</button>
                            </form>
                            "
                        ."</td>";
                    echo "</tr>";
                }

                ?>
            </tbody>
		</table>
    </div>


</body>
</html>
