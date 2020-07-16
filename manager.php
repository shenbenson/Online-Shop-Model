<?php
	$serverName = "localhost";
	$username = "root";
	$password = "";
	$dbName = "H";
	$fileName = "setup/products.csv";
	$success = true;
	
	// Create connection
	$connection = new mysqli($serverName, $username, $password);
	
	// Create database	
	$connection->query("CREATE DATABASE $dbName");
	
	// Connect to database
	$connection->select_db($dbName);
	
	// Create table
	$connection->query("
        CREATE TABLE products (
            itemID INT AUTO_INCREMENT,        
            name VARCHAR(150),    
            brand VARCHAR(20),    
            category VARCHAR(20),  		 
            price VARCHAR(10),      
            inventory INT,  
            discount INT,   
            description TEXT (5000),    
            PRIMARY KEY (itemID)
        )"
	);
	
	// Makes autoincrements start at 1001
	$connection->query("ALTER TABLE products AUTO_INCREMENT=1001;");
	
	// Open csv file, "r" = read only
	$products = fopen($fileName, "r");
	$x = 0;
	
	while (($c = fgetcsv($products, 0, ",")) != FALSE) {
		$x++;
        $insertData = "INSERT INTO products (name,brand,category,price,inventory,discount, description) 
        VALUES ('$c[0]','$c[1]','$c[2]','$c[3]','$c[4]','$c[5]','$c[6]')";
        
        $result = mysqli_query($connection, $insertData);
        
        if (empty($result)) {
            echo "Problem in Importing CSV Data on Product #$x";
			echo "<br>";
			$success = false;
        }
    } 
	
	$connection->query("
		CREATE TABLE shoppingcart (
			itemID INT,
			quantity INT
		)
	");

	$connection->query("
		CREATE TABLE customers (
			customerID INT AUTO_INCREMENT,
			customerName VARCHAR(50),
			addressLine1 VARCHAR(100),
			addressLine2 VARCHAR(100),
			city VARCHAR (50),
			province VARCHAR(50),
			postalCode VARCHAR (10),
			telephoneNumber VARCHAR(10),
			PRIMARY KEY(customerID)
		)
	");

	$connection->query("
		CREATE TABLE orders (
			itemID SMALLINT,
			quantity SMALLINT,
			orderID INT,
			customerID INT
		)
	");
	
	if ($success) {
		echo "Database is set up properly!";
	} else {
		echo "The above errors appeared when setting up database.";
	}
	
	fclose($products);
	
	$connection->close();
?>