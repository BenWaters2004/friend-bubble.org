<?php
  //links to databases, html tags, stylings and functions page
  session_start();
  include('DB_Connection.php');
  include('functions.php');
  /*Checks if they are logged in already*/
  if (isset($_SESSION["UsersID"])) {
    header("Location: home.php");
  }

  //When form is submitted it sets the inputs as variables for functions
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $uname = $_POST["uname"];
    $uusername = $_POST["uusername"];
    $upassword = $_POST["upassword"];
    $upassword2 = $_POST["upassword2"];

    //Input validation - signup form

    //checks if username is ok
    if (usernameINVALID($uusername) === true) {
      echo "<p class=error>Your username should only be numbers and letters!</p>";
    }
    //checks if username is taken already
    $query = "SELECT * FROM users WHERE UsersUsername ='$uusername' ";
    $query2 = mysqli_query($connection, $query);
    if (mysqli_num_rows($query2) > 0) {
      echo "<p class=error>Sorry, but that username already exists!</p>";
    }
    //checks if any inputs are empty
    elseif (noInputSignup($uname, $uusername, $upassword, $upassword2) === true) {
      echo "<p class=error>Please fill in all sections!</p>";
    }
    //checks that the password is an appropriate length
    elseif (badpassword($upassword) === true) {
      echo "<p class=error>Your password should be 8 - 16 characters long!</p>";
    }
    //checks if the passwords entered match
    elseif (samePasswords($upassword, $upassword2) === true) {
      echo "<p class=error>Your passwords didn't match!</p>";
    }
    //checks length of full name
    elseif (longname($uname) == true) {
      echo "<p class=error>Your name is too long!</p>";
    }
    //checks length of username
    elseif (longname($uusername) == true) {
      echo "<p class=error>Your username is too long!</p>";
    }
    else {
      //adds user to the database
      newuser($connection, $uname, $uusername, $upassword);
    }
  } 

  include_once ('header.php');

  ?>
  <title>Friend Bubble │ Sign Up</title>
</head>
<body>
  <!--Bubble Animation-->
  <div class="bubbles">
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
  </div>

    <!--Back button-->
  <a class="back" href="index.php"><--Go back!</a>

    <!--learn more button-->
  <div class="learn"><li><a href="learn-more.php">Learn More</a></li></div>
  <section class="signup">

    <!--title-->
    <h2>Sign Up</h2>
    <div>

      <!--sign up form-->
      <form method="post">
        <input type="text" name="uname" placeholder="Enter your Full name...">
        <input type="text" name="uusername" placeholder="Enter a username...">
        <input type="password" name="upassword" placeholder="Enter a password...">
        <input type="password" name="upassword2" placeholder="repeat your password...">
        <button type="submit" name="submit">Sign Up</button>
        <a id="already" href="login.php">Already signed up?</a><br></br> <!--option to go to login in page-->
      </form>
    </div>
  </section>
  </body>
</html>