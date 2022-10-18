<?php
//basic html tags and links to stylesheets ect
  session_start();
  include_once 'header.php';
?>
    <title>Friend Bubble │ Contact Us</title>
  </head>
  <body>
    <a class="back" href="home.php"><--Go back!</a> <!--back button-->
    <section class="signup">
      <h2>Contact Us</h2> <!--title-->
      <p class="contact"> <!--info-->
      To contact us, you can message
      ~~~ADMIN~~~
      but please make sure the name contains these symbols ~~~ as only our account will have them! 
      If you are experiencing cyber bullying or other online abuse, please do not repond to them. 
      Instead tell someone you trust like family or friends, and tell us/or report them so that we can prevent any further comunications. 
      You can also report this abuse to the police using the ceop button in the bottom right.

      <nav class="homeNAV">
        <ul>
          <li><a class="backBlack" href="chat.php?userID=1&userName=~~~ADMIN~~~">Message us</a></li>
        </ul>
      </nav>

    </section>
    <!--CEOP button, allows young users to report online sexual abuse to the police as an added saftey messure-->
    <div class="ceop2">
        <a href="https://www.ceop.police.uk/Safety-Centre/" target= "_blank"><img class="ceop" src="img/ceop.png" alt="CEOP"></a>
    </div>
  </body>
</html>