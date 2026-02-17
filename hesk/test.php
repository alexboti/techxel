<?php
$mysqli_connection = new MySQLi('localhost', 'test', 'QaW6p5gky3d]', 'hesk');
if ($mysqli_connection->connect_error) {
   echo "Not connected, error: " . $mysqli_connection->connect_error;
}
else {
   echo "Connected.";
}