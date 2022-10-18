<?php
  //links to databases, html tags, stylings and functions page
  session_start();
  include('DB_Connection.php');
  include('functions.php');

  //When form is submitted it sets the inputs as variables for functions
  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $_SESSION['uusername'] = $_POST["uusername"];
    $_SESSION['upassword'] = $_POST["upassword"];
    
    if (isset($_SESSION["UsersID"])) {
      header("Location: http://friend-bubble.org/home.php");
      exit();
    }
    else {
      if (isset($_SESSION["uusername"]) and isset($_SESSION["upassword"])) {
        $uusername = $_SESSION["uusername"];
        $upassword = $_SESSION["upassword"];
  
        //Input validation - login form
        if (noInputLogin($uusername, $upassword) === true) {
          header("Location: http://friend-bubble.org/login.php?login=notcomplete");
          exit();
        }
        else { //logs user in
          loginUSER ($connection, $uusername, $upassword);
        }
      }
    }
  }

  if (isset($_GET['login'])) {
    $problem = $_GET['login'];
    if ($problem == "incorrect") {
      ?><p class="error">Your login infomation was inncorrect!</p><?php
    }
    else if ($problem == "notcomplete") {
      ?><p class="error">Please fill in all infomation!</p><?php
    }
    else if ($problem == "error") {
      ?><p class="error">There was a problem</p><?php
    }
    else {
      header("location: login.php");
      exit();
    }
  }
  
  /*Checks if they are logged in already*/
  if (isset($_SESSION["UsersID"])) {
    header("Location: home.php");
  }

  include_once ('header.php');
?>

  <title>Friend Bubble │ Login</title>
</head>
<body><?php

  if (isset($_GET['signup'])) {
    $newUser = $_GET['signup'];
    if ($newUser == "true") {
      ?>
      <div class="modal-dim"> <!--dims the background-->
        <div class="modal-content" id="TandC"> <!--displays modal-->
          <h2>Welcome new user</h2>
          <p>After reading and agreeing to these terms and conditions, you can login for the first time!</p><br>
          <p>You must be over the age of 16 or over 13 with parental consent. Be midful of what you are sending and who too, espesialy if you have never met the person in real life.
            Do not meet up with anyone you don't know, remember people might not be who they say they are!
            Whilst using this website you agree to treat other users with respect and to respect their privacy.
            Bullying or harassment will not be tolerated and these actions will have your account removed and in extream circumstances further action will be taken.
            This website is not for advetising or scamming so both of these actions will result in the removal of your account, same goes for impersonating an Admin.
            If you find an exploit/problem or security floor with the website, DO NOT abuse it and instead report it to us.
            We are not liable for any criminal damage (for example: being scammed) but will help prevent this from taking place.
          </p><br>
          <p>
            With that out of the way, thank you so much and by signing up you are helping to further my Computer Science A-level project!
          </p><br>
          
          <a href="login.php" class="agree">I Agree</a>
        </div>
      </div>
      <?php
    }
  }
  
  ?>
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
    
    <a class="back" href="index.php"><--Go back!</a>
    <!--learn more button-->
    <div class="learn"><li><a href="learn-more.php">Learn More</a></li></div>
    <section class="signup">
      <!--title-->
      <h2>Login</h2>
      <div>
        <!--Login form-->
        <form method="post">
          <input type="text" name="uusername" placeholder="Enter your username...">
          <input type="password" name="upassword" placeholder="Enter your password...">
          <button type="submit" name="submit" href="/login.php">Login</button>
          <a id="already" href="signup.php">Don't have an account?</a><br></br> <!--option to go to sign up page-->
        </form>
      </div>
    </section>
    </body>
  </html>