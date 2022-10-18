<?php
//links to databases, html tags, stylings and functions page
session_start();
include('functions.php');
require_once 'DB_Connection.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") { //if form is submitted
  $uname = $_POST["uname"];
  $uusername = $_POST["uusername"];
  $upassword = $_POST["upassword"];
  $upasswordNew = $_POST["upasswordNew"];
  $upasswordNew2 = $_POST["upasswordNew2"];

  $usnChangeError = FALSE;
  $usunChangeError = FALSE;
  $uspChangeError = FALSE;

  $query = "SELECT * FROM users WHERE UsersUsername ='$uusername' ";
  $query2 = mysqli_query($connection, $query);

  $userID = $_SESSION["UsersID"]; 

  //Checks Name
  //checks if input is empty
  if (!empty($uname) === true) {
      //checks if name is ok
    if (usernameINVALID($uname) === true) {
      echo "<p class=error id=proset>Your name should only be letters!</p>";
      $usnChangeError = TRUE;
    }
    //checks if name is too long
    elseif (longname($uname) == true) {
      echo "<p class=error>Your name is too long!</p>";
      $usnChangeError = TRUE;
    }
    //If no problems changes Name
    elseif ($usnChangeError == FALSE){
      $sql = mysqli_query($connection, "UPDATE users SET UsersName='$uname' WHERE UsersID='$userID'");
      $_SESSION["UsersName"] = $uname;
    }
  }
  //Checks Username
  //checks if input is empty
  elseif (!empty($uusername) === true) {
    //checks if username is ok
    if (usernameINVALID($uusername) === true) {
      echo "<p class=error id=proset >Your username should only be numbers and letters!</p>";
      $usunChangeError = TRUE;
    }
    //checks if username is taken already
    if (mysqli_num_rows($query2) > 0) {
      echo "<p class=error id=proset>Sorry, but that username already exists!</p>";
      $usunChangeError = TRUE;
    }
    //checks if name is too long
    if (longname($uusername) == true) {
      echo "<p class=error>Your username is too long!</p>";
      $usunChangeError = TRUE;
    }
    //If no problems changes Name
    elseif ($usunChangeError == FALSE) {
      $sql = mysqli_query($connection, "UPDATE users SET UsersUsername='$uusername' WHERE UsersID='$userID'");
      $_SESSION["UsersUsername"] = $uusername;
    }
  }
  //Checks Password
  //checks if inputs are empty
  elseif ((!empty($upassword) === true) or (!empty($upasswordNew) === true) or (!empty($upasswordNew2) === true)) {
    //checks all password sections are filled in
    if ((empty($upassword) === true) or (empty($upasswordNew) === true) or (empty($upasswordNew2) === true)) {
      echo "<p class=error id=proset>Please fill in all password sections!</p>";
      $uspChangeError = TRUE;
    }
    //checks that the password is an appropriate length
    elseif (badpassword($upasswordNew) === true) {
      echo "<p class=error id=proset>Your new password should be 8 - 16 characters long!</p>";
      $uspChangeError = FALSE;
    }
    //checks if the passwords entered match
    elseif (samePasswords($upasswordNew, $upasswordNew2) === true) {
      echo "<p class=error id=proset>Your new passwords didn't match!</p>";
      $uspChangeError = FALSE;
    }
    //checks if your old passport matches
    elseif (oldPasswordright($connection, $userID, $upassword) == FALSE) {
      
      $uspChangeError = FALSE;
    }
    //checks if input is empty
    elseif (!empty($upassword) === true) {
      $hashingPASSWORD = password_hash($upasswordNew, PASSWORD_DEFAULT);
      $sql = mysqli_query($connection, "UPDATE users SET UsersPassword='$hashingPASSWORD' WHERE UsersID='$userID'");
    }
  }
  //sends you back
  if ($usnChangeError = FALSE AND $usunChangeError = FALSE AND $uspChangeError = FALSE) {
    header("Location: /profile.php?changeSuccess");
  }
  
}

include_once 'header.php';

?>
  <title>Friend Bubble │ Profile</title>
</head>

