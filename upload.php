<?php
session_start();
include('DB_Connection.php');

if (isset($_POST['imgSubmit'])) {
  $file = $_FILES['file'];

  $fileName = $_FILES['file']['name'];
  $fileTmpName = $_FILES['file']['tmp_name'];
  $fileSize = $_FILES['file']['size'];#
  $fileError = $_FILES['file']['error'];
  $fileType = $_FILES['file']['type'];

  $userID = $_SESSION['UsersID'];

  $fileExt = explode('.', $fileName);
  $fileActualExt = strtolower(end($fileExt));

  $allowed = array('jpg', 'jpeg', 'png', 'pdf');

  if (in_array($fileActualExt, $allowed)) {
    if ($fileError === 0) {
      if ($fileSize < 700000){
        $fileNameNew = $userID . "." . $fileActualExt;
        $fileDestination = 'uploads/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $fileDestination);

        $sql = mysqli_query($connection, "UPDATE profileimg SET ImageSet=1 WHERE userID='$userID'");
        
        header("location: profile.php?uploadsuccess");
      }
      else {
        header("location: profile.php?error=filetoobig");
      }
    }
    else {
      header("location: profile.php?error=couldntupload");
    }
  }
  else {
    header("location: profile.php?error=wrongfiletype");
  }
}