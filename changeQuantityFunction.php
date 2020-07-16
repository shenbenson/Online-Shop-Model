<?php 
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	
	$connection = new mysqli($serverName, $username, $password);
	$connection->select_db($dbName);
	
	$newQuantity = $_POST["quantity"];
	$itemID = $_POST["itemID"];
	
	if ($newQuantity == false) {
		$connection->query('DELETE FROM shoppingCart WHERE itemID = "' . $itemID . '"');
	} else {
		$connection->query('UPDATE shoppingCart SET quantity = ' . $newQuantity . ' WHERE itemID = "' . $itemID . '"');
	}
	
	$connection->close();
	
	header("Location: /cart.php");
?>