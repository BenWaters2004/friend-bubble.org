<?php
  //links to databases, html tags, stylings and functions page
  session_start();
  require_once 'DB_Connection.php';
  require_once 'functions.php';

  $uUser = $_SESSION["UsersID"];

  //Friend request and accepting friends requests
  if (isset($_GET['fren'])) { //request friends
    $frenUsr = $_GET['fren'];
    $sql = mysqli_query($connection, "INSERT INTO friendsList (friend1, friend2, status) VALUES ('$uUser', '$frenUsr', 'requested')");
    header("Location: home.php"); //refreshes page
    exit();
  }
  elseif (isset($_GET['Addfren'])) { //acepting friends
    $frenUsr = $_GET['Addfren'];
    $sql = mysqli_query($connection, "UPDATE friendsList SET status='friends' WHERE friend1 = '$frenUsr' AND friend2 = '$uUser'");
    header("Location: home.php"); //refreshes page
    exit();
  }

  include_once 'header.php';
?>

    <title>Friend Bubble</title>
  </head>

  <body>
  <!--Bubble Animation-->
  <div class="bubbles2">
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
    <div><span class="gleam"></span></div>
  </div>

  <?php
  //Sets Background colours
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
  /*Checks if they are logged in*/
  if (isset($_SESSION["UsersID"])) {

    if (isset($_GET["menu"])) {
      if (($_GET["menu"]) == "yes") {
        ?><div class="hSideBar2">
        <div class="user">
        </div>
        <h2>Friend Bubble</h2> <!--Title-->
        <a class="close" id="white" href="home.php">+</a>
        <!--Navigation Bar-->
        <nav class="homeNAV">
          <ul>
            <li><a id="tooSMALL" href="profile.php">Profile Page</a></li>
            <li><a href="contactus.php">Conatct Us</a></li>
            <li><a href="Help.php">Help</a></li>
            <li><a href="logout.php">Log Out</a></li>
          </ul>
        </nav>
      </div> <?php
      }
    }

    ?>
    <!--Navigation Bar-->
    <div class="hSideBar">
      <div class="user">
      <?php echo "<p>Hello, " . $_SESSION["UsersName"] . "</p>"; //displays users name?>
      </div>
      <h2>Friend Bubble</h2> <!--Title-->
      <!--Navigation Bar-->
      <nav class="homeNAV">
        <ul>
          <li><a id="tooSMALL" href="profile.php">Profile Page</a></li>
          <li><a href="contactus.php">Conatct Us</a></li>
          <li><a href="Help.php">Help</a></li>
          <li><a href="logout.php">Log Out</a></li>
        </ul>
      </nav>
    </div>

    <!--User selection-->
    <section class="HOME">
      <?php
      //filters for user list ?>
        <div class="filters">
          <a class="filtFriends" href="friends.php">Friends List</a> <!--Friends List-->
          <a class="☰" href="home.php?menu=yes">☰</a>
          <form method="POST">
            <input type="text" name="search" placeholder="Search Users..."> <!--Search bar-->
            <button type="submit" name="searchButton">Search</button>
          </form>
        </div> 

        <?php

        //makes a list of all users, so they can select who to chat too
        //Looks for users where the searched words appear
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
          $search = mysqli_real_escape_string($connection, $_POST['search']);
          $users = mysqli_query($connection, "SELECT * FROM users WHERE UsersUsername LIKE '%$search%'") //finds user with name similar to search
                or die("Failed to connect to database");
          if (mysqli_num_rows($users) > 0) {
            while ($row = mysqli_fetch_assoc($users)) { //displays each user
              $userID = $row['UsersID'];
              $sqlImg = "SELECT *FROM profileimg WHERE userID='$userID'"; //checks if user has profile picture or not
              $resultImg = mysqli_query($connection, $sqlImg);
              while ($rowImg = mysqli_fetch_assoc($resultImg)) {
                echo "<div class='propics'>";
            
                if ($rowImg['ImageSet'] == 1) { //displays profile picture if they have one
                  echo "<img src='uploads/".$userID.".jpg'>";
                }
                else {
                  echo "<img src='uploads/defaultprofile.jpg'>"; //users default image if no profile picture set
                }
                
                echo '<a href="chat.php?userID='.$row["UsersID"].'&userName='.$row["UsersUsername"].'">'.$row["UsersUsername"].'</a>'; //button that takes you to chat page with user selected in url
                echo '<p class="propicsChat" >Click their name to start a chat!</p>'; //preview message
                echo '<a id="report" href="report.php?user='.$row["UsersID"].'">Report</a>'; //report button with user selected in url

                //checks friend status
                $currentUsr = $row["UsersID"];
                $listFren = mysqli_query($connection, "SELECT * FROM friendsList WHERE (friend1 = $uUser AND friend2 = $currentUsr AND status = 'requested')");
                $listFrens = mysqli_query($connection, "SELECT * FROM friendsList WHERE (friend1 = $currentUsr AND friend2 = $uUser AND status = 'friends') OR (friend1 = $uUser AND friend2 = $currentUsr AND status = 'friends')");
                if (mysqli_num_rows($listFren) > 0) {
                  echo '<a id="addfren">Requested</a>'; //you requested to be their friend
                }
                elseif (mysqli_num_rows($listFrens) > 0) {
                  echo '<a id="addfren">Friends</a>'; //you are friends
                }
                else {
                  $listFren = mysqli_query($connection, "SELECT * FROM friendsList WHERE (friend1 = $currentUsr AND friend2 = $uUser AND status = 'requested')");
                  if (mysqli_num_rows($listFren) > 0) {
                    echo '<a class="sentreq" href="home.php?Addfren='.$row["UsersID"].'">Accept their friend request?</a>'; //they requested to be your friend
                  }
                  else {
                    echo '<a id="addfren" href="home.php?fren='.$row["UsersID"].'">Add Friend</a>'; //no friend request sent or recieved
                  }
                }

                echo "</div>";
              }
            }
          }
          else{ //displays when there is no user who matches what you searched for
            echo "<p class=error >No users match your search!";
          }
        }
        //looks for users where the search word dosn't appear
        else {
          $users = mysqli_query($connection, "SELECT * FROM users ORDER BY rand()")
            or die("Failed to connect to database");
          
          //outputs admin frist so it is always at the top
          ?><div class="propics"><?php
          echo "<img src='uploads/1.jpg'>";
            echo '<a id="adminName" href="chat.php?userID=1&userName=~~~ADMIN~~~">~~~ADMIN~~~</a>';
            echo '<p class="propicsChat" >If their are any problems just click my name to chat!</p>'; //does not display preview message for security, like if the user 
            //needs help but the person abusing them is in the room?>
          </div><?php
          
          if (mysqli_num_rows($users) > 0) {
            while ($row = mysqli_fetch_assoc($users)) {
              $userID = $row['UsersID'];
              if (($userID != $uUser) and ($userID != 1)) { //so that your own name dosnt appear or admin again

                $sqlImg = "SELECT * FROM profileimg WHERE userID='$userID'"; //checks if profile picture is set
                $resultImg = mysqli_query($connection, $sqlImg);
                while ($rowImg = mysqli_fetch_assoc($resultImg)) {
                  echo "<div class='propics'>";
                  if ($rowImg['ImageSet'] == 1) {
                    echo "<img src='uploads/".$userID.".jpg'>"; //displays user set profile picture
                  }
                  else {
                    echo "<img src='uploads/defaultprofile.jpg'>"; //displays default propfile picture
                  }

                  $sqlmsg = "SELECT * FROM messages WHERE (toUser = $userID AND fromUser = $uUser) OR (toUser = $uUser AND fromUser = $userID) ORDER BY mID DESC LIMIT 1";
                  $resultmsg = mysqli_query($connection, $sqlmsg);
                  echo '<a href="chat.php?userID='.$row["UsersID"].'&userName='.$row["UsersUsername"].'">'.$row["UsersUsername"].'</a>'; //displays button with user in url
                  if (mysqli_num_rows($resultmsg) > 0) { //if you have sent them a message before
                    $rowMsg = mysqli_fetch_assoc($resultmsg);

                    $msg = $rowMsg["msg"]; //decrypts message
                    $msg = decryptMSG($msg, $encryptionKEY);

                    if ($rowMsg["fromUser"] == $uUser) { //displays message if you sent it
                      echo '<p class="propicsChat" >You: ' . $msg . '</p>';
                    }
                    else { //displays message if they sent it
                      echo '<p class="propicsChat" >Them: ' . $msg . '</p>';
                    }
                  }
                  else { //if no message has been sent
                    echo '<p class="propicsChat" >Click their name to start a chat!</p>';
                  }
                  echo '<a id="report" href="report.php?user='.$row["UsersID"].'">Report</a>'; //report button with user selected in url

                  $currentUsr = $row["UsersID"];
                  $listFren = mysqli_query($connection, "SELECT * FROM friendsList WHERE (friend1 = $uUser AND friend2 = $currentUsr AND status = 'requested')");
                  $listFrens = mysqli_query($connection, "SELECT * FROM friendsList WHERE (friend1 = $currentUsr AND friend2 = $uUser AND status = 'friends') OR (friend1 = $uUser AND friend2 = $currentUsr AND status = 'friends')");
                  if (mysqli_num_rows($listFren) > 0) {
                    echo '<a id="addfren">Requested</a>'; //you requested to be their friend
                  }
                  elseif (mysqli_num_rows($listFrens) > 0) { //you are friends
                    echo '<a id="addfren">Friends</a>';
                  }
                  else {
                    $listFren = mysqli_query($connection, "SELECT * FROM friendsList WHERE (friend1 = $currentUsr AND friend2 = $uUser AND status = 'requested')");
                    if (mysqli_num_rows($listFren) > 0) {
                      echo '<a class="sentreq" href="home.php?Addfren='.$row["UsersID"].'">Accept their friend request?</a>'; //they requested to be your friend
                    }
                    else {
                      echo '<a id="addfren" href="home.php?fren='.$row["UsersID"].'">Add Friend</a>'; //no friend request sent or recieved
                    }
                  }
                }
                echo "</div>";
                }
              
              }
            }
          }
        ?>
      </section>
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

