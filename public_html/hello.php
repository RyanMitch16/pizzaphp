<?php
echo "My first PHP script!";

$servername = "cs-sql2014.ua-net.ua.edu";
$username = "rmitchell";
$password = "11557275";

// Create connection
$conn = mysql_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
} 
echo "Connected successfully";

?>