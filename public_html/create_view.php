<?php
$servername = "cs-sql2014.ua-net.ua.edu";
$username = "rmitchell";
$password = "11557275";

// Create connection
$conn = mysql_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysql_error());
} 

// Choose the database
$db_selected = mysql_select_db($username, $conn);
if (!$db_selected) {
	die ("Database selection failed: " . mysql_error());
}

// Create all the tables for the database
$sql = "CREATE VIEW Dish_Allergens AS SELECT dish.dname, price, calories, aname FROM Dish, Allergens WHERE allergens.dname=dish.dname;";

foreach (explode(";", $sql) as $query) {
	if (!mysql_query($query)) {
		die ("Query failed:" . mysql_error () );
	}
}

echo "Table created successfully";
mysql_close ();
?>
