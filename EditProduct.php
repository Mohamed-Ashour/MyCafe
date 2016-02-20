<!DOCTYPE html>
<html>
<head>
	<title>Edit Product</title>
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
            echo "You have no access to this page!";
            exit;
        }
    }

    if(isset($_COOKIE['user'])){
        if($_COOKIE['user']!="admin"){
            echo "You have no access to this page!";
            exit;
        }
    }

	require_once('database/model.php');
	$prod_db = new ORM();
	$prod_db->setTable("products");

	if(isset($_POST["id"])) {
		$product = $prod_db->select(array("id"=>$_POST["id"]));
		$row = $product->fetch_assoc();
	}
	else{
		echo "Go Away!!";
		exit();
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

		require_once('database/model.php');

		$cat_db = new ORM();
		$cat_db->setTable("categories");
		$categories = $cat_db->select_all();
		if( isset($_POST["product_name"]) ){
			$key=0;

			if (empty($_POST["product_name"])) {
				echo "<h4 class='alert-danger'> Product name is required</h4>";
				$key=1;
			}

			if (empty($_POST["price"])) {
				echo "<h4 class='alert-danger'> Price is required</h4>";
				$key=1;
			}

			if (empty($_POST["category"])) {
				echo "<h4 class='alert-danger'> Category is required</h4>";
				$key=1;
			}

			if ($key==0) {
				// image handling
				if( !empty($_FILES['image']['name']) ){
					$image_path="images/products/".$_FILES['image']['name'];
					move_uploaded_file($_FILES["image"]["tmp_name"], $image_path);
					$image = $_FILES['image']['name'];
				}
				else {
					$image = 'default.jpg';
				}
				// database insertion
				$selected = $cat_db->select( array('name' => $_POST["category"] ) );
				$category = $selected->fetch_assoc();

				$product = array('name' => $_POST["product_name"] ,
				'price' => $_POST["price"] , 'category_id' => $category['id'] ,
				'is_available' => 1 , 'pic' => $image );
				$where = array('id' => $_POST["id"]);
				$result = $prod_db->update($where, $product);

				header("Location: Products.php");
			}
		}

		?>

        <h1>Edit Product</h1>
		<form method="post" action="EditProduct.php" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group panel">
				<label class="control-label">Product Name</label>
				<input type="text" name="product_name" class="form-control" value="<?php echo $row['name']; ?>"><br>
                <label>Price</label><br>
				<input type="number" name="price" min="0" class="form-control price"  value="<?php echo $row['price']; ?>">
                <span class="desc">EGP</span> <br>
                <label class="control-label">Category</label><br>
				<select class="form-control category" name="category">

					<?php

					while($cat = $categories->fetch_assoc()) {
						if ($cat['id'] == $row['category_id']) {
							echo "<option selected value='"  . $cat['name'] . "'>" . $cat['name'] . "</option>" ;
						}
						else {
							echo "<option value='"  . $cat['name'] . "'>" . $cat['name'] . "</option>" ;
						}
					}

					?>

				</select>
				<a href="AddCategory.php" class="desc">Add category</a><br><br>
                <label class="control-label">Product picture</label><br>
				<input type="file" name="image" class="form-control pic">
				<input type="hidden" name="id" value="<?php echo $_POST["id"] ?>">
				<br><br>

				<button type="submit" value="Submit" class="btn btn-info">Submit</button>
	  			<button type="reset" value="Reset" class="btn btn-alert">Reset</button>
  			</div>
		</form>
	</div>
</body>
</html>
