<!DOCTYPE html>

<?php 
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);
?>

<html>
	<head>
		<link rel="stylesheet" href="css/navBar.css">
		<link rel="stylesheet" href="css/cart.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<title>Cart - Carey's Computers</title>
	</head>
	<body>
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
				<a draggable="false" href="cart.php"><img title="My Shopping Cart" id="shoppingcart" src="res/shoppingcart.png"></a>
				<a draggable="false" href="refund.php"><img title="Refund" id="shoppingcart" src="res/refund.png"></a>
				<form action="shop.php" method="get">
					<input type="hidden" name="order" value="nameAZ">
					<input class="searchbt" type="image" title="Search" src="res/searchicon.png" alt="Submit">
					<input class="search" autocomplete="off" type="text" placeholder="Search Products" name="search" required/>
				</form>
			</ul>
		</nav>
		<div id="pageContainer">
			<div id="allProductsContainer">
				<h1 id="shoppingCartLabel">Shopping Cart</h1>
				<?php
					$sql = "SELECT * FROM shoppingCart";
					$product = mysqli_fetch_assoc($connection->query($sql));
					$totalPrice = 0;
					$totalQuantity = 0;
					$maxNameLength = 100;
					
					if ($product["itemID"] == false) {
						echo "<h3>Your shopping cart is empty!</h3>";
					}

					if ($result = $connection->query($sql)) {
						while ($row = mysqli_fetch_assoc($result)) {
							$productInfo = mysqli_fetch_assoc($connection->query('SELECT name, price, discount, inventory FROM products WHERE itemID ="' . $row['itemID'] . '"'));
											
							$productName = substr($productInfo['name'], 0, $maxNameLength);
											
							if (strlen($productInfo['name']) > $maxNameLength) {
								$productName .= " ...";
							}
							
							$finalProductPrice = round($productInfo['price'] * (1 - ($productInfo['discount']) / 100), 2);
							
							$totalPrice += $finalProductPrice * $row['quantity'];
							$totalQuantity += $row['quantity'];
							
				?>
				<div class="productContainer">
					<div class="imageContainer">
						<a href="/product.php?product=<?php echo $row['itemID']; ?>">
							<img class="productImage" src="/products/<?php echo $row["itemID"]; ?>.png"></img>
						</a>
					</div>
					<div class="nameContainer">
						<a href="/product.php?product=<?php echo $row['itemID']; ?>">
							<p class="productName"><?php echo $productName; ?></p>
						</a>
					</div>
					<div class="priceContainer">
						<p class="productPrice">Price: $<?php echo number_format($finalProductPrice, 2) ?></p>
					</div>
					<div class="quantityContainer">
						<form action="changeQuantityFunction.php" method="post">
							<label class="quantityLabel">Quantity:</label>
							<input class="productQuantity" type="number" value="<?php echo $row['quantity']; ?>" name="quantity" min="0" max="<?php echo $productInfo['inventory']; ?>">
							<input name="itemID" type="hidden" value="<?php echo $row["itemID"]; ?>">
							<input class="updateQuantity" type="submit" value="Update">
						</form>
						<form action="changeQuantityFunction.php" method="post">
							<input name="itemID" type="hidden" value="<?php echo $row["itemID"]; ?>">
							<input name="quantity" type="hidden" value="0">
							<input class="updateQuantity" type="submit" value="Remove">
						</form>
					</div>
				</div>
				<?php 	
						}
					}
				?>
			</div>
			<?php 
				if ($totalQuantity > 0) { 
			?>
			<div id="checkoutContainer">
				<h1 id="checkoutTitle">Checkout</h1>
				<hr>
			<?php
					$itemText = "Item";
					$shippingEligibility = "Get <b>free shipping</b> on orders over $40!";
					$shipment = 0;

					if ($totalQuantity != 1) {
						$itemText .= "s";
					}
					if ($totalPrice > 40) {
						$shippingEligibility = "You are eligible for <b>free shipping</b>!";
					}
			?>
				<p id="subtotalTitle">Subtotal <?php echo ("(" . $totalQuantity . " " . $itemText . "): "); ?></p>
				<p id="subtotalPrice"><?php echo ("$" . number_format($totalPrice, 2)); ?></p>
				<br />
				<?php
					if ($totalPrice < 40) {
						$shipment = 20;
						$totalPrice += $shipment;
				?>
				<p id="subtotalTitle">Shipping Cost:</p>
				<p id="subtotalPrice"><?php echo ("$" . number_format($shipment, 2)); ?></p>
				<br />
				<?php } ?>
				<p id="subtotalTitle">HST: </p>
				<p id="subtotalPrice"><?php echo ("$" . number_format($totalPrice * 0.13, 2)); ?></p>
				<br />
				<p id="grandTotalTitle">Grand Total: </p>
				<p id="grandTotalPrice"><?php echo ("$" . number_format($totalPrice * 1.13, 2)); ?></p>
				<p id="shippingEligibility"><?php echo $shippingEligibility; ?></p>
				<a href="/checkout.php"><button id="checkoutButton">Checkout</button></a>
			</div>
			<?php } ?>
		</div>
	</body>
</html>

<?php
	$connection->close();
?>