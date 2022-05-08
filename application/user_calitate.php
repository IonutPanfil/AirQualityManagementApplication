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


	try{
		$query_echip1 = "SELECT echipamente.localizare AS localizare, calitateaer.data AS datav, calitateaer.calitate AS valoare, echipamente.id AS id
		FROM calitateaer, echipamente
		WHERE calitateaer.idrasp = echipamente.id AND echipamente.id = 1
		ORDER BY data DESC
		LIMIT 24";
		$stmt_echip1 = $connect->prepare($query_echip1);
		$stmt_echip1->execute();
		$echip1 = $stmt_echip1->fetchAll();
	
	  } catch(PDOException $e){
		echo $e->getmessage();
			exit();
	  }
	
	  try{
		$query_echip2 = "SELECT echipamente.localizare AS localizare, calitateaer.data AS datav, calitateaer.calitate AS valoare, echipamente.id AS id
		FROM calitateaer, echipamente
		WHERE calitateaer.idrasp = echipamente.id AND echipamente.id = 2
		ORDER BY data DESC
		LIMIT 24";
		$stmt_echip2 = $connect->prepare($query_echip2);
		$stmt_echip2->execute();
		$echip2 = $stmt_echip2->fetchAll();
	
	  } catch(PDOException $e){
		echo $e->getmessage();
			exit();
	  }
	  $countere1 = 0;
  	$counterep1 = 0;
  	$countere2 = 0;
  	$counterep2 = 0;

	  $connection = mysqli_connect('localhost:3306', 'root', '', 'calitate_aer');
  $result = mysqli_query($connection, "SELECT data, calitate FROM calitateaer WHERE idrasp = 1 ORDER BY data ASC");
  $rezult = mysqli_query($connection, "SELECT data, calitate FROM calitateaer WHERE idrasp = 2 ORDER BY data ASC");

  try{
    $idCont = $info['id'];
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
		$info1 = $stmt_valori->fetch(PDO::FETCH_ASSOC);

		
	} catch(PDOException $e){
		echo $e->getmessage();
		exit();
	}
  
  try{
    $idCont = $info['id'];
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

  try{
    $query_echip1min = "SELECT MIN(calitate) AS minim
    FROM calitateaer, echipamente
    WHERE calitateaer.idrasp = echipamente.id AND calitateaer.idrasp = 1
    ORDER BY data DESC
    LIMIT 24";
    $stmt_echip1min = $connect->prepare($query_echip1min);
    $stmt_echip1min->execute();
    $echip1min = $stmt_echip1min->fetch(PDO::FETCH_ASSOC);

  } catch(PDOException $e){
    echo $e->getmessage();
        exit();
  }

  try{
    $query_echip1max = "SELECT MAX(calitate) AS maxim
    FROM calitateaer, echipamente
    WHERE calitateaer.idrasp = echipamente.id AND calitateaer.idrasp = 1
    ORDER BY data DESC
    LIMIT 24";
    $stmt_echip1max = $connect->prepare($query_echip1max);
    $stmt_echip1max->execute();
    $echip1max = $stmt_echip1max->fetch(PDO::FETCH_ASSOC);

  } catch(PDOException $e){
    echo $e->getmessage();
        exit();
  }

  try{
    $query_echip2min = "SELECT MIN(calitate) AS minim
    FROM calitateaer, echipamente
    WHERE calitateaer.idrasp = echipamente.id AND calitateaer.idrasp = 2 
    ORDER BY data DESC
    LIMIT 24";
    $stmt_echip2min = $connect->prepare($query_echip2min);
    $stmt_echip2min->execute();
    $echip2min = $stmt_echip2min->fetch(PDO::FETCH_ASSOC);

  } catch(PDOException $e){
    echo $e->getmessage();
        exit();
  }

  try{
    $query_echip2max = "SELECT MAX(calitate) AS maxim
    FROM calitateaer, echipamente
    WHERE calitateaer.idrasp = echipamente.id AND calitateaer.idrasp = 2
    ORDER BY data DESC
    LIMIT 24";
    $stmt_echip2max = $connect->prepare($query_echip2max);
    $stmt_echip2max->execute();
    $echip2max = $stmt_echip2max->fetch(PDO::FETCH_ASSOC);

  } catch(PDOException $e){
    echo $e->getmessage();
        exit();
  }
?>

<html>
    <title>Calitate aer</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
	<link rel="stylesheet" href="styles/user_index.css">
	
	<link rel="stylesheet" href="styles/admin_echip.css"> 
  <script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://www.jqueryscript.net/demo/Export-Html-Table-To-Excel-Spreadsheet-using-jQuery-table2excel/src/jquery.table2excel.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<!-- Jquery needed -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="javascripts/user_index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="js/scripts.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <link rel="stylesheet" href="styles/admin_echip.css">  
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous"> -->
		
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

  



	<link rel="stylesheet" href="styles/admin_index.css">
	<script src="javascripts/admin_index.js"></script>
  <script src="javascripts/admin_echip.js"></script>
<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="https://www.jqueryscript.net/demo/Export-Html-Table-To-Excel-Spreadsheet-using-jQuery-table2excel/src/jquery.table2excel.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script> -->
<!-- <script src='assets/js/jquery-1.10.1.min.js'></script>        -->
    <!-- <script src="assets/js/bootstrap.min.js"></script> -->
    <script src="http://maps.google.com/maps/api/js?key=AIzaSyCzlShruXn80kOhzxNR7OIM9e9oBBW0pWY"></script>
  <script>
        
        var marker;
          function initialize() {
            var infoWindow = new google.maps.InfoWindow;
            
            var mapOptions = {
              mapTypeId: google.maps.MapTypeId.ROADMAP
            } 
     
            var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
            var bounds = new google.maps.LatLngBounds();
    
            // Retrieve data from database
            <?php
                $query = mysqli_query($connection,"select * from echipamente");
                while ($data = mysqli_fetch_array($query))
                {
                    $nama = $data['localizare'];
                    $lat = $data['latitudine'];
                    $lon = $data['longitudine'];
                    
                    echo ("addMarker($lat, $lon, '<b>$nama</b>');\n");                        
                }
              ?>
              
            // Proses of making marker 
            function addMarker(lat, lng, info) {
                var lokasi = new google.maps.LatLng(lat, lng);
                bounds.extend(lokasi);
                var marker = new google.maps.Marker({
                    map: map,
                    position: lokasi
                });       
                map.fitBounds(bounds);
                bindInfoWindow(marker, map, infoWindow, info);
             }
            
            // Displays information on markers that are clicked
            function bindInfoWindow(marker, map, infoWindow, html) {
              google.maps.event.addListener(marker, 'click', function() {
                infoWindow.setContent(html);
                infoWindow.open(map, marker);
              });
            }
     
            }
          google.maps.event.addDomListener(window, 'load', initialize);
        
        </script>

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
             echo "['".$row['data']."', ".(int)$row['calitate'].", ".$info1['minim'].", ".$info1['maxim']."],";
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

      var chart = new google.visualization.LineChart(document.getElementById('chart_div1'));
      chart.draw(data, options);
    }
    </script>

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
<div class="panel panel-default">
                <div class="panel-heading"></div>
                        <div id="map-canvas" style="width: 100%; height: 600px;"></div>
                    
            </div>
            <div class="tableFixHead">
    <h1>Tabel de valori al echipamentului 1:</h1>
		<table class="table1" id="table2excel1">
      <thead>
        <tr class="noExl1">
        <th>Localizare</th>
            <th>Data</th>
            <th>Valoare(calitate calculata dupa detectarea de: amoniac, aburi benzina, fum si alte gaze daunatoare)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach($echip1 as $e1){
          $countere1 += 1;
          $counterep1 += $e1['valoare'];
         echo"
		        <tr class='active'>
              <td>".$e1['localizare']."</td>
			        <td>".$e1['datav']."</td>
			        <td>".$e1['valoare']."</td>
            </tr>";
          }
          $media1 = $counterep1 / $countere1;
        echo"
        <tr>
          <td style='background-color: gray;'>Media:".$media1."</td>
          <td style='background-color: gray;'>Valoare minima:".$echip1min["minim"]."</td>
          <td style='background-color: gray;'>Valoare maxima:".$echip1max["maxim"]."</td>
        </tr>";
        ?>
      </tbody>
    </table>
        <button id= "btnep1" class="btn btn-success">CSV</button></div>
        <br><br>
      <div id="line_top_x"></div>

      <div class="tableFixHead">
    <h1>Tabel de valori al echipamentului 2:</h1>
		<table class="table2" id="table2excel2">
      <thead>
        <tr class="noExl2">
        <th>Localizare</th>
            <th>Data</th>
            <th>Valoare(calitate calculata dupa detectarea de: amoniac, aburi benzina, fum si alte gaze daunatoare)</th>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach($echip2 as $e2){
          $countere2 += 1;
          $counterep2 += $e2['valoare'];
         echo"
		        <tr class='active'>
              <td>".$e2['localizare']."</td>
			        <td>".$e2['datav']."</td>
			        <td>".$e2['valoare']."</td>
            </tr>";
          }
          $media2 = $counterep2 / $countere2;
        echo"
        <tr>
          <td style='background-color: gray;'>Media:".$media2."</td>
          <td style='background-color: gray;'>Valoare minima:".$echip2min["minim"]."</td>
          <td style='background-color: gray;'>Valoare maxima:".$echip2max["maxim"]."</td>
        </tr>";
        ?>
      </tbody>
    </table>
        <button id= "btnep2" class="btn btn-success">CSV</button></div>
    
  <br />
  <br />


      <div id="chart_div1"></div>
      <br>

	
	</body>
