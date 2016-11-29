
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
?>
<html>
<head>
	<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="stylesheet.css">
	<title>Query Tables</title>
</head>
<body>
	<!--Navigation bar-->
	<ul>
  		<li style="font-weight: bold; font-size: 18px;">Pizza PHP</li>
  		<li><a href="display_tables.php">Display and Insert</a></li>
  		<li><a href="allergen.php">Find Allergens</a></li>
  		<li><a href="franchise_hours.php">Franchise Hours</a></li>
  		<li style="float:right">Elizabeth Conrad (ecconrad1@crimson.ua.edu)</li>
  		<li style="float:right">Ryan Mitchell (rtmitchell2@crimson.ua.edu)</li>
	</ul>
	<table class="TableDisplayerBody">
		<tr>

			<td class="LeftColumn" valign="top">
				<!--Find hours and address of certain franchise-->
				<form class="QueryForm" method="get">
					<?php 	
							$allergens= array();
							$result = mysql_query("SELECT DISTINCT aname FROM Allergens");
							if (!$result) {
								die ("Query failed:" . mysql_error ());
							}

							while ($allergen = mysql_fetch_array($result)) {
								array_push($allergens, $allergen);
							}
							// Write the heading 
							echo "<h3>Find Franchises Open During Range</h3>";
							echo "<p>(At least one field must filled out)</p>";

							// Create the inputs for each column of the table

							echo 'Day:<br><input type="text" name="_day"><br>';
							echo '<br>Opening time:<br><input type="time" name="_start"><br>';
							echo '<br>Closing time:<br><input type="time" name="_end"><br>';

							// Create the submit button
							echo '<br><br><input style="margin-right:20px;" type="submit" value="Query">';

					?>
				</form>
			</td>


			<td class="RightColumn" valign="top">
				<form method="get">
					<?php if (!empty($_GET["_start"]) || !empty($_GET["_end"]) || !empty($_GET["_day"])) {

							//Array that holds the column names of the view
							$query_columns = array();
							$query = "SELECT DISTINCT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Franchise' OR TABLE_NAME = 'FranchiseSchedule'";
							$franchise_result = mysql_query($query);
							if (!$franchise_result) {
								die ("Query failed:" . mysql_error () );
							}

							// Store the table data into an array
							while ($column = mysql_fetch_array($franchise_result)) {
								array_push($query_columns, $column);
							}
														
							// Write the heading
							echo "<h1>Displaying the Franchise Hours</h1>";

							echo'<div display:table>';
							// Create the headers of the table
							echo '<table class="DataTable"> <tr>';
							foreach ($query_columns as $column) {
								echo "<th>" . $column[0] . "</th>";
							}
							echo "</tr>";

							// Get the data from the sql query
							$query = "SELECT * FROM Franchise, FranchiseSchedule WHERE Franchise.franchise_id=FranchiseSchedule.franchise_id";
							if($_GET["_day"]) {
								echo $_GET["_day"];
								$query .= ' AND fs_day="' . $_GET["_day"] . '"';
							}
							if($_GET["_start"] && $_GET["_end"]) {
								$query .= " AND fs_start_hour >= '" . $_GET["_start"] . "' AND fs_end_hour <= '" . $_GET["_end"] . "'";
							} else if($_GET["_start"]) {
								$query .= " AND fs_start_hour >= '" . $_GET["_start"] . "'";
							} else if($_GET["_end"]) {
								$query = " AND fs_end_hour =< '" . $_GET["_end"] . "'";
							}
							$query .= ";";

							$query_result = mysql_query($query);
							if (!$query_result) {
								die ("Query failed:" . mysql_error());
							}

							// Enter the data into the table 
							while ($row = mysql_fetch_array($query_result)) {
								echo '<tr class="DataTableRow">';
								foreach ($query_columns as $column) {
									echo "<td align='center'>" . $row[$column[0]] . "</td>";
								}
								echo "</tr>";
							}
							echo "</table>";

							echo "</div>";

							mysql_close();
						}
					?>

				</form>
			</td>
		</tr>
	</table>
</body>


</html>