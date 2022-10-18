<?php
session_start();
include_once 'header.php';
require_once 'DB_Connection.php';
?>

  <title>Friend Bubble │ Report</title>
</head>

<body class="bstyle" style="background-color: rgb(13, 31, 42)">
<div class="profback">
<a class="back" href="home.php"><--Go back!</a>
</div>

<?php
if (isset($_SESSION["UsersID"])) { //checks if user is logged in

  if (isset($_GET['user'])) {
      $suspectID = $_GET['user'];
      $byID = $_SESSION['UsersID'];
      echo "$suspectID";
      echo "$byID"; ?>

      <div class="signup">
          <form method="POST">
              <p class="reportP">Thank you for making a report, please enter a reason for your report</p>
              <input id="ReportInput" type="text" name="reason" placeholder="Reason...">
              <button type="submit" name="reasonSub">Submit</button>
          </form>
      </div>
      <?php
      if ($_SERVER['REQUEST_METHOD'] == "POST") {
          $reason = $_POST['reason'];
          $sql = "INSERT INTO reports (suspectID, byID, reason) VALUES (?, ?, ?);";
          $stmt = mysqli_stmt_init($connection);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
              echo "<p class=error>There was an error!</p>";
          }

          mysqli_stmt_bind_param($stmt, "iis", $suspectID, $byID, $reason);
          mysqli_stmt_execute($stmt);

          mysqli_stmt_close($stmt);

          echo "<p class=reportDone >All done, thank you! We will look into this as soon as we can!</p>";
      }
  }
}
else {
  ?><p class="error">You should not be on this page <a href="index.php">Click here to go back!</a></p><?php
}
?>