<body class="bstyle" style="background-color: rgb(13, 31, 42)"> <!--sets different background image-->
<div class="profback">
<a class="back" href="home.php"><--Go back!</a> <!--back button-->
<h2 class="proftitle">Profile Page</h2> <!--title-->
</div>
<?php

  if (isset($_SESSION["UsersID"])) { //checks if user is logged in
    $userID = $_SESSION["UsersID"]; 
    //Modal colour select
    if (isset($_GET['colour'])) { //gets variable from url
      $colour = $_GET['colour'];
      $sql = mysqli_query($connection, "UPDATE background SET Colour='$colour' WHERE UserID='$userID'"); //updates database with new choice so that it saves when you next log in
      
      if ($colour == "change") { //if they are changing colour displays modal
        ?>
        <div class="modal-dim"> <!--dims the background-->
          <div class="modal-content"> <!--displays modal-->
            <h2>Change your background colour?</h2>
            <a class="close" href="profile.php">+</a> <!--close button, using + for more accruate cross than x-->
            <p>~Note: The background is not visible on mobile devices or smaller devices!</p>
            <div class="colourList">
              <!--colour options-->
              <a class="red" href="profile.php?colour=red">red</a>
              <a class="blue" href="profile.php?colour=blue">blue</a>
              <a class="aqua" href="profile.php?colour=aqua">aqua</a>
              <a class="yellow" href="profile.php?colour=yellow">yellow</a>
              <a class="pink" href="profile.php?colour=pink">pink</a>
              <a class="default" href="profile.php?colour=default">default</a>
          </div>
          </div>
        </div>
    <?php
      }
      else {
        $_SESSION["colour"] = $colour; //updates new variable
      }
    }


    echo "<p class='namehello'>Hello " . $_SESSION["UsersName"] . "</p>"; //description
    echo "<p class='parhello'>This is your profile page. Here you can change your account details, if you want to change anything else message ~~~ADMIN~~~ and we may be able to add that feature.</p>";


    $userID = $_SESSION["UsersID"];
    $sqlImg =  mysqli_query($connection,"SELECT * FROM profileimg WHERE userID='$userID'");
    $rowImg = mysqli_fetch_assoc($sqlImg) ?>

    <section class="profileP"> <!--change username and password-->
      <P class="account">My account</p>
      <div><br>
        <form id="margLeft" class="editAccount" method="post">
          <div class="pppp">
            <P>Change your Full Name? or Change your Username?</p>
            <input type="text" name="uname" placeholder="<?=$_SESSION["UsersName"]?>"> <!--displays current name as placeholder-->
            <input type="text" name="uusername" placeholder="<?=$_SESSION["UsersUsername"]?>"><br> <!--displays current name as placeholder-->
            <button class="usersub" type="submit" name="submit">Submit</button>
          </div><br>
          <P>Change your Password?</p>
          <input type="password" name="upassword" placeholder="Enter your old password..."> <!--to verify it is you-->
          <input type="password" name="upasswordNew" placeholder="Enter your new password...">
          <input type="password" name="upasswordNew2" placeholder="Repeat your new password..."><br> <!--repeat password to prevent typos-->
          <button class="usersub" type="submit" name="submit">Submit</button>
        </form>
      </div>
    </section>

    <!--displays profile picture-->
    <div class='profpics'> <?php
      if ($rowImg['ImageSet'] == 1) { 
        echo "<img src='uploads/".$userID.".jpg'>";
      }
      else {
        echo "<img src='uploads/defaultprofile.jpg'>";
      } ?>
    </div>
    
    <!--allows you to change profile picture or background colour-->
    <section class="profileP2">
      <div class = "sideprofile">
        <p class="account">My Prefrences</p> <br>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <P>Change your profile picture?</p> 
            <input id="fileSelect" type="file" name="file">
            <button type="submit" name="imgSubmit">Upload</button>
        </form><br>

        <P>Change your background colour?</p><br>
        <a class="bgColour" href="profile.php?colour=change"><span>Change Colour</span></a> <!--opens modal-->

      </div>
      <!--Profile Picture errors--> <?php
        if (isset($_GET['error'])) {
          $error = $_GET;
          if ($error = "wrongfiletype") {
            echo "<P class='error' id=proset>You cannot upload files of that type!";
            exit();
          }
          elseif ($error = "couldntupload") {
            echo "<P class='error' id=proset>There was a problem uploading your image!";
            exit();
          }
          elseif ($error = "filetoobig") {
            echo "<P class='error' id=proset>Your file is too big!";
            exit();
          }
        }
        ?>
    </section>

    <!--CEOP button, allows young users to report online sexual abuse to the police as an added saftey messure-->
    <div class="ceop2">
      <a href="https://www.ceop.police.uk/Safety-Centre/" target= "_blank"><img class="ceop" src="img/ceop.png" alt="CEOP"></a>
    </div>
  </body>
  </html>
  <?php
  }
  //error message for non logged in users
  else {
    ?><p class="error">You should not be on this page <a href="index.php">Click here to go back!</a></p><?php
  }
?>