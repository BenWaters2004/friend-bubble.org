<?php
  session_start();
  include_once 'header.php';
  require_once 'DB_Connection.php';
  require_once 'functions.php';
  ?>

    <title>Friend Bubble</title>
  </head>
  <body>
  <?php
  /*Checks if they are logged in*/
  if (isset($_SESSION["UsersID"])) {
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
      <section class="HOME">
        <!--filters for user list-->
        <div class="filters">
          <a class="filtFriends" href="home.php">All Users</a>
        </div> 
        <div class="userList">
          
          <?php

          //makes a list of all users, so they can select who to chat too
          $friends = mysqli_query($connection, "SELECT * FROM friendsList")
            or die("Failed to connect to database");
            while ($rowFr = mysqli_fetch_assoc($friends)) {
              if ((($rowFr['friend1'] == $_SESSION["UsersID"]) or ($rowFr['friend2'] == $_SESSION["UsersID"])) AND ($rowFr['status'] == 'friends')) {
                if ($rowFr['friend1'] == $_SESSION["UsersID"]) {
                  $Fruser = $rowFr['friend2'];
                  $uUser = $_SESSION["UsersID"];
                  
                  $sqlImg = "SELECT * FROM profileimg WHERE userID= '$Fruser'";
                  $resultImg = mysqli_query($connection, $sqlImg);
                  while ($rowImg = mysqli_fetch_assoc($resultImg)) {
                    echo "<div class='propics'>";
                    if ($rowImg['ImageSet'] == 1) {
                      echo "<img src='uploads/".$Fruser.".jpg'>";
                    }
                    else {
                      echo "<img src='uploads/defaultprofile.jpg'>";
                    }

                    if ($Fruser == 1) {
                      echo '<a id="adminName" href="chat.php?userID=1&userName=~~~ADMIN~~~">~~~ADMIN~~~</a>';
                      echo '<p class="propicsChat" >Click their name to start a chat!</p>';
                    }
                    else {
                      $FrUsers = mysqli_query($connection, "SELECT * FROM users WHERE UsersID = $Fruser")
                        or die("Failed to connect to database");
                      while ($row = mysqli_fetch_assoc($FrUsers)) {
                        $sqlmsg = "SELECT * FROM messages WHERE (toUser = $Fruser AND fromUser = $uUser) OR (toUser = $uUser AND fromUser = $Fruser) ORDER BY mID DESC LIMIT 1";
                        $resultmsg = mysqli_query($connection, $sqlmsg);
                        $FrUname = $row["UsersUsername"];
                        echo '<a href="chat.php?userID='.$Fruser.'&userName='.$FrUname.'">'.$FrUname.'</a>';
                        if (mysqli_num_rows($resultmsg) > 0) {
                          $rowMsg = mysqli_fetch_assoc($resultmsg);
    
                          $msg = $rowMsg["msg"];
                          $msg = decryptMSG($msg, $encryptionKEY);
    
                          if ($rowMsg["fromUser"] == $uUser) {
                            echo '<p class="propicsChat" >You: ' . $msg . '</p>';
                          }
                          else {
                            echo '<p class="propicsChat" >Them: ' . $msg . '</p>';
                          }
                        }
                        else {
                          echo '<p class="propicsChat" >Click their name to start a chat!</p>';
                        }
                        echo '<a id="report" href="report.php?user='.$row["UsersID"].'">Report</a>';
                        echo '<a id="addfren" href="friends.php?remove='.$row["UsersID"].'">Remove Friend</a>';
                      }
                    }
                    echo "</div><br>";
                  }
                }
                else {
                  if ($rowFr['friend2'] == $_SESSION["UsersID"]) {
                    $Fruser = $rowFr['friend1'];
                    $uUser = $_SESSION["UsersID"];

                    $sqlImg = "SELECT * FROM profileimg WHERE userID= '$Fruser'";
                    $resultImg = mysqli_query($connection, $sqlImg);
                    while ($rowImg = mysqli_fetch_assoc($resultImg)) {
                      echo "<div class='propics'>";
                      if ($rowImg['ImageSet'] == 1) {
                        echo "<img src='uploads/".$Fruser.".jpg'>";
                      }
                      else {
                        echo "<img src='uploads/defaultprofile.jpg'>";
                      }
  
                      if ($Fruser == 1) {
                        echo '<a id="adminName" href="chat.php?userID=1&userName=~~~ADMIN~~~">~~~ADMIN~~~</a>';
                        echo '<p class="propicsChat" >Click their name to start a chat!</p>';
                      }
                      else {
                        $FrUsers = mysqli_query($connection, "SELECT * FROM users WHERE UsersID = $Fruser")
                          or die("Failed to connect to database");
                        while ($row = mysqli_fetch_assoc($FrUsers)) {
                          $sqlmsg = "SELECT * FROM messages WHERE (toUser = $Fruser AND fromUser = $uUser) OR (toUser = $uUser AND fromUser = $Fruser) ORDER BY mID DESC LIMIT 1";
                          $resultmsg = mysqli_query($connection, $sqlmsg);

                          $FrUname = $row["UsersUsername"];

                          echo '<a href="chat.php?userID='.$Fruser.'&userName='.$FrUname.'">'.$FrUname.'</a>';
                          if (mysqli_num_rows($resultmsg) > 0) {
                            $rowMsg = mysqli_fetch_assoc($resultmsg);
      
                            $msg = $rowMsg["msg"];
                            $msg = decryptMSG($msg, $encryptionKEY);
      
                            if ($rowMsg["fromUser"] == $uUser) {
                              echo '<p class="propicsChat" >You: ' . $msg . '</p>';
                            }
                            else {
                              echo '<p class="propicsChat" >Them: ' . $msg . '</p>';
                            }
                          }
                          else {
                            echo '<p class="propicsChat" >Click their name to start a chat!</p>';
                          }
                          echo '<a id="report" href="report.php?user='.$row["UsersID"].'">Report</a>';
                          echo '<a id="addfren" href="friends.php?remove='.$row["UsersID"].'">Remove Friend</a>';
                        }
                      }
                      echo "</div>";
                    }
                  }
                }
              }
            }
          
          if (isset($_GET['remove'])) {
            $toremove = $_GET['remove'];
            $currentusr = $_SESSION['UsersID'];
            $removal = "DELETE FROM friendsList WHERE (friend1 = '$toremove' AND friend2 = '$currentusr') OR (friend1 = $currentusr AND friend2 = $toremove)";
            mysqli_query($connection, $removal);
            header("Location: friends.php");

          }
          ?>
        </div>
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

