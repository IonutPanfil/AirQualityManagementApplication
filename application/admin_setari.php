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

  if(isset($_POST["edit"]))
  {
    $emailnou = $_POST["email"];
    $parola = $_POST["parola"];
    $parolanoua = $_POST["parolanoua"];
    $parolanouarep = $_POST["parolanouarep"];

    if($emailnou !== "" && $parola === "" && $parolanoua === "" && $parolanouarep === ""){
    try{
      $updateEm = "UPDATE conturi SET email = '$emailnou' WHERE id = '$idClient'";
      $stmtUpdEm = $connect->prepare($updateEm);
      $stmtUpdEm->execute();
      alert("Emailul a fost modificat");
    } catch(PDOException $e){
      echo $e->getmessage();
      exit();
    }
  }

    if($parolanoua !== $parolanouarep){
      alert("Noile parole nu coincid");
    }
    else{
      try{
        $queryParolaCurenta = "SELECT COUNT(parola) AS num FROM conturi WHERE id = '$idClient' AND parola = '$parola'";
        $statmtParolaCurenta = $connect->prepare($queryParolaCurenta);
        $statmtParolaCurenta->bindValue(':id', $idClient);
        $statmtParolaCurenta->bindValue(':parola', $parola);
        $statmtParolaCurenta->execute(
          array(
            ':id' => $idClient,
            ':parola' => $parola
          )
        );
        $countParolaCurenta = $statmtParolaCurenta->fetch(PDO::FETCH_ASSOC);
        if($countParolaCurenta['num'] > 0)
			  {
          if($emailnou === ""){
            try{
              $updateParolaCurenta = "UPDATE conturi SET parola = '$parolanoua' WHERE id = '$idClient'";
              $stmtUpdPar = $connect->prepare($updateParolaCurenta);
              $stmtUpdPar->execute();
              alert("Parola a fost modificata");
            } catch(PDOException $e){
              echo $e->getmessage();
              exit();
            }
          }
          if($emailnou !== "" && $parolanoua !== "")
          {
            try{
              $updateParolaCurenta1 = "UPDATE conturi SET parola = '$parolanoua' WHERE id = '$idClient'";
              $updateEmail = "UPDATE conturi SET email = '$emailnou' WHERE id = '$idClient'";
              $stmtUpdPar1 = $connect->prepare($updateParolaCurenta1);
              $stmtUpdEmail = $connect->prepare($updateEmail);
              $stmtUpdPar1->execute();
              $stmtUpdEmail->execute();
              alert("Parola si email-ul au fost modificate cu succes");          
            } catch(PDOException $e){
              echo $e->getmessage();
              exit();
          }
          }
        }
        else{
          if($emailnou === ""){
          alert("Nu ai tastat corect parola curenta");
          }
        }
      } catch(PDOException $e){
        echo $e->getmessage();
        exit();
      }
  }
}

try{
  $query_echip = "SELECT id, localizare FROM echipamente";
  $stmt_echip = $connect->prepare($query_echip);
  $stmt_echip->execute();
  $echip = $stmt_echip->fetchAll();
} catch(PDOException $e){
  echo $e->getmessage();
  exit();
}

if(isset($_POST["setare"]))
  { 
    try{
      //se salveaza in variabile valorile introduse in acele campuri
      $selectieEchip = $_POST['echipament'];
      $idCont = $info['id'];
      //se verifica daca exista deja limite pentru echipamentul selectat
      $query_verifEch = "SELECT COUNT(cont_calitate.id) AS verificare, cont_calitate.idechip AS idE
      FROM cont_calitate, echipamente, conturi
      WHERE cont_calitate.idcont = conturi.id AND cont_calitate.idechip = echipamente.id 
          AND conturi.id = '$idCont' AND echipamente.localizare = '$selectieEchip'";
      $stmt_verifEch = $connect->prepare($query_verifEch);
      $stmt_verifEch->bindValue(':conturi.id', $idCont);
			$stmt_verifEch->bindValue(':echipamente.localizare', $selectieEchip);
			$stmt_verifEch->execute(
				array(
					':conturi.id' => $idCont,
					':echipamente.localizare' => $selectieEchip
				)
			);
      $verifEch = $stmt_verifEch->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e){
      echo $e->getmessage();
      exit();
    }
    if($verifEch['verificare'] > 0){
      try{
        $selectieEchipid = $verifEch['idE'];
        $idCont = $info['id'];
        $valmin = $_POST['vminim'];
        $valmax = $_POST['vmaxim'];
        $media = $_POST['vmedie'];
        //se actualizeaza in baza de date noile valori pentru limitele minima, maxima si media pentru echipamentul selectat
        $update_valori = "UPDATE cont_calitate SET v_medie = '$media', v_minim = '$valmin', v_maxim = '$valmax' WHERE 
                          idcont = '$idCont' AND idechip = '$selectieEchipid'";
        $stmt_valori = $connect->prepare($update_valori);
        $stmt_valori->execute();
        alert('Au fost setate valorile echipamentului '.$selectieEchip);
      } catch(PDOException $e){
        echo $e->getmessage();
        exit();
      }
    } else {
      try{
        $selectieEchipid = $verifEch['idE'];
        $idCont = $info['id'];
        $valmin = $_POST['vminim'];
        $valmax = $_POST['vmaxim'];
        $media = $_POST['vmedie'];
        //se insereaza in baza de date pentru echipamentul selectat noile valori pentru minima, maxima si medie
        $insert_valori = "INSERT INTO cont_calitate(idcont, idechip, v_medie, v_minim, v_maxim) VALUES((SELECT id FROM conturi WHERE id = '$idCont'), 
        (SELECT id FROM echipamente WHERE localizare = '$selectieEchip'), '$media', '$valmin', '$valmax')";
        $stmt_nvalori = $connect->prepare($insert_valori);
        $stmt_nvalori->execute();
        alert('Au fost setate valorile echipamentului '.$selectieEchip);
      } catch(PDOException $e){
        echo $e->getmessage();
        exit();
      }
    }
  }

  

