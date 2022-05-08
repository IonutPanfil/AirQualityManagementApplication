<html>
   
   <head>
      <title>Sending HTML email using PHP</title>
   </head>
   
   <body>
      
      <?php
      $receiver = "m.calitateaer@gmail.com";
      $subject = "Email TEST via PHP using localhost";
      $body = "Hy, tehre, this is a test";

      if(mail($receiver, $subject, $body)){
         echo "Email sent successfully ";
      } else {
         echo "Sorry";
      }
      ?>
      
   </body>
</html>