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
		<link rel="stylesheet" href="css/home.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<title>Home - Carey's Computers</title>
	</head>
	<body>
		<div class="layer"></div>
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
				<a draggable="false" href="refund.php"><img title="Refund" id="shoppingcart" src="res/refund.png"></a>
				<form action="shop.php" method="get">
					<input type="hidden" name="order" value="nameAZ">
					<input class="searchbt" type="image" title="Search" src="res/searchicon.png" alt="Submit">
					<input class="search" autocomplete="off" type="text" placeholder="Search Products" name="search" required/>
				</form>
			</ul>
		</nav>
		<div id="pageContainer">
			<h1>Welcome to Carey's Computers</h1>
			<p><i>Discover the best technologies</i> &nbsp;-&nbsp; Current Time: 
			<?php 
				date_default_timezone_set("America/Toronto"); 
				echo date("h:i A, M d,Y");  
				$connection->close();
			?>
			</p>
			<a href="/shop.php?search=&order=nameAZ"><button class="button">Start Shopping</button></a>
		</div>
	</body>
</html>