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


  $countere1 = 0;
  $counterep1 = 0;
  $countere2 = 0;
  $counterep2 = 0;

  if(isset($_POST['adaugaE'])){
    //in cazul in care unul din cele trei campuri este gol nu se va putea efectua inserarea
    if($_POST['locatia'] === "" || $_POST['lat'] === "" || $_POST['long'] === "" ){
      alert('Nu ai completat toate campurile');
    } else {
    try{
      //se salveaza valorile din cele trei campuri in variabile
      $localizare = $_POST['locatia'];
      $latitudine = $_POST['lat'];
      $longitudine = $_POST['long'];
      //inserarea in baza de date folosing prepared statement
      $adaugaEchipament = "INSERT INTO echipamente(localizare, latitudine, longitudine) VALUES('$localizare', '$latitudine', '$longitudine')";
      $statement_adauga = $connect->prepare($adaugaEchipament);
			$statement_adauga->bindValue(':localizare', $localizare);
      $result_adauga = $statement_adauga->execute();
      //daca inserarea s-a efectuat cu succes administratorul va fi anuntat
      if($result_adauga){
        echo '<script>alert("Echipament adaugat cu succes");</script>';
      }
    }
    catch(PDOException $e){
      echo $e->getmessage();
          exit();
    }
  }
}

  if(isset($_POST['stergeE'])){
    try{
      $localizare1 = $_POST['locatiaE'];
      $stergeEchipament = "DELETE FROM echipamente WHERE localizare = '$localizare1'";
      $statement_sterge = $connect->prepare($stergeEchipament);
			$statement_sterge->bindValue(':localizare', $localizare1);
      $result_sterge = $statement_sterge->execute();
      if($result_sterge){
        echo '<script>alert("Echipament sters cu succes");</script>';
      }
    }
    catch(PDOException $e){
      echo $e->getmessage();
          exit();
    }
  }

  try{
    $query_echipT = "SELECT id, localizare
    FROM echipamente";
    $stmt_echipT = $connect->prepare($query_echipT);
    $stmt_echipT->execute();
    $echipT = $stmt_echipT->fetchAll();

  } catch(PDOException $e){
    echo $e->getmessage();
        exit();
  }

  $connection = mysqli_connect('localhost:3306', 'root', '', 'calitate_aer');
  $idCont = $info['id'];
  try{
    
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
    <title>Echipamente</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <meta charset="utf-8">
	<link rel="stylesheet" href="">
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

    <script>
    function stergeValoareT(delid){
      if(confirm("Doresti sa stergi echipamentul?")){
        window.location.href='deleteEchip.php?del_id=' + delid + '';
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
          <a href="admin_cereri.php">Cereri</a>
        </div>

        <div class="panel-body">
        <li class="active"><a href="admin_echip.php">Echipamente</a></li>
        </div>

      </div>

    </div>

    <div class="col-xs-6">

      <div id='myChart'>
      <div class="panel panel-default">
                <div class="panel-heading"></div>
                        <div id="map-canvas" style="width: 700px; height: 600px;"></div>
                    
            </div>
      <br><br><br>
      
    </div>
    
    </div>
    

  </div>

  <center><form action='admin_echip.php' id='deleteBox' method ='post'>
  <label for="fname"><h2>Adauga echipament:</h2></label><br>
  <input type="text" id="locatia" name="locatia" placeholder="Introdu locatia noului echipament" style="width: 50%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #ffffff;"><br>
  <input type="text" id="lat" name="lat" placeholder="Introdu latitudinea noului echipament" style="width: 50%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #ffffff;"><br>
  <input type="text" id="long" name="long" placeholder="Introdu longitudinea noului echipament" style="width: 50%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: #ffffff;"><br>
  <input name ='adaugaE' class ='adaugaE' type ='submit' value='Adauga echipament' style="width: 50%;
    padding: 15px;
    margin: 5px 0 22px 0;
    display: inline-block;
    border: none;
    background: green;">


  <h2>Tabel de control al echipamentelor:</h2>
      <div class="tableFixHead">
      <table id="datatables-buttons" class="table table-striped" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Localizare</th>
            <th>Sterge</th>
          </tr>
        </thead>
        <tbody>
        <?php
        foreach($echipT as $eT){
         echo"
		        <tr>
              <td>".$eT['id']."</td>
              <td>".$eT['localizare']."</td>
              <td><form action='admin_echip.php' id='deleteBox' method ='post'><input name ='sterge' class ='sterge' type ='button' onclick='stergeValoareT(".$eT['id'].")' value='Sterge'></form></td>
		        </tr>";
        }
        ?>
        </tbody>
      </table>
    </div>



  <div id="chart_div" style="width:100%;"></div>
      <br><br><br>
      <!-- <h2>Tabel de valori al echipamentului 1:</h2>
      <div  class="tableFixHead">
      <table>
        <thead>
          <tr>
            <th>Localizare</th>
            <th>Data</th>
            <th>Valoare(calitate calculata dupa detectarea de: amoniac, aburi benzina, fum si alte gaze daunatoare)</th>
          </tr>
        </thead>
        <tbody> -->
        <?php
        // foreach($echip1 as $e1){
        //   $countere1 += 1;
        //   $counterep1 += $e1['valoare'];
        //  echo"
		    //     <tr>
        //       <td>".$e1['localizare']."</td>
			  //       <td>".$e1['datav']."</td>
			  //       <td>".$e1['valoare']."</td>
        //     </tr>";
        // }
        // $media1 = $counterep1 / $countere1;
        // echo"
        // <tr>
        //   <td style='background-color: gray;'>Media:".$media1."</td>
        //   <td style='background-color: gray;'>Valoare minima:".$echip1min["minim"]."</td>
        //   <td style='background-color: gray;'>Valoare maxima:".$echip1max["maxim"]."</td>
        // </tr>";
        ?>
        <!-- </tbody>
      </table>
    </div> -->

      <!-- <h2>Tabel de valori al echipamentului 2:</h2>
      <div class="tableFixHead">
      <table>
        <thead>
          <tr>
          <th>Localizare</th>
            <th>Data</th>
            <th>Valoare(calitate calculata dupa detectarea de: amoniac, aburi benzina, fum si alte gaze daunatoare)</th>
          </tr>
        </thead>
        <tbody>
        <?php
        // foreach($echip2 as $e2){
        //   $countere2 += 1;
        //   $counterep2 += $e2['valoare'];
        //  echo"
		    //     <tr>
        //       <td>".$e2['localizare']."</td>
			  //       <td>".$e2['datav']."</td>
			  //       <td>".$e2['valoare']."</td>
        //     </tr>";
        //   }
        //   $media2 = $counterep2 / $countere2;
        // echo"
        // <tr>
        //   <td style='background-color: gray;'>Media:".$media2."</td>
        //   <td style='background-color: gray;'>Valoare minima:".$echip2min["minim"]."</td>
        //   <td style='background-color: gray;'>Valoare maxima:".$echip2max["maxim"]."</td>
        // </tr>";

        ?>

        </tbody>
      </table>
      </center>
    </div> -->
    </center>

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


    </div>
  <div class="col-xs-4">

      <div id='myChart1'></div>
      
    </div>

</div>


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
// trimiterea mail-ului daca ora sistemului va fi ora setata
if(date('H:i') === '11:22'){
  //in cazul in care media rezultata este mai mica va trimite mail cu notificarea ce anunta 
  if($media1 <= $info1['medie']){
  $mailr = "m.calitateaer@gmail.com";
  $sub = "Notificare media valori calitate aer zilnica";
  $message = "Media de pe echipamentul 1 este sub limita de".$info1['medie']." Valoarea curenta a mediei este: ".$media1;
  mail($mailr, $sub, $message);
  }
  //in cazul contrar se va trimite mail ce anunta ca media este limite normale 
  else {
  $mailr = "m.calitateaer@gmail.com";
  $sub = "Notificare media valori calitate aer zilnica";
  $message = "Media de pe echipamentul 1 este in intervalul dorit. Valoarea mediei este: '$media1'";
  mail($mailr, $sub, $message);
  }
  //in cazul in care media rezultata este mai mica va trimite mail cu notificarea ce anunta 
  if($media2 <= $info2['medie']){
    $mailr2 = "m.calitateaer@gmail.com";
    $sub2 = "Notificare media valori calitate aer zilnica";
    $message2 = "Media de pe echipamentul 2 este sub limita de".$info2['medie']." Valoarea curenta a mediei este: ".$media2;
    mail($mailr2, $sub2, $message2);
    }
    //in cazul contrar se va trimite mail ce anunta ca media este limite normale 
    else {
    $mailr2 = "m.calitateaer@gmail.com";
    $sub2 = "Notificare media valori calitate aer zilnica";
    $message2 = "Media de pe echipamentul 2 este in intervalul dorit. Valoarea mediei este: '$media2'";
    mail($mailr2, $sub2, $message2);
    }
  }
?>