<?php
session_start();
require_once 'DB_Connection.php';
require 'functions.php';

$TOuserID = $_SESSION['TOuserID'];
$fromUser = $_SESSION["UsersID"]; ?>

<?php

$chats = mysqli_query($connection, "SELECT * FROM messages WHERE (toUser = $TOuserID AND fromUser = $fromUser) OR (toUser = $fromUser AND fromUser = $TOuserID)")
  or die("Failed to connect to database");

while ($chat = mysqli_fetch_assoc($chats)) {
  $msg = $chat['msg'];
  $chatFrom = $chat['fromUser'];
  $sql = "SELECT UsersUsername FROM users WHERE UsersID = $chatFrom";
  $result = $connection->query($sql);
  $chatFrom = $result->fetch_assoc();

  $msg = decryptMSG($msg, $encryptionKEY);

  if ($chat['fromUser'] == $TOuserID) {
    echo "<div class='msgBubble'>";
    echo "<p class=recieved id=fromNAME>$chatFrom[UsersUsername]</p>";
    echo "<p class=msg>$msg</p><br>";
    echo "</div>";
  }
  else {
    echo "<div class='msgBubble'>";
    echo "<p class=sent id=fromNAME>$chatFrom[UsersUsername]</p>";
    echo "<p class=msg>$msg</p><br>";
    echo "</div>";
  }
} ?>