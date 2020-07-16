<!DOCTYPE HTML>

<?php
	$search = $_GET["search"];
	$order = $_GET["order"];
	$searchDes  = !empty($_GET['searchDes']) ? $_GET['searchDes']: "false";

	#SEARCH STUFF XD
	
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);	
?>

<html>
	<head>
		<link rel="stylesheet" href="/css/navBar.css">
		<link rel="stylesheet" href="/css/shop.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<title>Shop - Carey's Computers</title>
	</head>
	<body>
		<nav>
			<ul>
				<li id="logo"><a id="logo" href="/"><img class="logo" src="res/logo.png"></a></li>
				<li><div class="dropdown">
					<a class="dropbtn" draggable="false" href="/shop.php?search=&order=nameAZ"><button id="active" class="dropbtn"><b>SHOP</b></button></a>
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
					<input type="hidden" name="searchDes" value="<?php echo $searchDes ?>">
					<input class="searchbt" type="image" title="Search" src="res/searchicon.png" alt="Submit">
					<input class="search" autocomplete="off" type="text" placeholder="Search Products" name="search" required/>
				</form>
			</ul>
		</nav>
		<div id="pageContainer">
			<?php 
				$action = "";
				if ($search == "do a barrel roll" || $search == "tilt" || $search == "askew") { 
					$action = $search;
					$search = "";
				}

				switch ($order) {
					case "nameAZ":
						$sqlOrder = " ORDER BY name";
						break;
					case "nameZA":
						$sqlOrder = " ORDER BY name DESC";
						break;
					case "priceHL":
						$sqlOrder = " ORDER BY CAST((price * (100 - discount)) AS int) DESC";
						break;
					case "priceLH":
						$sqlOrder = " ORDER BY CAST((price * (100 - discount)) AS int)";
						break;
				}
				
				$searchHold = $search;
				
				if (substr($search, 0, 6) == "brand:") {
					$search = substr($search, 6);
					$sql = 'SELECT itemID, name, price, discount FROM products WHERE brand ="' . $search . '"';
				} else if (substr($search, 0, 9) == "category:") {
					$search = substr($search, 9);
					$sql = 'SELECT itemID, name, price, discount FROM products WHERE category ="' . $search . '"';
				} else {
					$sql = 'SELECT itemID, name, price, discount FROM products WHERE name LIKE "%' . $search . '%" OR brand LIKE "' . $search . '" OR category LIKE "' . $search . '"';
				}
				
				if ($searchDes == "true") {
					$sql .= ' OR description LIKE "%' . $search . '%"';
				}
				
				$sql .= $sqlOrder;
			?>
			<div>
				<?php
					$check = mysqli_fetch_array($connection->query($sql));
					if ($check[0] == 0) {
						echo '<p id="showingResultsMessage">No Products Found For: <b>' . $search . '</b></p>';
					} else if ($search == "") {
						echo '<p id="showingResultsMessage"><b>Showing All Products</b></p>';
					} else {
						echo '<p id="showingResultsMessage">Showing Results For: <b>' . $search . '</b></p>';
					}
				?>
			</div>
			
			<form action="/shop.php" class="orderby" method="get">
                <input type="hidden" name="search" value="<?php echo $searchHold ?>">
				<input type="hidden" name="searchDes" value="<?php echo $searchDes ?>">
                <select class="selectOption" name="order" onchange="this.form.submit()">
                    <option value="nameAZ" <?php if($order == "nameAZ"){ echo "selected";} ?>>Name: A - Z</option>
                    <option value="nameZA" <?php if($order == "nameZA"){ echo "selected";} ?>>Name: Z - A</option>
                    <option value="priceHL" <?php if($order == "priceHL"){ echo "selected";} ?>>Price: High - Low</option>
                    <option value="priceLH" <?php if($order == "priceLH"){ echo "selected";} ?>>Price: Low - High</option>
                </select>
            </form>
			<div id="searchDescriptionsContainer">
				<label id="searchDescriptionTitle">Search Descriptions</label>
				<form action="/shop.php" class="orderby" method="get">
					<input type="hidden" name="search" value="<?php echo $searchHold ?>">
					<input type="hidden" name="order" value="<?php echo $order ?>">
					<label class="switch">
						<input type="checkbox" name="searchDes" onchange="this.form.submit()" <?php if($searchDes == "true"){ echo 'value="false" checked'; } else { echo 'value="true"'; } ?>>
						<span class="slider round"></span>
					</label>
				</form>
			</div>
			<hr>
			<?php
				$maxNameLength = 70;
			
				if ($result = $connection->query($sql)) {
					while ($product = mysqli_fetch_assoc($result)) {
						$productName = substr($product['name'], 0, $maxNameLength);
											
						if (strlen($product['name']) > $maxNameLength) {
							$productName .= " ...";
						}
						
						$productPrice = number_format($product["price"] * (1 - ($product["discount"]) / 100), 2);
			?>
			<a href="/product.php?product=<?php echo $product["itemID"]; ?>">
				<div class="productContainer">
					<img class="productImage" src="/products/<?php echo $product["itemID"]; ?>.png" alt="<?php echo $product["name"]; ?>">
					<p class="productName"><?php echo $productName; ?></p>
					<p class="productPrice">Price: $<?php echo $productPrice; ?></p>
					<?php if ($product["discount"] > 0) {?>
					<p class="discountPrice">Save: $<?php echo number_format(($product["price"] * $product["discount"] / 100), 2); ?></p>
					<?php } ?>
				</div>
			</a>
			<?php 
					}
				}
			?>
		</div>
		<?php if ($action == "do a barrel roll") { ?>
		<style>
			@keyframes roll {
				from { transform: rotate(0deg) }
				to   { transform: rotate(360deg) }
			}

			@-webkit-keyframes roll {
				from { -webkit-transform: rotate(0deg) }
				to   { -webkit-transform: rotate(360deg) }
			}

			body {
				-webkit-animation-name: roll;
				-webkit-animation-duration: 3s;
				-webkit-animation-iteration-count: 1;
			}

			html,body {
			   margin: 0;
			   padding: 0;
			   height: 100%;
			}
		</style>
		<?php } elseif ($action == "tilt" || $action == "askew") { ?>
		<style>
			@keyframes roll {
				from { transform: rotate(360deg) }
				to   { transform: rotate(358.5deg) }
			}

			@-webkit-keyframes roll {
				from { -webkit-transform: rotate(360deg) }
				to   { -webkit-transform: rotate(358.5deg) }
			}

			body {
				-webkit-animation-name: roll;
				-webkit-animation-duration: 1s;
				-webkit-animation-iteration-count: 1;
			}

			body {
			  transform: rotate(358.5deg);
			}
		</style>
		<?php } ?>
	</body>
</html>

<?php $connection->close(); ?>