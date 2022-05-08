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

	if($info["rol"] === "admin"){
		header("location:admin_index.php");
	}

	if(isset($_POST["edit"]))
  {
	$parola = $_POST["parola"];
    $parolanoua = $_POST["parolanoua"];
    $parolanouarep = $_POST["parolanouarep"];
	$id = $info["id"];
	try{
		$queryParolaCurenta = "SELECT COUNT(parola) AS num FROM conturi WHERE id = '$id' AND parola = '$parola'";
        $statmtParolaCurenta = $connect->prepare($queryParolaCurenta);
        $statmtParolaCurenta->bindValue(':id', $id);
        $statmtParolaCurenta->bindValue(':parola', $parola);
        $statmtParolaCurenta->execute(
          array(
            ':id' => $id,
            ':parola' => $parola
          )
        );
        $countParolaCurenta = $statmtParolaCurenta->fetch(PDO::FETCH_ASSOC);
	} catch(PDOException $e){
		echo $e->getmessage();
		exit();
	}
	if($parola === "" || $parolanoua === "" || $parolanouarep ===""){
		alert("Nu ai completat toate campurile");
	} else{
		if($countParolaCurenta['num'] > 0)
		{
			if($parolanoua === $parolanouarep){
				try{
					$updateParolaCurenta = "UPDATE conturi SET parola = '$parolanoua' WHERE id = '$id'";
              		$stmtUpdPar = $connect->prepare($updateParolaCurenta);
              		$stmtUpdPar->execute();
              		alert("Parola a fost modificata");
				}catch(PDOException $e){
					echo $e->getmessage();
					exit();
				}
			} else{
				alert("Parola noua nu coincide");
				}
		} else {
			alert("Nu ai introdus parola curenta corecta");
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
		$selectieEchip = $_POST['echipament'];
		$idCont = $info['id'];
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
		  $update_valori = "UPDATE cont_calitate SET v_medie = '$media', v_minim = '$valmin', v_maxim = '$valmax' WHERE idcont = '$idCont' AND idechip = '$selectieEchipid'";
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
		  $insert_valori = "INSERT INTO cont_calitate(idcont, idechip, v_medie, v_minim, v_maxim) VALUES((SELECT id FROM conturi WHERE id = '$idCont'), (SELECT id FROM echipamente WHERE localizare = '$selectieEchip'), '$media', '$valmin', '$valmax')";
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
    <title>Setarile contului</title>
	<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="">
	<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css">
	<link rel="stylesheet" href="styles/user_index.css">
	<link rel="stylesheet" href="styles/user_setari.css">

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
<div class="info box">
  Nume: <?php echo $info['nume']; ?>
  </div>
  <div class="info box">
  Email: <?php echo $info['email']; ?>
  </div>
  <div class="info box">
  Rol: <?php echo $info['rol']; ?>
</div>


<form class="well form-horizontal" action=" " method="post"  id="contact_form">
<fieldset>

<!-- Form Name -->
<legend>Schimba parola:</legend>

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

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <input type="submit" class="btn btn-warning" name="edit" value="Schimba parola">
  </div>
</div>

</fieldset>
</form>

</div>

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

	
	</body>
</html>