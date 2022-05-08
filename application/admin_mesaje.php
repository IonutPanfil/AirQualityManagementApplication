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

	if($info["rol"] === "client"){
		header("location:user_index.php");
	}

	$queryMesaje = "SELECT email, mesaj FROM mesaje";
	$stmtMesaje = $connect->prepare($queryMesaje);
	$stmtMesaje->execute();
	$mesaje = $stmtMesaje->fetchAll();

?>

<html>
    <title>Mesaje</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

	<link rel="stylesheet" href="styles/admin_index.css">
  <link rel="stylesheet" href="styles/admin_mesaje.css">
	<script src="javascripts/admin_index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
	</head>

	<body>
	<nav class="navbar navbar-default">

  <div class="navbar-header">

    <a class="navbar-brand" href="admin_index.php"><?php echo $user?></a>

  </div>

  <ul class="nav navbar-nav">

    <li><a href="admin_index.php">Acasa</a></li>
    <li class="active"><a href="admin_mesaje.php">Mesaje</a></li>
    <li><a href="admin_setari.php">Setari</a></li>
    <li><a href="logout.php">Logout</a></li>

  </ul>

</nav>

<div class="container">

  <div class="row">

    <div class="col-xs-2">

      <div class="panel panel-default">

        <div class="panel-heading">
          <a href="#">Administrare</a>
        </div>

        <div class="panel-body">
          <a href="admin_grafice.php">Grafice</a>
        </div>

        <div class="panel-body">
          <a href="admin_conturi.php">Conturi</a>
        </div>

        <div class="panel-body">
          <a href="admin_cereri.php">Cereri</a>
        </div>

        <div class="panel-body">
          <a href="admin_echip.php">Echipamente</a>
        </div>

      </div>

    </div>

    <div class="col-xs-6">

      <div id='myChart'>
      <div class="review-box">
			<div class="inbox-message">
				<ul>
					

					<?php
					foreach ($mesaje as $msj){
						echo '<li><div class="message-avatar">
						<img src="https://cdn.pixabay.com/photo/2014/07/01/15/40/balloon-381334_960_720.png" alt="Глеб">
					</div>
					<div class="message-body">
						<div class="message-body-heading">
							<h5>'.$msj['email'].'</h5>
						</div>
						<p>'.$msj['mesaj'].'</p>
					</div></li>';
					} 
					?>
				</ul>
			</div>
		</div>
      </div>

    </div>

    <div class="col-xs-4">

      <div id='myChart1'></div>

    </div>

  </div>

</div>
	</body>
</html>