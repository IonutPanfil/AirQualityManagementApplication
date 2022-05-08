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

  try{
    $query_Cont = "SELECT * FROM conturi WHERE permisiune='ASTEPTARE'";
    $stmt_conturi = $connect->prepare($query_Cont);
    $stmt_conturi->execute();
    $conturi = $stmt_conturi->fetchAll();

  } catch(PDOException $e){
    echo $e->getmessage();
        exit();
  }

?>

<html>
    <title>Cereri</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
  <link rel="stylesheet" href="styles/admin_conturi.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

	<link rel="stylesheet" href="styles/admin_index.css">
	<script src="javascripts/admin_index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
  <script language="javascript">
    function acceptaCont(accid){
      if(confirm("Doresti sa accepti contul?")){
        window.location.href='acceptaCont.php?acc_id=' + accid + '';
        return true;
      }
    } 
    function respingeCont(respid){
      if(confirm("Doresti sa respingi contul?")){
        window.location.href='respingeCont.php?resp_id=' + respid + '';
        return true;
      }
    }

  </script>
	</head>

	<body>
	<nav class="navbar navbar-default">

  <div class="navbar-header">

    <a class="navbar-brand" href="admin_index.php"><?php echo $user?></a>

  </div>

  <ul class="nav navbar-nav">

    <li><a href="admin_index.php">Acasa</a></li>
    <li><a href="admin_mesaje.php">Mesaje</a></li>
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
        <li class="active"><a href="admin_cereri.php">Cereri</a></li>
        </div>

        <div class="panel-body">
          <a href="admin_echip.php">Echipamente</a>
        </div>

      </div>

    </div>

    <div class="col-xs-6">

      <div id='myChart'>
      <table class="container" id="myTable">
	<thead>
		<tr>
			<th><h1>Username</h1></th>
			<th><h1>Email</h1></th>
      <th><h1>Accepta/Respinge</h1></th>
		</tr>
	</thead>
	<tbody>
  <?php
  foreach($conturi as $c){
    echo"
		<tr data-status='".$c['rol']."'>
			<td>".$c['nume']."</td>
			<td>".$c['email']."</td>
      <td><form action='admin_conturi' id='deleteBox' method ='post'><input name ='sterge' class ='sterge' type ='button' onclick='acceptaCont(".$c['id'].")' value='Accepta'><input name ='sterge' class ='sterge' type ='button' onclick='respingeCont(".$c['id'].")' value='Respinge'></form></td>
		</tr>";
  }
    ?>
	</tbody>
</table>
      </div>

    </div>

    <div class="col-xs-4">

      <div id='myChart1'></div>

    </div>

  </div>

</div>
	</body>
</html>