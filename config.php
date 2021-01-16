<?php

$servername = "localhost";
$username = "anshul";
$password = "777111";
$database = "project";

$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
  die("ERROR: ".$mysqli->connect_error);
}


 ?>
