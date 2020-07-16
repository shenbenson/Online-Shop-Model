<!DOCTYPE html>

<?php
	session_start();
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);
	$itemID = $_SESSION["itemID"];
	$quantity = $_SESSION["quantity"];
	
	$product = mysqli_fetch_assoc($connection->query("SELECT * FROM products WHERE itemID = " . $itemID));
?>

<html>
	<head>
		<link rel="stylesheet" href="css/navBar.css">
		<link rel="stylesheet" href="css/addProduct.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<title>Carey's Computers</title>
	</head>
	<body>
		<div>
			<nav>
				<ul>
					<li id="logo"><a id="logo" href="/"><img class="logo" src="res/logo.png"></a></li>
					<li><div class="dropdown">
						<a class="dropbtn" draggable="false" href="/shop.php?search=&order=nameAZ"><button class="dropbtn"><b>SHOP</b></button></a>
						<div class="dropdown-content">
							<?php 
								$sql = 'SELECT DISTINCT category FROM products ORDER BY category';
								if ($result = $connection->query($sql)) {
									while ($category = mysqli_fetch_assoc($result)) {
										echo '<a href="/shop.php?order=nameAZ&x=0&y=0&search=category:' . $category["category"] . '">' . $category["category"] . '</a>';
									}
								}
							?>
						</div>
					</div></li>
					<li><div class="dropdown">
						<button class="dropbtn"><b>BRANDS</b></button>
						<div class="dropdown-content">
							<?php 
								$sql = 'SELECT DISTINCT brand FROM products ORDER BY brand';
								if ($result = $connection->query($sql)) {
									while ($brand = mysqli_fetch_assoc($result)) {
										echo '<a href="/shop.php?order=nameAZ&x=0&y=0&search=brand:' . $brand["brand"] . '">' . $brand["brand"] . '</a>';
									}
								}
							?>
						</div>
					</div></li>
					<li class="header"><a draggable="false" href="/about.php">ABOUT</a></li>
					<a draggable="false" href="cart.php"><img title="My Shopping Cart" id="shoppingcart" src="res/shoppingcarticon.png"></a>
					<a draggable="false" href="refund.php"><img title="Refund" id="shoppingcart" src="res/refund.png"></a>
					<form action="shop.php" method="get">
						<input type="hidden" name="order" value="nameAZ">
						<input class="searchbt" type="image" title="Search" src="res/searchicon.png" alt="Submit">
						<input class="search" autocomplete="off" type="text" placeholder="Search Products" name="search" required/>
					</form>
				</ul>
			</nav>
		</div>
		<div id="pageContainer">
			<p id="addedProductMessage">Item successfully added to cart!</p>
			<div id="divAddedInfo">
				<img id="addedProductImage" draggable="false" src="products/<?php echo $product["itemID"]; ?>.png"></img>
				<div id="divAddedProductInfo">
					<p id="addedName">
						<a id="addedLink" href="product.php?product= <?php echo $product["itemID"]; ?> "> 
							<?php 
								$maxNameLength = 50; 
								if (strlen($product["name"]) > $maxNameLength) { 
									echo (substr($product["name"], 0, $maxNameLength) . " ..."); 
								} else { 
									echo $product["name"]; 
								} 
							?> 
						</a>
					</p>
					<p id="addedPrice">Price Per Unit: <?php echo " $" . number_format($product["price"] * (1 - ($product["discount"]) / 100), 2); ?></p>
					<p id="addedQuantity">Quantity added:<?php echo " " . $quantity; ?></p>
					<a href="/cart.php"><button id="viewCartButton">View Shopping Cart</button></a>
					<a href="/shop.php?search=&order=nameAZ"><button id="continueShoppingButton">Continue Shopping</button></a>
				</div>
			</div>
		</div>
	</body>
</html>

<?php 
	$connection->close(); 
?>