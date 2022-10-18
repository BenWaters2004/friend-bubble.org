<?php
$encryptionKEY = "U09OTlk=";

//checks the username when signing up to make sure it only contains numbers and letters
function usernameINVALID($username) {
  $functionOUT;
  if (!preg_match("/^[a-zA-Z0-9 ]*$/", $username)) {
    $functionOUT = true;
  }
  else {
    $functionOUT = false;
  }
  return $functionOUT;
}
//checks that the password is of appropriate length
function badpassword($password) {
  $functionOUT;
  $length = strlen($password);
  if ($length < 8 or $length > 16) {
    $functionOUT = true;
  }
  else {
    $functionOUT = false;
  }
  return $functionOUT;
}
//checks that the two passwords entered are the same
function samePasswords($password, $password2) {
  $functionOUT;
  if ($password !== $password2) {
    $functionOUT = true;
  }
  else {
    $functionOUT = false;
  }
  return $functionOUT;
}
//checks is any of the inputs have been left blank
function noInputSignup($name, $username, $password, $password2) {
  $functionOUT;
  if (empty($name) or empty($username) or empty($password) or empty($password2)) {
    $functionOUT = true;
  }
  else {
    $functionOUT = false;
  }
  return $functionOUT;
}
//Creates a new user by inserting the new infomation into the database, also has some sql injection protection
function newuser($connection, $uname, $uusername, $upassword) {
  $functionOUT;
  $sql = "INSERT INTO users (UsersName, UsersUsername, UsersPassword) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($connection);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    $error = "SINVALID";
  }

  /*assigns a value to the password so hackers are unable to see them, rather than storing it in plain text
  these can not be converted back, however are at risk from hash collisions which are
  when more than one thing has the same hash value
  this is a built in function to php and is automatically updated by php when it is outdated*/
  $hashingPASSWORD = password_hash($upassword, PASSWORD_DEFAULT);

  mysqli_stmt_bind_param($stmt, "sss", $uname, $uusername, $hashingPASSWORD);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);

  //sets profile-img to default
  $sql = "SELECT * FROM users WHERE UsersUsername='$uusername'";
  $result = mysqli_query($connection, $sql);
  $row = mysqli_fetch_assoc($result);
  $userID = $row['UsersID'];
  $sql = "INSERT INTO profileimg (UserID, ImageSet) VALUES ('$userID', 0)";
  mysqli_query($connection, $sql);

  //sets background to default
  $sql = "INSERT INTO Background (UserID, colour) VALUES ('$userID', 'default')";
  mysqli_query($connection, $sql);

  //sets admin as friend
  $sql = "INSERT INTO friendslist (friend1, friend2, status) VALUES ('1', '$userID', 'friends')";
  mysqli_query($connection, $sql);

  //adds background default
  $sql = mysqli_query($connection, "INSERT INTO background (UserID, Colour) VALUES ('$userID', 'default')");

  header("location: http://friend-bubble.org/login.php?signup=true");
  exit();
}
//checks if any of the inputs are empty
function noInputLogin($username, $password) {
  $functionOUT;
  if (empty($username) or empty($password)) {
    $functionOUT = true;
  }
  else {
    $functionOUT = false;
  }
  return $functionOUT;
}
//logs in the user by first checking the username exists
function loginUSER ($connection, $uusername, $upassword) {
  $usernameEXIST = usernameEXIST($connection, $uusername);

  if ($usernameEXIST === false) {
    $error = "loginIncorrect";
  }
  else { //then making sure the password entered maches the hashed password stored
    $hashedPASSWORD = $usernameEXIST["UsersPassword"];
    $checkPmatch = password_verify($upassword, $hashedPASSWORD);
    if ($checkPmatch == false) {
      $error = "loginIncorrect";
    }
    else {
      session_start(); //then logs them in
      $_SESSION["UsersID"] = $usernameEXIST["UsersID"];
      $_SESSION["UsersUsername"] = $usernameEXIST["UsersUsername"];
      $_SESSION["UsersName"] = $usernameEXIST["UsersName"];

      $userID = $_SESSION["UsersID"];
      $sql = mysqli_query($connection, "SELECT * FROM background WHERE UserID='$userID'");
      $row = mysqli_fetch_assoc($sql);
      $_SESSION["colour"] = $row["Colour"];

      header("Location: http://friend-bubble.org/home.php");
      exit();
    }
  }
  if ($error == "loginIncorrect") { //error message in case they entered wrong infomation
    header("location: http://friend-bubble.org/login.php?login=incorrect");
    exit();
  }
}

//checks that the username is in the database, also has some sql injection protection
function usernameEXIST($connection, $uusername) {
  $sql = "SELECT * FROM users WHERE UsersUsername = ?;";
  $stmt = mysqli_stmt_init($connection);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("location: http://friend-bubble.org/login.php?login=error");
    exit();
  }
  else {
    mysqli_stmt_bind_param($stmt, "s", $uusername);
    mysqli_stmt_execute($stmt);

    $rdata = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($rdata)) {
      return $row;
    }
    else {
      $result = false;
      return $result;
    }
    mysqli_stmt_close($stmt);
  }
}

//sends messages
function sendmsg($connection, $fromUser, $TOuserID, $msg) {
  $sql = "INSERT INTO messages (fromUser, toUser, msg) VALUES (?, ?, ?);";
  $stmt = mysqli_stmt_init($connection);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    echo "<p class=error>There was an error!</p>";
  }

  mysqli_stmt_bind_param($stmt, "iis", $fromUser, $TOuserID, $msg);
  mysqli_stmt_execute($stmt);
  mysqli_stmt_close($stmt);
}

function oldPasswordright($connection, $userID, $upassword) {
  $sql = mysqli_query($connection, "SELECT * FROM users WHERE UsersID='$userID'");
  $row = mysqli_fetch_assoc($sql);
  $oldPass = $row['UsersPassword'];

  $hashingPASSWORD = password_hash($upassword, PASSWORD_DEFAULT);
  if ($hashingPASSWORD == $oldPass) {
    return TRUE;
  }
  else {
    return FALSE;
  }
}

function longname($x) {
  $length = strlen($x);
  if ($length > 30) {
    return true;
  }
}

function encryptMSG($originalMSG, $encryptionKEY) {
  $key = base64_decode($encryptionKEY);
  $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
  $encryptedMSG = openssl_encrypt($originalMSG, 'aes-256-cbc', $key, 0, $iv);
  return base64_encode($encryptedMSG . '::' . $iv);
}

function decryptMSG($encryptedMSG, $encryptionKEY) {
  $key = base64_decode($encryptionKEY);
  list($encryptedDATA, $iv) = array_pad(explode('::', base64_decode($encryptedMSG), 2),2,null);
  return openssl_decrypt($encryptedDATA, 'aes-256-cbc', $key, 0, $iv);
}