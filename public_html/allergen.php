
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
				<!--Select which allergens are being queried-->
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
							echo "<h3>Find Dishes with Specific Allergen</h3>";

							// Create the inputs for each column of the table

							echo '<select name="_allergen">';
							echo isset($_GET["_allergen"]);
							foreach ($allergens as $allergen) {
								if($_GET["_allergen"] && $_GET["_allergen"] == urlencode($allergen[0])) {
									echo '<option value=' . urlencode($allergen[0]) .  ' selected="selected">'  . $allergen[0] . '</option>';
								} else {
									echo '<option value=' . urlencode($allergen[0]) . '>' . $allergen[0] . '</option>';
								}
								
							}
							echo "</select><br>";

							//Add checkbox to alter whether the allergen should appear in the dish or not
							if($_GET["_exclude"]) {
								echo '<br><label><input type="checkbox" name="_exclude" checked="on">Exclude allergen</label>';
							} else {
								echo '<br><label><input type="checkbox" name="_exclude">Exclude allergen</label>';
							}

							// Create the submit button
							echo '<br><br><input style="margin-right:20px;" type="submit" value="Query">';

							echo'<br><br><p>The allergen dropdown only contains unique allergens that exist in the allergen table. If a new allergen is added or an allergen is removed, the dropdown will be updated.</p>';
					?>
				</form>
			</td>


			<td class="RightColumn" valign="top">
				<form method="get">
					<!--ALLERGEN QUERY-->
					<?php if (!empty($_GET["_allergen"])) {

							//Array that holds the column names of the view
							$view_description = array();
							$view_result = mysql_query("DESCRIBE Dish_Allergens");
							if (!$view_result) {
								die ("Query failed:" . mysql_error () );
							}

							// Store the table data into an array
							while ($column = mysql_fetch_array($view_result)) {
								array_push($view_description, $column);
							}
														
							// Write the heading
							echo "<h1>Displaying the Dish_Allergens View</h1>";

							echo'<div display:table>';
							// Create the headers of the table
							echo '<table class="DataTable"> <tr>';
							foreach ($view_description as $column) {
								echo "<th>" . $column[0] . "</th>";
							}
							echo "</tr>";

							// Get the data from the sql query
							if($_GET["_exclude"]) {
								$query = 'SELECT * FROM Dish_Allergens WHERE dname NOT IN (SELECT dname FROM Dish_Allergens WHERE aname="' . urldecode($_GET["_allergen"]) . '");';
							} else {
								$query = 'SELECT * FROM Dish_Allergens WHERE aname="' . urldecode($_GET["_allergen"]) . '";';
							}
							$query_result = mysql_query($query);
							if (!$query_result) {
								die ("Query failed:" . mysql_error());
							}

							// Enter the data into the table 
							while ($row = mysql_fetch_array($query_result)) {
								echo '<tr class="DataTableRow">';
								foreach ($view_description as $column) {
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
	<div class="container">
  		<div class="bottomleft">This query is run using the "Dish_Allergens" view which is a view containing all the information from the distinct columns of the Dish and Allergens tables.</div>
	</div>
</body>


</html>