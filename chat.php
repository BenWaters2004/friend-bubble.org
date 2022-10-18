<?php

  //this page is so that the code isn't repeated over several pages, making it more effcient
  session_start();
  require_once 'DB_Connection.php';
  require_once 'functions.php';
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Friend Bubble │ Chat</title>

    <!--Adds tab icon-->
    <link rel="icon" href="img/BubbleIcon.png">

    <!--links to fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fjalla+One&family=Ubuntu:wght@500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Amiri&family=Bubblegum+Sans&display=swap" rel="stylesheet">

    <!--Links to style sheets-->
    <link rel="stylesheet" href="resetstyle.css">
    <link rel="stylesheet" href="style.css">

    <!--Links to jquery-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Jquery script to re-load the chat page every second-->
    <script>
      $(document).ready(function()
      {
        setInterval(function() {
          $("#refresh").load("load.php");
          refresh();
        },250);
      });
    </script>
  </head>
  <body>
  <div class="bubbles2">
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
  </div>
<?php

  /*Checks if they are logged in*/
  if (isset($_SESSION["UsersID"])) {

    if (isset($_SESSION["colour"])) {
      $colour = $_SESSION["colour"];
      if ($colour == "red") {
        ?><div class="Bred"></div><?php
      }
      elseif ($colour == "blue") {
        ?><div class="Bblue"></div><?php
      }
      elseif ($colour == "aqua") {
        ?><div class="Baqua"></div><?php
      }
      elseif ($colour == "yellow") {
        ?><div class="Byellow"></div><?php
      }
      elseif ($colour == "pink") {
        ?><div class="Bpink"></div><?php
      }
    }

    ?>
    <div class="hSideBar">
      <div class="user">
      <?php echo "<p>Hello, " . $_SESSION["UsersName"] . "</p>"; ?>
      </div>
      <h2>Friend Bubble</h2>
      <nav class="homeNAV">
        <ul>
          <li><a href="profile.php">Profile Page</a></li>
          <li><a href="contactus.php">Conatct Us</a></li>
          <li><a href="Help.php">Help</a></li>
          <li><a href="logout.php">Log Out</a></li>
        </ul>
      </nav>
    </div>
    
    <?php 
    
    if (isset($_GET["userID"]) AND isset($_GET["userName"]) ) { 
        $TOuserID = $_GET['userID'];
        $TOuserName = $_GET['userName'];
        $fromUser = $_SESSION["UsersID"];
  
        ?><div class="topChatBar"><?php
        //profile image of user you chatting too
        $sqlImg = "SELECT * FROM profileimg WHERE userID='$TOuserID'";
        $resultImg = mysqli_query($connection, $sqlImg);
        while ($rowImg = mysqli_fetch_assoc($resultImg)) {
          echo "<div class='chatProfimg'>";
          if ($rowImg['ImageSet'] == 1) {
            echo "<img src='uploads/".$TOuserID.".jpg'>";
            echo "</div>";
          }
          else {
            echo "<img src='uploads/defaultprofile.jpg'>";
            echo "</div>";
          }
        }
        echo "<p class='chatname'>" . $TOuserName . "</p>"; ?>
        <a id="chatback" href="home.php">Go Back</a> <?php
        
        $_SESSION['TOuserID'] = $TOuserID;
        ?>
        </div>
        <section class="HOME2">
    
        <div class="chat" id="refresh" ><?php
          
          require_once 'DB_Connection.php';
  
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
          
        </div>
      
      <?php
      if (isset($_POST["send"])) {
        $msg = $_POST["msg"];
        if (empty($msg)) {
          echo "<p class=error> The message is blank!</p>";
        }
        else{
          $msg = encryptMSG($msg, $encryptionKEY);
          sendmsg($connection, $fromUser, $TOuserID, $msg);
        }
      }
    }
    else {
      echo "<p class=error> Something went wrong! <a href='home.php' class='error'> Click here to go back! </a></p>";
      exit();
    }
    ?>
    </section>
    <div class="chatbar">
      <form method="post">
        <input type="text" name="msg" placeholder="Type your message here...">
        <button name="send" class="send">Send</button>
      </form>
    </div>
  </body>
</html>

<?php
  }
  /*If they haven't logged in, an error is displayed, this could be they typed in the url instead of logging in first*/
  else {
    ?><p class="error">You should not be on this page <a href="index.php">Click here to go back!</a></p><?php
  }
?>

<!--CEOP button, allows young users to report online sexual abuse to the police as an added saftey messure-->
    <div class="ceop2">
      <a href="https://www.ceop.police.uk/Safety-Centre/" target= "_blank"><img class="ceop" src="img/ceop.png" alt="CEOP"></a>
    </div>