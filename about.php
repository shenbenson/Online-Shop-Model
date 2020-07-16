<!DOCTYPE html>

<html>
	<head>
		<link rel="stylesheet" href="css/navBar.css">
		<link rel="stylesheet" href="css/about.css">
		<link rel="icon" type="image/png" href="res/favicon.png">
		<link href="https://fonts.googleapis.com/css?family=PT+Serif|Raleway" rel="stylesheet">
		<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.9.0/css/all.css">
		<title>About - Carey's Computers</title>
	</head>
	<body>
		<nav>
			<ul>
				<li id="logo"><a id="logo" href="/"><img class="logo" src="res/logo.png"></a></li>
				<li><div class="dropdown">
					<a class="dropbtn" draggable="false" href="/shop.php?search=&order=nameAZ"><button class="dropbtn"><b>SHOP</b></button></a>
					<div class="dropdown-content">
						<?php 
							$serverName = "localhost";
							$username = "root";
							$password = "";
							$dbName = "H";
							
							$connection = new mysqli($serverName, $username, $password);
							$connection->select_db($dbName);
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
				<li class="header"><a draggable="false" id="active" href="/about.php">ABOUT</a></li>
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
		
		<div class="hero-image">
			<div class="hero-text">
				<h1>Unity.<br>Precision.<br>Perfection.</h1>
				<br> <br> <br>
				<p>&copy; Carey's Computers </p>
	 		</div>
		</div>
		
		<section class="services">
			<div class="container grid-3 center">
				<div style="text-align: left">
					<h4 style="margin-left: 6rem">ABOUT OUR COMPANY</h4>
					<p style="font-size: 1.5rem">Carey's Computers was founded in 2019 and has since then thrived itself on three principles: customer satisfaction, the passion for innovation, and dirt-cheap prices.
				</div>
				<div>
					<i class="fas fa-truck fa-4x"></i>
					<h3>Shipment tracking</h3>
					<p>Whether you're receiving one package or shipping hundreds, Carey's Computers provides insight about each shipment's status all along its journey.</p>
				</div>
				<div>
					<i class="fas fa-phone-square fa-4x"></i>
					<h3>Have a question?</h3>
					<p>If you have any questions, our Customer Service center will be happy to assist you 24 hours a day, 7 days a week at 1-800-123-456.</p>
				</div>
			</div>
		</section>
		<section id=meet-the-team >
			<div class="container">
				<h1 class="heading green">Meet the team</h1>
				<div class="card-wrapper">

					<div class="card">
						<img src="res/bg1.jpg" alt="background not found" class="card-img">
						<img src="res/cz.jpg" alt="profile image" class ="profile-img">
						<h1>Carey Zheng</h1>
						<p class="job-title">Creator</p>
						<p class="about">
							"Success is no accident. It is hard work, perseverance, learning, studying, sacrifice and most of all, love of what you are doing or learning to do." <br><br>
						</p>
					</div>

					<div class="card">
						<img src="res/bg2.jpeg" alt="background not found" class="card-img">
						<img src="res/bs.jpg" alt="profile image" class="profile-img">
						<h1>Benson Shen</h1>
						<p class="job-title">Creator</p>
						<p class="about">
							"The price of success is hard work, dedication to the job at hand, and the determination that whether we win or lose, we have applied the best of ourselves to the task at hand."
						</p>
					</div>

					<div class="card">
						<img src="res/bg3.jpg" alt="background not found" class="card-img">
						<img src="res/km.jpg" alt="profile image" class ="profile-img">
						<h1>Kai Wei Mo</h1>
						<p class="job-title">Creator</p>
						<p class="about">
							"Do not wait; the time will never be 'just right.' Start where you stand, and work with whatever tools you may have at your command, and better tools will be found as you go along."
						</p>
					</div>
					
				</div>
			</div>
		</section>
		<footer class="center">
			<p>&copy; 2019 Carey's Computers</p> 
		</footer>
	</body>
</html>