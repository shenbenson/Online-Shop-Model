<?php
	session_start();
	$_SESSION["itemID"] = $_POST["itemID"];
	$_SESSION["quantity"] = $_POST["quantity"];
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);
	
	if (isset($_POST["quantity"]) && isset($_POST["itemID"])) {
		$check = mysqli_fetch_array($connection->query("SELECT quantity from shoppingCart WHERE itemID = " . $_POST["itemID"]));
		
		# If there is already an item with the same ID, it updates and adds the quantity to the cart instead of creating a new record.
		if ($check["quantity"] == false) {
			$connection->query("INSERT INTO shoppingCart(itemID, quantity) values (" . $_POST["itemID"] . "," . $_POST["quantity"] . ")");
		} else {
			$connection->query("UPDATE shoppingCart SET quantity = " . ($check["quantity"] + $_POST["quantity"]) . " WHERE itemID = " . $_POST["itemID"]);
		}
	}
	
	$connection->close();
	
	header("Location: /addProduct.php");
?>