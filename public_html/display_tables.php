<?php
// Connect to the SQL database
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

// Get the structure of the currently viewed table
$table_description = array();
if (!empty($_GET["_table"])) {
	
	$result = mysql_query("DESCRIBE " . $_GET["_table"]);
	if (!$result) {
		die ("Query failed:" . mysql_error () );
	}

	// Store the table data into an array
	while ($column = mysql_fetch_array($result)) {
		array_push($table_description, $column);
	}
}
?>

<html lang="en">
<head>
	<!-- Link the page styling -->
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<title>Table Displayer</title>
</head>
<body>
	<table class="TableDisplayerBody">
		<tr>
			<td class="LeftColumn" valign="top">
				<!-- Allows the user to change what table is being viewed -->
				<form>
					<h1>Table Displayer</h1>
					<p> Choose a table to be displayed: </p>

					<!--The database selctor dropdown-->
					<select name="_table">
					  <option value="Franchise">Franchise</option>
					  <option value="FranchiseSchedule">FranchiseSchedule</option>
					  <option value="Employee">Employee</option>
					  <option value="EmployeeSchedule">EmployeeSchedule</option>
					  <option value="Dish">Dish</option>
					  <option value="Allergens">Allergens</option>
					  <option value="Coupon">Coupon</option>
					  <option value="OfferedCoupons">OfferedCoupons</option>
					  <option value="Menu">Menu</option>
					  <option value="MenuDays">MenuDays</option>
					  <option value="MenuDishes">MenuDishes</option>
					</select>

					<br><br> <input type="submit" value="Submit">
				</form>

				<!-- Allows the user to insert values into the table -->
				<form method="post">
					<?php  
					if (!empty($_GET["_table"])) {

						// Write the heading 
						echo "<h3>Insert into the " . $_GET["_table"] . " table</h3>";

						// Check if a value was inserted into the table
						if (!empty($_POST["_inserted"])) {
							$sql_values = "";
							$sql_columns = "";

							// Concat the submitted values of each colum into the insert query
							$isFirst = TRUE;
							foreach ($table_description as $column) {
								if ($isFirst) {
									$isFirst = FALSE;
								} else {
									$sql_values .= ", ";
									$sql_columns .= ", ";
								}

								$sql_columns .= $column[0];

								// Add quotes around non numerical values
								if (strpos($column[1], "int") !== false
									|| strpos($column[1], "float") !== false
									|| strpos($column[1], "double") !== false
									|| strpos($column[1], "decimal") !== false) {

									$sql_values .= $_POST[$column[0]];
								} else {
									$sql_values .= "'" . $_POST[$column[0]] . "'";
								}
							}

							// Insert the entry into the table
							$insert_result = mysql_query("INSERT INTO " 
								. $_GET["_table"] 
								. " ( $sql_columns ) VALUES ( $sql_values )");
							if (!$insert_result) {
								echo '<div style="background-color: #FF815E; padding: 3px;">Entry input failed:' . mysql_error() . '</div><br>';
							} else {
								// Report a successful insertion
								echo '<div style="background-color: #D1F6BD; padding: 3px;">Entry inputted successfully</div><br>';
							}
						}

						// Create the inputs for each column of the table
						foreach ($table_description as $column) {
							if ($column[1] == "date") {
								echo $column[0] . ':<br><input type="date" name="' . $column[0] . '"><br>';
							} else {
								echo $column[0] . ':<br><input type="text" name="' . $column[0] . '"><br>';
							}
						}

						// Make sure the table param is kept when submitted
						echo '<input type="hidden" name="_inserted" value="true">';

						// Create the submit button
						echo '<br><input type="submit" value="Insert">';
					}
					?>
				</form>
			</td>

			<!-- Display the selected table -->
			<td class="RightColumn" valign="top">
				<div style="height: calc(100% - 120px)">
					<?php

					if (!empty($_GET["_select"]) && !empty($_GET["_table"])) {

						// Build the SQL query
						$query = "SELECT " . $_GET["_select"] . " FROM " . $_GET["_table"];
						if (!empty($_GET["_where"])) {
							$query .= " WHERE " . $_GET["_where"];
						}

						$result = mysql_query($query);
						if (!$result) {
							die("Query failed:" . mysql_error());
						}

						// Write the heading
						echo "<h3>Displaying the result from the query: " . $query . " </h3>";	

						// Create the headers of the table
						echo '<table class="DataTable"> <tr>';
						foreach ($table_description as $column) {
							echo "<th>" . $column[0] . "</th>";
						}
						echo "</tr>";

						// Enter the data into the table 
						while ($row = mysql_fetch_array($result)) {
							echo "<tr class='DataTableRow'>";
							foreach ($table_description as $column) {
								echo "<td>" . $row[$column[0]] . "</td>";
							}
							echo "</tr>";
						}

						echo "</table></div>";

					} else if (!empty($_GET["_table"])) {

						// Write the heading
						echo "<h1>Displaying the " . $_GET["_table"] . " table</h1>";

						// Create the headers of the table
						echo '<table class="DataTable"> <tr>';
						foreach ($table_description as $column) {
							echo "<th>" . $column[0] . "</th>";
						}
						echo "</tr>";

						// Get the data from the sql query
						$result = mysql_query("SELECT * FROM " . $_GET["_table"]);
						if (!$result) {
							die("Query failed:" . mysql_error());
						}

						// Enter the data into the table 
						while ($row = mysql_fetch_array($result)) {
							echo "<tr class='DataTableRow'>";
							foreach ($table_description as $column) {
								echo "<td>" . $row[$column[0]] . "</td>";
							}
							echo "</tr>";
						}

						echo "</table></div>";
					}
					?>
				</div>
				<div style="background-color: #FFFFFF; height: 120px; padding: 10px;">
					<form>
						<p> Enter an SQL query to execute on the database: </p>
						<div>
							<!--<div style="float: left" style="padding:0; margin:0;">-->
							SELECT <input type="text" name="_select" style="width: 20%; "></textarea>
							FROM <select name="_table" >
								  <option value="Franchise">Franchise</option>
								  <option value="FranchiseSchedule">FranchiseSchedule</option>
								  <option value="Employee">Employee</option>
								  <option value="EmployeeSchedule">EmployeeSchedule</option>
								  <option value="Dish">Dish</option>
								  <option value="Allergens">Allergens</option>
								  <option value="Coupon">Coupon</option>
								  <option value="OfferedCoupons">OfferedCoupons</option>
								  <option value="Menu">Menu</option>
								  <option value="MenuDays">MenuDays</option>
								  <option value="MenuDishes">MenuDishes</option>
								</select>
							<br>
							WHERE <input type="text" name="_where" style="width:50%";"></textarea>
						</div>
						<br><input type="submit" value="Submit">
					</form>
				</div>
			</td>
		</tr>
	</table>
</body>
</html>
<?php mysql_close(); ?>