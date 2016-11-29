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
$sql = "Create table Franchise (franchise_id int, address varchar(256), PRIMARY KEY (franchise_id)); " .
	 "Create table FranchiseSchedule (franchise_id int, fs_day char(3), fs_start_hour time, fs_end_hour time,  FOREIGN KEY (franchise_id) REFERENCES Franchise(franchise_id), PRIMARY KEY (franchise_id, fs_day)); " .
	 "Create table Employee (employee_id int, fname varchar(25), lname varchar(25), wage int, franchise_id int, PRIMARY KEY (employee_id)); " .
	 "Create table EmployeeSchedule (employee_id int, es_day char(3), es_start_hour int, es_end_hour int, FOREIGN KEY (employee_id) REFERENCES Employee(employee_id), PRIMARY KEY (employee_id, es_day)); " .
	 "Create table Dish (dname varchar(64), price int, recipe varchar(256), calories int, PRIMARY KEY (dname)); " .
	 "Create table Allergens (dname varchar(64), aname varchar(64), FOREIGN KEY (dname) REFERENCES Dish(dname), PRIMARY KEY (dname, aname)); " .
	 "Create table Coupon (coupon_id int, dname varchar(64), cname varchar(64), details varchar(256), start_date date, end_date date, PRIMARY KEY (coupon_id)); " .
	 "Create table OfferedCoupons (coupon_id int, franchise_id int, FOREIGN KEY (coupon_id) REFERENCES Coupon(coupon_id), PRIMARY KEY (coupon_id, franchise_id)); " .
	 "Create table Menu (mname varchar(64), ms_start_hour time, ms_end_hour time, PRIMARY KEY (mname)); " .
	 "Create table MenuDays (mname varchar(64), day char(3), FOREIGN KEY (mname) REFERENCES Menu(mname), PRIMARY KEY (mname, day)); " .
	 "Create table MenuDishes (mname varchar(64), dname varchar(64), FOREIGN KEY (mname) REFERENCES Menu(mname), FOREIGN KEY (dname) REFERENCES Dish(dname), PRIMARY KEY (mname, dname));";

foreach (explode(";", $sql) as $query) {
	if (!mysql_query($query)) {
		die ("Query failed:" . mysql_error () );
	}
}

echo "Table created successfully";
mysql_close ();
?>
