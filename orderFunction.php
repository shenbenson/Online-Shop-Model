<?php 
	session_start();
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);
	
	do {
		$randNum = rand(10000001, 99999999);
		$sql = 'SELECT DISTINCT orderID FROM orders WHERE orderID ="' . $randNum . '"';
		$check = mysqli_fetch_array($connection->query($sql));
	} while ($check[0] != false);
	
	$check = mysqli_fetch_array($connection->query(
		'SELECT customerName 
		FROM customers 
		WHERE customerName = "' . $_POST['name'] . '" AND addressLine1 = "' . $_POST['address1'] . '" AND addressLine2 = "' . $_POST['address2'] . '" AND city = "' . $_POST['city'] . '" AND province = "' . $_POST['province'] . '" AND postalCode = "' . $_POST['postalCode'] . '" and telephoneNumber = "' . $_POST['telephoneNumber'] . '"'
	));
	
	if ($check['customerName'] == "") {
		$connection->query(
			'INSERT INTO 
			customers (customerName, addressLine1, addressLine2, city, province, postalCode, telephoneNumber) 
			VALUES ("' . $_POST['name'] . '", "' . $_POST['address1'] . '", "' . $_POST['address2'] . '", "' . $_POST['city'] . '", "' . $_POST['province'] . '", "' . $_POST['postalCode'] . '", "' . $_POST['telephoneNumber'] . '")'
		);
	}

	$customer = mysqli_fetch_array($connection->query(
		'SELECT customerID 
		FROM customers 
		WHERE customerName = "' . $_POST['name'] . '" AND addressLine1 = "' . $_POST['address1'] . '" AND addressLine2 = "' . $_POST['address2'] . '" AND city = "' . $_POST['city'] . '" AND province = "' . $_POST['province'] . '" AND postalCode = "' . $_POST['postalCode'] . '" and telephoneNumber = "' . $_POST['telephoneNumber'] . '"'
	));
	
	if ($result = $connection->query("SELECT * FROM shoppingcart")) {
		while ($row = mysqli_fetch_assoc($result)) {
			$connection->query(
				'INSERT INTO
				orders (itemID, quantity, orderID, customerID)
				VALUES (' . $row['itemID'] . ', ' . $row['quantity'] . ', ' . $randNum . ', ' . $customer['customerID'] . ')'
			);
			
			$connection->query('UPDATE products SET inventory = inventory - ' . $row['quantity'] . ' WHERE itemID = ' . $row['itemID']);
		}
	}
	
	$connection->query("TRUNCATE shoppingCart");
	
	$connection->close();
	
	$_SESSION["totalNum"] = $_POST["total"];
	$_SESSION["receiptID"] = $randNum;
	
	header("Location: /confirmation.php");
?>