<?php
	session_start();
	require_once('connect.php');

	function alert($message){
		echo "<script>alert('$message');</script>";
	  }

	if(isset($_SESSION["email"]))
	{
		$user = $_SESSION["email"];
		$query_idClient = "SELECT id  FROM  conturi WHERE email='$user'";
		$statmt_idClient = $connect->prepare($query_idClient);
		$idClient = $statmt_idClient->execute();

		$query_emailClient = "SELECT email  FROM  conturi WHERE email='$user'";
		$statmt_emailClient = $connect->prepare($query_emailClient);
		$statmt_emailClient->execute();
		$emailClient = $statmt_emailClient->fetch(PDO::FETCH_ASSOC);
		

	}
	else
	{
		header("location:home.php");
		die();
	}

	try{
		$query_info = "SELECT id, nume, email, rol FROM conturi WHERE email='$user'";
		$stmt_info = $connect->prepare($query_info);
		$stmt_info->bindValue(':email', $user);
		$stmt_info->execute(
			array(
			  ':email' => $user
			)
		  );
		$info = $stmt_info->fetch(PDO::FETCH_ASSOC);

		
	} catch(PDOException $e){
		echo $e->getmessage();
		exit();
	}

	if($info["rol"] === "admin"){
		header("location:admin_index.php");
	}

	if(isset($_POST["trimite"]))
{   
    
    $email = $emailClient["email"];
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
    <title>Neclaritati</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="styles/register.css">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
	<link rel="stylesheet" href="styles/user_index.css">

	<!-- Jquery needed -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="javascripts/user_index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	</head>

	<body>
	<nav class="navbar-inverse" style="position:sticky;top:0;z-index:99999">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="user_index.php"><?php echo $user;?></a>
    </div>
    
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li><a href="user_calitate.php">Calitatea aerului</a></li>
        <li><a href="user_noutati.php">Noutati</a></li>
        <li><a href="user_neclaritati.php">Neclaritati</a></li>
        <li><a href="user_setari.php">Setarile contului</a></li>
        <li><a href="logout.php">Iesi din cont</a></li>
      </ul>
    </div>
</nav>

<div class="announcement">
  <marquee id="marqueeStyle" onmouseover="this.stop();" onmouseout="this.start();">
    ***  Mail-ul administratorilor: m.calitateaer@gmail.com  ***
    </marquee>
</div>

<div class="container">
<center><br><br>
<form action="" method="POST">
  <div class="container">
    <h1>TRIMITE NECLARITATEA CATRE ADMINISTRATOR</h1>

    <label for="mesaj"><b>Scrie neclaritatea catre administratori:</b></label><br>
    <textarea placeholder="Introdu neclaritatea catre administratori" name="mesaj" id="mesaj" style="height:100px;  width: 80%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #f1f1f1;" required></textarea><br>
    <hr>

    <input type="submit" value="Trimite" class="registerbtn" name="trimite">
    
  </div>
  </div>
  
</form>
</center>
</div>

	
	</body>
</html>