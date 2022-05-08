<?php
	session_start();
	require_once('connect.php');

  function alert($message){
    echo "<script>alert('$message');</script>";
  }
  try{
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
}
  catch(PDOException $e) {
    echo $e->getmessage();
    exit();
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
  if(isset($_POST["postare"]))
  {
    $anunt = $_POST["anunt"];
    $queryPost = "INSERT INTO anunturi (idCont, anunt) VALUES ('$idClient', '$anunt')";
		$statementPost = $connect->prepare($queryPost);
		$statementPost->bindValue(':idCont', $idClient);
		$statementPost->bindValue(':anunt', $anunt);
		$resultPost = $statementPost->execute();
    if($resultPost)
		{
			alert("Anunt postat");
		}
  }
}
  catch(PDOException $e) {
    echo $e->getmessage();
    exit();
  }

  //numar mesaje
  try{
  $queryMesaje = "SELECT COUNT(id) AS numMsj FROM mesaje";
	$statementMesaje = $connect->query($queryMesaje);
	$countMesaje = $statementMesaje->fetchColumn();
  }
  catch(PDOException $e) {
    echo $e->getmessage();
    exit();
  }
  
  //numar conturi
  try{
  $queryConturi = "SELECT COUNT(id) AS numCont FROM conturi";
	$statementConturi = $connect->query($queryConturi);
	$countConturi = $statementConturi->fetchColumn();
  }
  catch(PDOException $e) {
    echo $e->getmessage();
    exit();
  }

  //numar echipamente
  try{
  $queryEchip = "SELECT COUNT(id) AS numEchip FROM echipamente";
	$statementEchip = $connect->query($queryEchip);
	$countEchip = $statementEchip->fetchColumn();
  }
  catch(PDOException $e) {
    echo $e->getmessage();
    exit();
  }
?>

<html>
    <title>Prima pagina al administratorilor</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.5.2/metisMenu.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/3.3.7/css/sb-admin-2.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.css">
  <link rel="stylesheet" href="https://s3-us-west-2.amazonaws.com/s.cdpn.io/416491/timeline.css">
  <link rel="stylesheet" href="">
  <link rel="stylesheet" href="">
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v3.0.6/css/line.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

	<link rel="stylesheet" href="styles/admin_index.css">
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

    <li class="active"><a href="admin_index.php">Acasa</a></li>
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
          <a href="">Administrare</a>
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
      <div class="row">
      <div class="col-lg-12">
        <h1 class="page-header">Sumar</h1>
      </div>
      <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
      <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-comments fa-3x"></i>
              </div>
              <div class="col-xs-9 text-right">
              <div class="huge"><?php print $countMesaje ?></div>
                <div> Mesaje!</div>
              </div>
            </div>
          </div>
          <a href="admin_mesaje.php">
            <div class="panel-footer">
              <span class="pull-left">Vezi detalii</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-tasks fa-3x"></i>
              </div>
              <div class="col-xs-9 text-right">
                <div class="huge"><?php print $countConturi ?></div>
                <div>Conturi!</div>
              </div>
            </div>
          </div>
          <a href="admin_conturi.php">
            <div class="panel-footer">
              <span class="pull-left">Vezi Detalii</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-support fa-3x"></i>
              </div>
              <div class="col-xs-9 text-right">
                <div class="huge"><?php print $countEchip ?></div>
                <div>Echipamente!</div>
              </div>
            </div>
          </div>
          <a href="admin_echip.php">
            <div class="panel-footer">
              <span class="pull-left">Vezi detalii</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      </div>

    </div>

<!--   <div class="col-xs-6-left"> -->
  <!--    <div id='myChart1'> -->
    <center>
        <table>
          <tr>
            <th>Anunturi:</th>
          </tr>
        </table>
        <?php
        $query="SELECT conturi.nume AS nume, anunt FROM anunturi, conturi WHERE anunturi.idCont = conturi.id";
        $statement = $connect->prepare($query);
			  $statement->execute();
			  $r = $statement->fetchAll();

        foreach ($r as $row) {
          echo "
									<tr>
										<td>(".$row['nume'].") - ".$row['anunt']."</td><br>
                  </tr>";
        }
        ?>
        
          <br><br>
          <form id="anuntform" method="post" action="admin_index.php">
          <input type='text' id='anunt' name='anunt' placeholder="Scrie un anunt...."/><br><br>
          <input type='submit' id='postanunt' value='Posteaza' title='Posteaza anuntul' name='postare'/>
          </form>
      </center>
    <!--  </div> --> 

  <!--  </div> --> 
    

  </div>

</div>

	</body>
</html>