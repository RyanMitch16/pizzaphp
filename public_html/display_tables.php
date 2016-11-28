<html>
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

		while ($column = mysql_fetch_array($result)) {
			array_push($table_description, $column);
		}
	}
?>
<head>
	<title>Table Displayer</title>
</head>
<body>
	<table style="height: 100%; margin: 0; padding: 0; width:100%;">
		<tr style="margin: 0; padding: 0;">

			<td style="background-color: #bcd6ff; padding: 30px; width:30%;" valign="top">
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

					<br>
					<input type="submit" value="Submit">
				</form>

				<form method="post" action=<?php echo '"?_table=' . $_GET["_table"] . '"'?>>
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

									if (strpos($column[1], "int") !== false
										|| strpos($column[1], "float") !== false
										|| strpos($column[1], "double") !== false
										|| strpos($column[1], "decimal") !== false) {

										$sql_values .= $_POST[$column[0]];
									} else {
										$sql_values .= "'" . $_POST[$column[0]] . "'";
									}
								}

								// Run the query
								$insert_result = mysql_query("INSERT INTO " 
									. $_GET["_table"] 
									. " ( $sql_columns ) VALUES ( $sql_values )");

								if (!$insert_result) {
									die ("Query failed:" . mysql_error());
								}

								echo '<div style="background-color: green">Entry inputted successfully</div>';
							}

							// Create the inputs for each column of the table
							foreach ($table_description as $column) {
								if ($row[1] == "date") {
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

			<td style="background-color: #eaeaea; padding: 30px; width:70%;" valign="top">
				<?php if (!empty($_GET["_table"])) {

						// Write the heading
						echo "<h1>Displaying the " . $_GET["_table"] . " table</h1>";

						// Create the headers of the table
						echo '<table style="width:100%";> <tr>';
						foreach ($table_description as $column) {
							echo "<th>" . $column[0] . "</th>";
						}
						echo "</tr>";

						// Get the data from the sql query
						$result = mysql_query("SELECT * FROM " . $_GET["_table"]);
						if (!result2) {
							die ("Query failed:" . mysql_error());
						}

						// Enter the data into the table 
						while ($row = mysql_fetch_array($result)) {
							echo "<tr>";
							foreach ($table_description as $column) {
								echo "<td>" . $row[$column[0]] . "</td>";
							}
							echo "</tr>";
						}
						echo "</table>";

						mysql_close();
					}
				?>
			</td>
		</tr>
	</table>
</body>


</html>