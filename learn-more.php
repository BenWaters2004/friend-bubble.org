<!--Learn More Page ~ just explains what the website is and how to use it to anyone new

links to the other needed pages -->
<?php
session_start();
include_once 'header.php';
include('functions.php');
require_once 'DB_Connection.php';
?>

  <title>Friend Bubble │ Learn More</title>
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

  <a class="back" href="index.php"><--Go back!</a> <!--Back Button-->

  <!--Text Box for page-->
  <section class="signup" id="lmPage">
    <h2>Learn More</h2><br> <!--title-->
    <p>Friend Bubble is an instant messaging website! Here you can chat with friends, family and make new friends from all over the world, over the internet.</p><br>
    <p>You can create an account that is free and safe, you can then customise your account to be how ever you want!</p>
    <p>When you create your password, make sure it is something you will remember but is not something other people might be able to guess!</p>
    <p>Currently there is no way to recover an account if you forget your password!</p><br>
    <p>Once you have created an account just click a name and type what ever you want to say in the bar at the bottom then click send!</p>
    <p>They will be able to see this message and if you look above the bar in the white space, your message will appear and so will any message they send you!</p><br>
    <p>When your done just click back and select someone else to talk to or press logout to stop</p><br>
    <p>Happy Chatting!</p>
  </section>

  <!--Report abuse button-->
  <div class="ceop2">
    <a href="https://www.ceop.police.uk/Safety-Centre/" target= "_blank"><img class="ceop" src="img/ceop.png" alt="CEOP"></a>
  </div>
</body>
</html>
