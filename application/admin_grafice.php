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
		$query_infocont = "SELECT id, nume, email, rol FROM conturi WHERE email='$user'";
		$stmt_infocont = $connect->prepare($query_infocont);
		$stmt_infocont->bindValue(':email', $user);
		$stmt_infocont->execute(
			array(
			  ':email' => $user
			)
		  );
		$infocont = $stmt_infocont->fetch(PDO::FETCH_ASSOC);

		
	} catch(PDOException $e){
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

  $connection = mysqli_connect('localhost:3306', 'root', '', 'calitate_aer');
  $result = mysqli_query($connection, "SELECT data, calitate FROM calitateaer WHERE idrasp = 1 ORDER BY data ASC");
  $rezult = mysqli_query($connection, "SELECT data, calitate FROM calitateaer WHERE idrasp = 2 ORDER BY data ASC");
  
  try{
    $idCont = $infocont['id'];
		$query_valori = "SELECT DISTINCT conturi.email AS email, echipamente.localizare AS localizare, 
    cont_calitate.v_medie AS medie, cont_calitate.v_minim AS minim, cont_calitate.v_maxim AS maxim
    FROM cont_calitate, conturi, echipamente, calitateaer
    WHERE cont_calitate.idcont = conturi.id AND cont_calitate.idechip = echipamente.id AND echipamente.id = calitateaer.idrasp AND idcont = '$idCont' AND idechip = 1";
		$stmt_valori = $connect->prepare($query_valori);
		// $stmt_valori->bindValue(':email', $user);
		$stmt_valori->execute(
			array(
			  ':email' => $user
			)
		  );
		$info = $stmt_valori->fetch(PDO::FETCH_ASSOC);

		
	} catch(PDOException $e){
		echo $e->getmessage();
		exit();
	}
  
  try{
    $idCont = $infocont['id'];
		$query_valori2 = "SELECT DISTINCT conturi.email AS email, echipamente.localizare AS localizare, 
    cont_calitate.v_medie AS medie, cont_calitate.v_minim AS minim, cont_calitate.v_maxim AS maxim
    FROM cont_calitate, conturi, echipamente, calitateaer
    WHERE cont_calitate.idcont = conturi.id AND cont_calitate.idechip = echipamente.id AND echipamente.id = calitateaer.idrasp AND idcont = '$idCont' AND idechip = 2";
		$stmt_valori2 = $connect->prepare($query_valori2);
		// $stmt_valori->bindValue(':email', $user);
		$stmt_valori2->execute();
		$info2 = $stmt_valori2->fetch(PDO::FETCH_ASSOC);

		
	} catch(PDOException $e){
		echo $e->getmessage();
		exit();
	}

?>

<html>
    <title>Grafice</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv='refresh' content='30'>
	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

	<link rel="stylesheet" href="styles/admin_index.css">
	<script src="javascripts/admin_index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['line']});
      google.charts.setOnLoadCallback(drawChart);
      google.charts.setOnLoadCallback(drawChart1);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('string', 'Data');
      data.addColumn('number', 'E1 - Smardan, Galati');
      data.addColumn('number', 'minim');
      data.addColumn('number', 'maxim');

      data.addRows([
        <?php
        if(mysqli_num_rows($result)> 0){
           while($row = mysqli_fetch_array($result)){
             echo "['".$row['data']."', ".(int)$row['calitate'].", ".$info['minim'].", ".$info['maxim']."],";
            }
          }
        ?>
      ]);

      var options = {
        chart: {
          title: 'Grafic calitate aer'
        },
        width: 1200,
        height: 500,
        vAxis: {
          title: 'Calitate'
        },
        axes: {
          x: {
            0: {side: 'Bottom'}
          }
        }
      };

      var chart = new google.charts.Line(document.getElementById('line_top_x'));

      chart.draw(data, google.charts.Line.convertOptions(options));
    


    }
    </script>

    <script>
    google.charts.load('current', {packages: ['corechart', 'line']});
google.charts.setOnLoadCallback(drawBackgroundColor);

function drawBackgroundColor() {
      var data = new google.visualization.DataTable();
      data.addColumn('string', 'data');
      data.addColumn('number', 'E2 - Tecuci, Galati');
      data.addColumn('number', 'minim');
      data.addColumn('number', 'maxim');

      data.addRows([
        <?php
        if(mysqli_num_rows($rezult)> 0){
           while($row1 = mysqli_fetch_array($rezult)){
             echo "['".$row1['data']."', ".(int)$row1['calitate'].", ".$info2['minim'].", ".$info2['maxim']."],";
            }
          }
        ?>
        
      ]);

      var options = {
        width: 1200,
        height: 500,
        hAxis: {
          title: 'Data'
        },
        vAxis: {
          title: 'Calitate'
        },
        backgroundColor: '#f1f8e9'
      };

      var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
      chart.draw(data, options);
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
        <li class="active"><a href="admin_grafice.php">Grafice</a></li>
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
      <div id="line_top_x"></div>
      <br>
      <div id="chart_div"></div>
      <br>
      
      
    </div>

    <div class="col-xs-4">
    </div>
    </div>

  </div>

</div>
	</body>
</html>

<!--if(mysqli_num_rows($result)> 0){
      //   while($row = mysqli_fetch_array($result)){
      //     echo "";
      //   }
      // }
      // -->