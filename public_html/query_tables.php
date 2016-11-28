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
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<title>Query Tables</title>
</head>
<body>
	<table>
		<tr>

			<td id="table-displayer" valign="top">
				<form>
					<h1>Query Tables</h1>
					<p> Choose a query to run: </p>

					<!--The database selctor dropdown-->
					<select name="_table">
					  <option value="find_allergens">Find allergens for dish</option>
					  <option value="find_wage">Find employee wage</option>
					</select>

					<br>
					<br>
					<input type="submit" value="Submit">
					<br>
					<br>
				</form>

				<form method="post" action=<?php echo '"?_table=' . $_GET["_table"] . '"'?>>
					<?php 
						if (!empty($_GET["_table"])) {

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
					
							// Write the heading 
							echo "<h3>Insert into the " . $_GET["_table"] . " table</h3>";

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

			<td id="table" valign="top">
				<?php if (!empty($_GET["_table"])) {

						// Write the heading
						echo "<h1>Displaying the " . $_GET["_table"] . "table</h1>";

						echo'<div display:table>';
						// Create the headers of the table
						echo '<table id="data-table"> <tr>';
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
							echo "<tr id='data-row'>";
							foreach ($table_description as $column) {
								echo "<td align='center'>" . $row[$column[0]] . "</td>";
							}
							echo "</tr>";
						}
						echo "</table>";

						echo "</div>";

						mysql_close();
					}
				?>
			</td>
		</tr>
	</table>
</body>


</html>