<?php
require_once('connect.php');

function alert($message){
  echo "<script>alert('$message');</script>";
}




if(isset($_POST["trimite"]))
{   
    
    $email = $_POST["email"];
    $mesaj = $_POST["mesaj"];
    $query = "INSERT INTO mesaje (email, mesaj) VALUES ('$email', '$mesaj')";
	$statement = $connect->prepare($query);
	$statement->bindValue(':email', $email);
    $statement->bindValue(':mesaj', $mesaj);

	$result = $statement->execute();

	if($result)
	{
		alert("Mesaj trimis catre administratorii site-ului.");
	}
}




?>

<html>
<title>Contacteaza-ne</title>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="styles/register.css">
</head>

<body>
  <center><br><br>
<form action="" method="POST">
  <div class="container">
    <h1>TRIMITE MESAJ CATRE ADMINISTRATOR</h1>
    <hr>

    <label for="email"><b>Adresa email:</b></label><br>
    <input type="text" placeholder="Introdu adresa de email" name="email" id="email" required><br>

    <label for="mesaj"><b>Scrie mesajul catre administratori:</b></label><br>
    <textarea placeholder="Introdu mesajul catre administratori" name="mesaj" id="mesaj" style="height:100px;  width: 80%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #f1f1f1;" required></textarea><br>
    <hr>

    <input type="submit" value="Trimite" class="registerbtn" name="trimite">
    
  </div>

  <div class="container signin">
    <a href="home.php" style="background: -moz-linear-gradient(-45deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
background: -webkit-gradient(left top, right bottom, color-stop(0%, rgba(30,29,31,1)), color-stop(100%, rgba(223,64,90,1)));
background: -webkit-linear-gradient(-45deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
background: -o-linear-gradient(-45deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
background: -ms-linear-gradient(-45deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
background: linear-gradient(135deg, rgba(30,29,31,1) 0%, rgba(223,64,90,1) 100%);
    color: white;
    padding: 11px 30px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 50%;
    opacity: 0.9;">INAPOI</a>
  </div>
  
</form>
</center>
</body>
</html>