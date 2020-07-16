<!DOCTYPE html>

<?php
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	$refund = false;
	
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);
	
	$orderID = !empty($_POST['orderID']) ? $_POST['orderID']: "";
	$refundQuantity = !empty($_POST["quantity"]) ? $_POST["quantity"]: "";
	$itemID = !empty($_POST["itemID"]) ? $_POST["itemID"]: "";
	$originalAmount = !empty($_POST["originalAmount"]) ? $_POST["originalAmount"]: "";
	
	if ($originalAmount != "" && $originalAmount - $refundQuantity == 0) {
		$connection->query('DELETE FROM orders WHERE itemID = "' . $itemID . '" AND orderID = "' . $orderID . '"');
		$connection->query('UPDATE products SET inventory = inventory + ' . $refundQuantity . ' WHERE itemID = ' . $itemID);
		$refund = true;
	} else if ($refundQuantity != "") {
		$connection->query('UPDATE orders SET quantity = quantity - ' . $refundQuantity . ' WHERE itemID = "' . $itemID . '" AND orderID = "' . $orderID . '"');
		$connection->query('UPDATE products SET inventory = inventory + ' . $refundQuantity . ' WHERE itemID = ' . $itemID);
		$refund = true;
	}
?>

<html>
	<head>
		<link rel="stylesheet" href="css/navBar.css">
		<link rel="stylesheet" href="css/refund.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<title>Refund - Carey's Computers</title>
		<script>
			if ( window.history.replaceState ) {
				window.history.replaceState( null, window.location.href );
			}
		</script>
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
						?>
					</div>
				</div></li>
				<li class="header"><a draggable="false" href="/about.php">ABOUT</a></li>
				<a draggable="false" href="cart.php"><img title="My Shopping Cart" id="shoppingcart" src="res/shoppingcarticon.png"></a>
				<a draggable="false" href="refund.php"><img title="Refund" id="shoppingcart" src="res/refundpage.png"></a>
				<form action="shop.php" method="get">
					<input type="hidden" name="order" value="nameAZ">
					<input class="searchbt" type="image" title="Search" src="res/searchicon.png" alt="Submit">
					<input class="search" autocomplete="off" type="text" placeholder="Search Products" name="search" required/>
				</form>
			</ul>
		</nav>
		<div id="pageContainer">
			<div class="infoContainer">
				<h1 id="refundLabel">Refund</h1>
				<form action="refund.php" method="post">
					<label>Order ID: </label>
					<input autocomplete="off" id="inputID" type="text" name="orderID" value="<?php echo $orderID; ?>" required/>
					<input type="submit" id="searchID" value="Search">
				</form>
				<?php
					$sql = 'SELECT itemID, quantity, customerID FROM orders WHERE orderID="' . $orderID . '"';
					$totalPrice = 0;
					$totalQuantity = 0;
					$maxNameLength = 100;
					$count = 0;
					$result = $connection->query($sql);
					$row = mysqli_fetch_assoc($result);
					
					$cInfo = mysqli_fetch_assoc($connection->query('SELECT customerName, addressLine1, addressLine2, city, province, postalCode, telephoneNumber FROM customers WHERE customerID="' . $row['customerID'] . '"'));
						
					if ($row["itemID"] != "") { 
						$count++; 
				?>
				<h3><b>Showing order with Order ID: <?php echo $orderID; ?></b></h3>
				<h4 id="paymentInformationTitle"><b>Payment Information:</b></h4>
				<p class="customerInfoText"><?php echo $cInfo["customerName"]; ?></p>
				<p class="customerInfoText"><?php echo ($cInfo["addressLine1"] . " " . $cInfo["addressLine2"]); ?></p>
				<p class="customerInfoText"><?php echo ($cInfo["city"] . ", " . $cInfo["province"] . ", " . $cInfo["postalCode"]); ?></p>
				<p class="customerInfoText"><?php echo $cInfo["telephoneNumber"]; ?></p>
				<?php }
					if ($count == 0 && $orderID != "") { 
				?>
				<h3 id="noOrderFound">No order found with Order ID: <?php echo $orderID; ?></h3>
				<?php } ?>
			</div>
			<div class="orderContainer">
				<?php
					if ($result = $connection->query($sql)) {
						while ($row = mysqli_fetch_assoc($result)) {
							
							$productInfo = mysqli_fetch_assoc($connection->query('SELECT name, price, discount, inventory FROM products WHERE itemID ="' . $row['itemID'] . '"'));
							
							$productName = substr($productInfo['name'], 0, $maxNameLength);
											
							if (strlen($productInfo['name']) > $maxNameLength) {
								$productName .= " ...";
							}
							
							$finalProductPrice = round($productInfo['price'] * (1 - ($productInfo['discount']) / 100), 2); 
				?>
				<div class="productContainer">
					<div class="imageContainer">
						<a target="_blank" href="/product.php?product=<?php echo $row['itemID']; ?>">
							<img class="productImage" src="/products/<?php echo $row["itemID"]; ?>.png"></img>
						</a>
					</div>
					<div class="nameContainer">
						<a target="_blank" href="/product.php?product=<?php echo $row['itemID']; ?>">
							<p class="productName"><?php echo $productName; ?></p>
						</a>
					</div>
					<div class="priceContainer">
						<p class="productPrice">Price: $<?php echo number_format($finalProductPrice, 2); ?></p>
					</div>
					<div class="quantityContainer">
						<form action="/refund.php" method="post">
							<label class="quantityLabel">Purchased: <?php echo $row["quantity"]; ?></label>
							<label class="quantityLabel">Refund: </label>
							<input name="itemID" type="hidden" value="<?php echo $row["itemID"]; ?>">
							<input name="originalAmount" type="hidden" value="<?php echo $row["quantity"]; ?>">
							<input name="quantity" type="number" value="1" id="refundNumberField" min="1" max="<?php echo $row['quantity']; ?>">
							<input name="orderID" type="hidden" value="<?php echo $orderID; ?>">
							<input class="refundButton" type="submit" value="Refund">
						</form>
					</div>
				</div>
				<?php 
						}
					}
					if ($refund) { 
				?>
				<script>
					alert("Refund successful!");
				</script>
				<?php } ?>
			</div>
		</div>
	</body>
</html>