?>

<html>
    <title>Setari</title>
	<head>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.bootstrapvalidator/0.5.0/css/bootstrapValidator.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		
	<link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">

	<link rel="stylesheet" href="styles/admin_index.css">
  <link rel="stylesheet" href="styles/admin_setari.css">
	<script src="javascripts/admin_index.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="https://cdn.zingchart.com/zingchart.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-validator/0.4.5/js/bootstrapvalidator.min.js"></script>
	</head>

	<body>
	<nav class="navbar navbar-default">

  <div class="navbar-header">

    <a class="navbar-brand" href="admin_index.php"><?php echo $user?></a>

  </div>

  <ul class="nav navbar-nav">

    <li><a href="admin_index.php">Acasa</a></li>
    <li><a href="admin_mesaje.php">Mesaje</a></li>
    <li class="active"><a href="admin_setari.php">Setari</a></li>
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

      <div class="container">

    <form class="well form-horizontal" action=" " method="post"  id="contact_form">
<fieldset>

<!-- Form Name -->
<legend>Editeaza profilul!</legend>

<!-- Text input-->
       <div class="form-group">
  <label class="col-md-4 control-label">Noul E-Mail</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
  <input name="email" placeholder="Adresa noua de e-mail" class="form-control"  type="text">
    </div>
  </div>
</div>

<!-- Text area -->
  
<div class="form-group">
  <label class="col-md-4 control-label">Parola curenta</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <input name="parola" placeholder="Introdu parola curenta" class="form-control"  type="password">
  </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label">Noua parola</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <input name="parolanoua" placeholder="Introdu parola noua" class="form-control"  type="password">
  </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label">Noua parola repeta</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <input name="parolanouarep" placeholder="Repeta parola noua" class="form-control"  type="password">
  </div>
  </div>
</div>



<!-- Success message -->
<div class="alert alert-success" role="alert" id="success_message">Success <i class="glyphicon glyphicon-thumbs-up"></i> Thanks for contacting us, we will get back to you shortly.</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <input type="submit" class="btn btn-warning" name="edit" value="Editeaza">
  </div>
</div>

</fieldset>
</form>
<!-- <select name="Year" size="">
        <option>Year</option>
        <option>2013</option>
        <option>2012</option>
        <option>2011</option>
    </select><br /><br /> -->

    <fieldset>

<!-- Form Name -->
<form class="well form-horizontal" action=" " method="post"  id="contact_form">
<legend>Seteaza limitelele echipamentelor</legend>

<!-- Text input-->
       <div class="form-group">
  <label class="col-md-4 control-label">Echipamentul</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <select name="echipament" size="">
        <?php
        foreach($echip as $ec){
          echo "<option>".$ec['localizare']."</option>";
        }
        ?>
    </select>
    </div>
  </div>
</div><br>

<!-- Text area -->
  
<div class="form-group">
  <label class="col-md-4 control-label">Valoare minima</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <select name="vminim" size="">
        <option>50</option>
        <option>55</option>
        <option>60</option>
        <option>65</option>
        <option>70</option>
        <option>75</option>
        <option>80</option>
        <option>85</option>
        <option>90</option>
        <option>95</option>
        <option>100</option>
        <option>110</option>
        <option>120</option>
        <option>130</option>
        <option>140</option>
        <option>150</option>
        <option>160</option>
        <option>170</option>
        <option>180</option>

    </select>
  </div>
  </div>
</div><br>

<div class="form-group">
  <label class="col-md-4 control-label">Valoare maxima</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <select name="vmaxim" size="">
        <option>60</option>
        <option>70</option>
        <option>80</option>
        <option>90</option>
        <option>100</option>
        <option>110</option>
        <option>120</option>
        <option>125</option>
        <option>130</option>
        <option>135</option>
        <option>140</option>
        <option>145</option>
        <option>150</option>
        <option>155</option>
        <option>160</option>
        <option>165</option>
        <option>170</option>
        <option>175</option>
        <option>180</option>
    </select>
  </div>
  </div>
</div><br>

<div class="form-group">
  <label class="col-md-4 control-label">Valoare medie</label>
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pencil"></i></span>
        <select name="vmedie" size="">
        <option>50</option>
        <option>60</option>
        <option>70</option>
        <option>80</option>
        <option>90</option>
        <option>100</option>
        <option>110</option>
        <option>120</option>
        <option>130</option>
        <option>130</option>
        <option>140</option>
        <option>150</option>
        <option>160</option>
        <option>170</option>
        <option>180</option>
    </select>
  </div>
  </div>
</div><br>



<!-- Success message -->
<div class="alert alert-success" role="alert" id="success_message">Success <i class="glyphicon glyphicon-thumbs-up"></i> Thanks for contacting us, we will get back to you shortly.</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <center><input type="submit" class="btn btn-warning" name="setare" value="Seteaza"></center>
  </div>
</div>

</fieldset>
</form>
</div>
    </div><!-- /.container -->

      </div>

    </div>

    <div class="col-xs-4">

      <div id='myChart1'></div>

    </div>

  </div>

</div>
	</body>
</html>