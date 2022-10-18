<?php
//Connects to database
$severname = "localhost";
$dataBusername = "frienble_user1";
$dataBpassword = "y8H*$9cT^7wR";
$dataBname = "frienble_Friend Bubble";
$connection = mysqli_connect($severname, $dataBusername, $dataBpassword, $dataBname);

if (!$connection) {
  die("Connection failed: " . mysqli_connect_error());
}
