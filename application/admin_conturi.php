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
    $query_Cont = "SELECT * FROM conturi WHERE permisiune='DA'";
    $stmt_conturi = $connect->prepare($query_Cont);
    $stmt_conturi->execute();
    $conturi = $stmt_conturi->fetchAll();

  } catch(PDOException $e){
    echo $e->getmessage();
        exit();
  }

?>

<html>
    <title>Conturi</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
  <link rel="stylesheet" href="styles/admin_conturi.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


	<link rel="stylesheet" href="styles/admin_index.css">
	<script src="javascripts/admin_index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
  <script src="javascripts/admin_conturi.js"></script>
  <script language="javascript">
    function deleteCont(delid){
      if(confirm("Doresti sa stergi contul?")){
        window.location.href='deleteCont.php?del_id=' + delid + '';
        return true;
      }
    }

      function cAdmin(idA){
      if(confirm("Doresti sa schimbi rolul contului in admin?")){
        window.location.href='creeazaAdmin.php?A_id=' + idA + '';
        return true;
      }
    }

      function cClient(idC){
      if(confirm("Doresti sa schimbi rolul contului in client?")){
        window.location.href='creeazaClient.php?C_id=' + idC + '';
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
        <li class="active"><a href="admin_conturi.php">Conturi</a></li>
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

      <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Cauta dupa username..." title="Cauta dupa username">

      <div class="table-wrapper">
        <div class="table-title">
            <div class="row">
                <div class="col-sm-6"><h4>Filtreaza dupa rol:</h4></div>
                <div class="col-sm-6">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-info active">
                            <input type="radio" name="status" value="Toti" checked="checked"> TOTI
                        </label>
                        <label class="btn btn-success">
                            <input type="radio" name="status" value="admin"> admin
                        </label>
                        <label class="btn btn-success">
                            <input type="radio" name="status" value="client"> client
                        </label>     
                    </div>
                </div>
            </div>
        </div>
      <table class="container" id="myTable">
	<thead>
		<tr>
      <th><h1>Email</h1></th>
			<th><h1>Username</h1></th>
			<th><h1>Rol</h1></th>
      <th><h1>Sterge cont</h1></th>
		</tr>
	</thead>
	<tbody>
  <?php
  foreach($conturi as $c){
    echo"
		<tr data-status='".$c['rol']."'>
      <td>".$c['email']."</td>
			<td>".$c['nume']."</td>
			<td>".$c['rol']."</td>
      <td><form action='admin_conturi' id='deleteBox' method ='post'><input name ='sterge' class ='sterge' type ='button' onclick='deleteCont(".$c['id'].")' value='Sterge'></form></td>
		</tr>";
  }
    ?>
	</tbody>
</table>



<h4>Modifica rol:</h4>
<table class="container" id="myTable">
	<thead>
		<tr>
      <th><h1>Email</h1></th>
			<th><h1>Rol</h1></th>
      <th><h1>Modifica rol</h1></th>
		</tr>
	</thead>
	<tbody>
  <?php
  foreach($conturi as $c){
    echo"
		<tr data-status='".$c['rol']."'>
      <td>".$c['email']."</td>
			<td>".$c['rol']."</td>
      <td><form action='admin_conturi' id='deleteBox' method ='post'><input name ='sterge' class ='sterge' type ='button' onclick='cAdmin(".$c['id'].")' value='Schimba in admin'></form><input name ='sterge' class ='sterge' type ='button' onclick='cClient(".$c['id'].")' value='Schimba in client'></form></td>
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