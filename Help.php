<?php
session_start();
include_once 'header.php';
include('functions.php');
require_once 'DB_Connection.php'; 
?>

  <title>Friend Bubble │ Help</title>
</head>

<body class="bstyle" style="background-color: rgb(13, 31, 42)">
<div class="profback">
<a class="back" href="home.php"><--Go back!</a>
</div>

<?php
  if (isset($_SESSION["UsersID"])) { ?>
    <section class="HelpMe">
      <h2>Need Help?</h2>

      <br><p class="headerHelp">If you are having a problem with a user:</p>
      <p>- To contact us, you can message ~~~ADMIN~~~ but please make sure the name contains these symbols ~~~ as only our account will have them!</p>
      <p>- If you are experiencing cyber bullying or other online abuse, please do not repond to them.</p>
      <p>- Instead tell someone you trust like family or friends, and tell us/or report them so that we can prevent any further comunications.</p>
      <p>- You can also report this abuse to the police using the ceop button in the bottom right.</p>

      <br><p class="headerHelp">If you are having a problem with the website:</p>
      <p>- Try refreshing the page</p>
      <p>- If there is still a proablem try logging out and back in again</p>
      <p>- Next try a different device if possible</p>
      <p>- Otherwise, inform us of any issues with the website, so we can get it fixed as soon as possible</p>

      <br><p class="headerHelp">Security note:</p>
      <p>- We will only ever talk to you throught the account "~~~ADMIN~~~" please ignore any other accounts prettending to be us</p>
      <p>- We will never ask for any personal infomation</p>
      <p>- Please do not give anyone any of your personal infomation</p>
      <p>- Remember if you ever need help report or contact us!</p>
    </section>

    <div class="ceop2">
      <a href="https://www.ceop.police.uk/Safety-Centre/" target= "_blank"><img class="ceop" src="img/ceop.png" alt="CEOP"></a>
    </div>
  </body>
  </html>
  <?php
  }
  else {
    ?><p class="error">You should not be on this page <a href="index.php">Click here to go back!</a></p><?php
  }
?>