</html>

<script>
$("#btnep2").click(function(){
  $("#table2excel2").table2excel({
    // exclude CSS class
    exclude: ".noExl2",
    name: "Echipament 2",
    filename: "Echip2-csv" //do not include extension
  }); 
});

$("#btnep1").click(function(){
  $("#table2excel1").table2excel({
    // exclude CSS class
    exclude: ".noExl1",
    name: "Echipament 1",
    filename: "Echip1-csv" //do not include extension
  }); 
});
</script>

<?php
try{
  $query_conturi_mail = "SELECT email FROM conturi WHERE rol = 'client'";
  $stmt_conturi_mail = $connect->prepare($query_conturi_mail);
	$stmt_conturi_mail->execute();
	$conturi_mail = $stmt_conturi_mail->fetchAll();


} catch(PDOException $e){
  echo $e->getmessage();
  exit();
}

foreach($conturi_mail as $cm){
// mail sender
if(date('H:i') === '11:22'){
  if($media1 <= $info1['medie']){
  $mailr = $cm['email'];
  $sub = "Notificare media valori calitate aer zilnica";
  $message = "Media de pe echipamentul 1 este sub limita de".$info1['medie']." Valoarea curenta a mediei este: ".$media1;
  mail($mailr, $sub, $message);
  } else {
  $mailr = $cm['email'];
  $sub = "Notificare media valori calitate aer zilnica";
  $message = "Media de pe echipamentul 1 este in intervalul dorit. Valoarea mediei este: '$media1'";
  mail($mailr, $sub, $message);
  }
  if($media2 <= $info2['medie']){
    $mailr2 = $cm['email'];
    $sub2 = "Notificare media valori calitate aer zilnica";
    $message2 = "Media de pe echipamentul 2 este sub limita de".$info2['medie']." Valoarea curenta a mediei este: ".$media2;
    mail($mailr2, $sub2, $message2);
    } else {
    $mailr2 = $cm['email'];
    $sub2 = "Notificare media valori calitate aer zilnica";
    $message2 = "Media de pe echipamentul 2 este in intervalul dorit. Valoarea mediei este: '$media2'";
    mail($mailr2, $sub2, $message2);
    }
  }
}
?>