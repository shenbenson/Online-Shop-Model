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
		<link rel="stylesheet" href="css/checkout.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<title>Carey's Computers</title>
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
			<div id="infoContainer">
				<h1 id="checkoutTitle">Checkout</h1>
				<form method="post" action="/orderFunction.php">
					<div id="informationContainer">
						<h2 id="informationTitle">Shipping Information</h2>
						<label class="inputLabel">Full Name:</label>
						<input required type="text" maxlength="50" class="inputField" name="name">
						<label class="inputLabel">Address Line #1:</label>
						<input required type="text" maxlength="100" class="inputField" name="address1">
						<label class="inputLabel">Address Line #2 (Optional):</label>
						<input type="text" maxlength="100" class="inputField" name="address2">
						<label class="inputLabel">City:</label>
						<input required type="text" maxlength="50" class="inputField" name="city">
						<label class="inputLabel">Province:</label>
						<input required type="text" maxlength="15" class="inputField" name="province">
						<label class="inputLabel">Postal Code</label>
						<input required type="text" minlength="6" maxlength="7" class="inputField" name="postalCode">
						<label class="inputLabel">Telephone Number</label>
						<input required type="text" minlength="10" maxlength="10" class="inputField" name="telephoneNumber">
						<hr>
						<h2 id="informationTitle">Credit Card Information</h2>
						<label class="inputLabel">Credit Card Number:</label>
						<input required type="text" minlength="16" maxlength="16" class="inputField" name="creditCardNumber">
						<label class="inputLabel">CVV:</label>
						<input type="text" minlength="3" maxlength="3" class="inputField" name="cvv">
					</div>
					<div id="orderContainer">
						<h1 id="orderTitle">Order</h1>
						<hr class="orderHR">
						<?php
							$sql = "SELECT * from shoppingcart";
							$maxNameLength = 100;
							$subtotal = 0;
							
							if ($result = $connection->query($sql)) {
								while ($row = mysqli_fetch_assoc($result)) {
									$productInfo = mysqli_fetch_assoc($connection->query('SELECT name, price, discount, inventory FROM products WHERE itemID ="' . $row['itemID'] . '"'));
									$finalProductPrice = $finalProductPrice = round($productInfo['price'] * (1 - ($productInfo['discount']) / 100), 2);

									$subtotal += $finalProductPrice * $row['quantity'];
									
									if (strlen($productInfo['name']) > $maxNameLength) {
										$productName = (substr($productInfo['name'], 0, $maxNameLength) . " ...");
									} else {
										$productName = $productInfo['name'];
									}
									
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
								<p class="productPrice">$<?php echo number_format($finalProductPrice, 2); ?></p>
							</div>
							<div class="quantityContainer">
								<p class="productQuantity">Quantity:<?php echo $row['quantity']; ?></p>
							</div>
						</div>
						<?php 
								}
							}
						?>
						<hr class="orderHR">
						<div id="subtotalContainer">
							<p id="subtotalTitle">Subtotal: </p>
							<p id="subtotalPrice"><?php echo ("$" . number_format($subtotal, 2)); ?> </p>
							<?php
								$shipment = 0;
								if ($subtotal < 40) {
									echo'
										<p id="shippingCostTitle">Shipping Cost: <b>$20.00</b></p>
									';
									$shipment = 20;
									$subtotal += $shipment;
								}
							?>
							<p id="taxTitle"> HST: </p>
							<p id="subtotalPrice"><?php echo ("$" . number_format($subtotal * 0.13, 2)); ?></p>
							<br />
							<p id="grandTotalTitle">Grand Total: </p>
							<p id="grandTotalPrice"><?php echo ("$" . number_format($subtotal * 1.13, 2)); ?></p>
							<input name="total" type="hidden" value="<?php echo ("$" . number_format($subtotal * 1.13, 2)); ?>">
						</div>
						<input id="orderButton" type="submit" value="Order">
					</div>
				</form>
			</div>
		</div>
		<footer class="center">
			<p>&copy; 2019 Carey's Computers</p> 
		</footer>
	</body>
</html>

<?php
	$connection->close();
?>