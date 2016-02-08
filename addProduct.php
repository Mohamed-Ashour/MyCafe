<!DOCTYPE html>
<html>
<head>
	<title>Add Product</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/jquery-2.2.0.min.js"></script>
	<style type="text/css">
		#wrapper {
			width: 30%;
			margin: auto auto;
		}
		.cap {
			font-size: 30px;
			border: 1px solid #ccc;
			padding: 15px 140px;
			margin: 0 auto;
			background-color: #fefefe;
		}
		.price {
			width: 50%;
			display: inline-block;
		}
		.category {
			width: 50%;
			display: inline-block;
		}
		.pic {
			width: 50%;
			display: inline-block;
		}
	</style>
</head>
<body>
    <?php
    	session_start();
    ?>
	<div class="container" id="wrapper">
        <h1>Add Product</h1>
		<form method="post" action="done.php" class="form-horizontal" enctype="multipart/form-data">
			<div class="form-group panel">
				<label class="control-label">Prduct Name</label>
				<input type="text" name="product_name" class="form-control"><br>
                <label>Price</label><br>
				<input type="number" name="price" min="0" class="form-control price" > EGP <br>
                <label class="control-label">Category</label><br>
				<select class="form-control category" name="category">

				</select>
				<a href="#">Add category</a><br><br>
                <label class="control-label">Product picture</label><br>
				<input type="file" name="image" class="form-control pic">
				<br><br>

				<button type="submit" value="Submit" class="btn btn-info">Submit</button>
	  			<button type="reset" value="Reset" class="btn btn-alert">Reset</button>
  			</div>
		</form>
	</div>
</body>
</html>
