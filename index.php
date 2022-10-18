<?php
  //basic html tags and links to stylesheets ect
  session_start();
  include_once 'header.php';
?>

    <title>Friend Bubble</title>
  </head>

  <body>
    <?php
      /*Checks if they are logged in already*/
      if (isset($_SESSION["UsersID"])) {
        header("Location: home.php");
      }
    ?>
    <!--learn more button-->
    <div class="learn">
      <li><a href="learn-more.php">Learn More</a></li>
    </div>
    <!--bubble animations-->
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
    
    <div class=indexBox>

      <!--title-->
      <h4 class="indexwelcome">Welcome to,</h4>
      <h1>Friend Bubble</h1>

      <!--Login and signup buttons-->
      <nav class="indexNAV">
        <ul>
          <li><a href="login.php">Log In</a></li>
          <li><a href="signup.php">Sign Up</a></li>
        </ul>
      </nav>
    </div>
  </body>
<!--
Hello, you shouldn't be looking at my code but heres a picture of a whale

┊┊┊┊┊╭╭╭╮╮╮┊┊┊┊ 
┊┊┊┊┊╰╰╲╱╯╯┊┊┊┊ 
┊┏╮╭┓╭━━━━━━╮┊┊ 
┊╰╮╭╯┃┈┈┈┈┈┈┃┊┊ 
┊┊┃╰━╯┈┈╰╯┈┈┃┊┊ 
┊┊┃┈┈┈┈┈┈┈╰━┫┊┊ 
╲╱╲╱╲╱╲╱╲╱╲╱╲╱╲

    -->
</html>