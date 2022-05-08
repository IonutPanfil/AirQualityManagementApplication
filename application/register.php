<?php
  require_once('connect.php');

  function alert($message){
    echo "<script>alert('$message');</script>";
  }

  if(isset($_POST["creeaza"]))
	{
    if($_POST["psw"] !== $_POST["npsw"])
		{
			alert("Parolele nu se potrivesc");
		}
    else
    {
      try
			{
				$email = $_POST["email"];
				$pass = $_POST["psw"];
        $nume = $_POST["usernm"];
				$query = "SELECT COUNT(email) AS num FROM conturi WHERE email = '$email'";
				$statement = $connect->prepare($query);
				$statement->bindValue(':email', $email);
				$statement->execute(
					array (
						':email' => $email
					)
				);
				$row = $statement->fetch(PDO::FETCH_ASSOC);

				if($row['num'] > 0)
				{
					alert("Adresa de mail exista deja!!!");
				}
				else
				{
					$passwordHash = password_hash($_POST["psw"], PASSWORD_BCRYPT, array("cost" => 12));

					$query = "INSERT INTO conturi (nume, email, parola, rol, permisiune) VALUES ('$nume', '$email', '$pass', 'client', 'ASTEPTARE')";
					$statement = $connect->prepare($query);
					$statement->bindValue(':nume', $nume);
					$statement->bindValue(':email', $email);
          $statement->bindValue(':parola', $pass);

					$result = $statement->execute();

					if($result)
					{
						alert("S-a salvat. Aceasta inregistrare a fost trimisa catre administrator sub forma unei cereri de inscriere. Asteapta aprobarea cererii");
					}
				}
			}
			catch(PDOException $e) {
				echo $e->getmessage();
				exit();
			}
    }
  }
?>

<html>
<title>Inregistrare</title>

<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="styles/register.css">
</head>

<body>
  <center><br><br>
<form action="" method="POST">
  <div class="container">
    <h1>Creeaza cont</h1>
    <hr>

    <label for="email"><b>Adresa email:</b></label><br>
    <input type="text" placeholder="Introdu adresa de email" name="email" id="email" required><br>

    <label for="usernm"><b>Nume de utilizator:</b></label><br>
    <input type="text" placeholder="Introdu numele de utilizator" name="usernm" id="usernm" required><br>

    <label for="psw"><b>Parola:</b></label><br>
    <input type="password" placeholder="Introdu parola" name="psw" id="psw" required><br>

    <label for="npsw"><b>Repetare parola:</b></label><br>
    <input type="password" placeholder="Repeta parola" name="npsw" id="npsw" required><br>
    <hr>

    <input type="submit" value="Creeaza cont" class="registerbtn" name="creeaza">
    
  </div>
  
  <div class="container signin">
    <p>Ai deja un cont? <a href="home.php">Logheaza-te</a>.</p>
  </div>

</form>
</center>
</body>
</html>