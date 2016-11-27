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
$sql = "Create table Franchise (franchise_id int, address varchar(256)); " .
	 "Create table FranchiseSchedule (franchise_id int, fs_day char(3), fs_start_hour int, fs_end_hour int); " .
	 "Create table Employee (employee_id int, fname varchar(25), lname varchar(25), wage int, franchise_id int); " .
	 "Create table EmployeeSchedule (employee_id int, es_day char(3), es_start_hour int, es_end_hour int); " .
	 "Create table Dish (dname varchar(64), price int, recipe int, calories int); " .
	 "Create table Allergens (dname varchar(64), aname varchar(64)); " .
	 "Create table Coupon (coupon_id int, dname varchar(64), cname varchar(64), details varchar(256), start_date date, end_date date); " .
	 "Create table OfferedCoupons (coupon_id int, franchise_id int); " .
	 "Create table Menu (mname varchar(64), ms_start_hour int, ms_end_hour int); " .
	 "Create table MenuDays (mname varchar(64), day char(3)); " .
	 "Create table MenuDishes (mname varchar(64), dname varchar(64))";

foreach (explode(";", $sql) as $query) {
	if (!mysql_query($query)) {
		die ("Query failed:" . mysql_error () );
	}
}

echo "Table created successfully";
mysql_close ();
?>
