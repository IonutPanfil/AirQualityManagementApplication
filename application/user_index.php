<?php
	session_start();
	require_once('connect.php');
	if(isset($_SESSION["email"]))
	{
		$user = $_SESSION["email"];
		$query_idClient = "SELECT id  FROM  conturi WHERE email='$user'";
		$statmt_idClient = $connect->prepare($query_idClient);
		$idClient = $statmt_idClient->execute();
		

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

?>

<html>
    <title>Prima pagina al clientilor</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
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
<section id="home">
        <div class="title-group">
            <h1>Salutare, <?php echo $user; ?></h1>
            <h2>Bine ai revenit in cont</h2>
        </div>
    </section>
</div>

	
	</body>
</html>