<!DOCTYPE html>

<?php 
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	$itemID = $_GET["product"];
	
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);
	$sql = "SELECT * FROM products WHERE itemID = " . $itemID;
	$product = mysqli_fetch_assoc($connection->query($sql));
	
	if ($product["itemID"] != false) {
?>

<html>
	<head>
		<link rel="stylesheet" href="/css/navBar.css">
		<link rel="stylesheet" href="/css/product.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<title>Carey's Computers</title>
	</head>
	<body>
		<nav>
			<ul>
				<li id="logo"><a id="logo" href="/"><img class="logo" src="res/logo.png"></a></li>
				<li><div class="dropdown">
					<a draggable="false" href="/shop.php?search=&order=nameAZ"><button class="dropbtn"><b>SHOP</b></button></a>
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
							$finalProductPrice = $product["price"] * (1 - ($product["discount"]) / 100);
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
		<div id="pageContainer">
			<div id="divMainProduct">
				<div>
					<img id="productImage" draggable="false" alt="" src="/products/<?php echo $product['itemID']; ?>.png">
				</div>
				<div id="divProductInfo">
					<h1 id="productName"><?php echo $product["name"]; ?></h1>
					<p id="productPrice">Price: $<?php echo $finalProductPrice; ?></p>
					<?php if ($product['discount'] > 0) { ?>
					<p id="previousProductPrice">Previous Listing: <del>$<?php echo number_format($product["price"], 2); ?></del></p>
					<p id="previousProductPrice">You save: $<?php echo (number_format($product["price"] - $finalProductPrice, 2) . ' (' . $product["discount"] . '%)'); ?></p>
					<?php } ?>
					
					<?php if ($product["inventory"] == 0) { ?>
					<h2 id='outOfStock'>Out of stock</h2>
					<?php } else { ?>
					<p id="productInventory">Inventory: <?php echo $product["inventory"]; ?></p>
					<?php } ?>
					<hr class="horDiv">
					<h1 id="descriptionTitle">Product Description<h1>
					<p id="productDescription"><?php echo $product["description"]; ?></p>
				</div>
				<div id="divProductCheckout">
					<h2 id="checkoutTitle">Purchase</h2><hr class="horDiv">
					<?php
						$cartQuantity = mysqli_fetch_assoc($connection->query('SELECT quantity FROM shoppingcart WHERE itemID = "' . $product["itemID"] . '"'));
						if ($product["inventory"] == 0) { ?>
							<h2 align="center" id="outOfStock">Out Of Stock</h2>
					<?php } else { ?>
					<h2 id="inStock">In Stock</h2>
					<p id="productPrice">Price: $<?php echo number_format($finalProductPrice, 2); ?></p>
					<p id="shippingDescription">Get <b>free shipping</b> on orders over $40!</p>
					<div id="divBuyForm">
						<form action="addProductFunction.php" method="post">
							<label id="quantityLabel">Quantity:</label>
							<input id="checkoutQuantity" type="number" value="1" name="quantity" min="1" max="<?php echo ($product["inventory"] - $cartQuantity["quantity"]); ?>">
							<input name="itemID" type="hidden" value="<?php echo $product["itemID"]; ?>">
							<input id="addToCartButton" type="submit" value="Add To Cart">
						</form>
					</div>
					<?php } ?>
				</div>
			</div>
			<hr>
			<?php
				$check = mysqli_fetch_array($connection->query('SELECT COUNT(*) FROM products WHERE category = "' . $product["category"] . '" AND NOT itemID = ' . $product["itemID"] . ''));
				if ($check[0] != 0) { 
			?>
			<h3 class="moreProductsTitle">Related Products</h3>
			<?php } ?>
			<div>
				<div id="moreProductsScrollMenu">
					<?php
						$sql = 'SELECT name, itemID, price, discount FROM products WHERE category = "' . $product["category"] . '" AND NOT itemID = ' . $product["itemID"] . '';
						if ($result = $connection->query($sql)) {
							while ($row = mysqli_fetch_assoc($result)) {
								$moreProductsPrice = number_format($row["price"] * (1 - ($row["discount"]) / 100), 2);
								$maxNameLength = 90;
								if (strlen($row["name"]) > $maxNameLength) {
									$moreProductsName = substr($row["name"], 0, $maxNameLength) . " ...";
								} else {
									$moreProductsName = $row["name"];
								}
					?>
					<a href="product.php?product=<?php echo $row["itemID"]; ?>">
						<div class="divMoreProducts">
							<img class="moreProductsImg" src="products/<?php echo $row["itemID"]; ?>.png">
							<p class="moreProductsName"><?php echo $moreProductsName; ?></p>
							<p class="moreProductsPrice">$<?php echo $moreProductsPrice; ?></p>
						</div>
					</a>
					<?php
							}
						}
					?>
				</div>
			</div>
			<?php 
				$result = $connection->query('SELECT COUNT(*) FROM products WHERE brand = "' . $product["brand"] . '" AND NOT itemID = ' . $product["itemID"] . '');
				$row = mysqli_fetch_array($result);
				if ($row[0] != 0) { 
			?>
			<h3 class="moreProductsTitle">More From <?php echo $product["brand"]; ?></h3>
			<?php } ?>
			<div>
				<div id="moreProductsScrollMenu">
					<?php
						$sql = 'SELECT name, itemID, price, discount FROM products WHERE brand = "' . $product["brand"] . '" AND NOT itemID = ' . $product["itemID"] . '';
						if ($result = $connection->query($sql)) {
							while ($row = mysqli_fetch_assoc($result)) {
								$moreProductsPrice = number_format($row["price"] * (1 - ($row["discount"]) / 100), 2);
								if (strlen($row["name"]) > $maxNameLength) {
									$moreProductsName = substr($row["name"], 0, $maxNameLength) . " ...";
								} else {
									$moreProductsName = $row["name"];
								}
					?>
					<a href="product.php?product=<?php echo $row["itemID"]; ?>">
						<div class="divMoreProducts">
							<img class="moreProductsImg" src="products/<?php echo $row["itemID"]; ?>.png">
							<p class="moreProductsName"><?php echo $moreProductsName; ?></p>
							<p class="moreProductsPrice"><?php echo $moreProductsPrice; ?></p>
						</div>
					</a>
					<?php 
							}
						}
					?>
				</div>
			</div>
		</div>
		<footer class="center">
			<p>&copy; 2019 Carey's Computers</p> 
		</footer>
	</body>
</html>

<?php
	$connection->close();
	} else {
		echo "Error! Invalid product ID!";
	}
?>