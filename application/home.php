<?php

session_start();
require_once('connect.php');

    if(isset($_POST["conectare"]))
	{
    if(empty($_POST["user"]) || empty($_POST["pass"]))
		{
			$message = '<label>All fields are required !</label>';
		}
    else
		{	if(isset($_POST["user"]))
			{
			$user = $_POST["user"];
			}
			if(isset($_POST["pass"]))
			{
			$pass = $_POST["pass"];
			}

      $query = "SELECT COUNT(email) AS num, rol,permisiune FROM conturi WHERE email = '$user' AND parola = '$pass'";
			$statement = $connect->prepare($query);
			$statement->bindValue(':email', $user);
			$statement->bindValue(':parola', $pass);
			$statement->execute(
				array(
					':email' => $user,
					':parola' => $pass
				)
			);
			$count = $statement->fetch(PDO::FETCH_ASSOC);
      $rol = $count['rol'];
			if($count['num'] > 0)
			{
        $permisiune = $count['permisiune'];
        if($rol == "admin" ){
          if($permisiune === "DA"){
				    $_SESSION["email"] = $_POST["user"];
				    header("location:admin_index.php");
          }
          else{
            header("location:login_failed.php");
          }
        }
        elseif($rol == "client"){
          if($permisiune === "DA"){
            $_SESSION["email"] = $_POST["user"];
				    header("location:user_index.php");
          }
          else{
            header("location:login_failed.php");
          }
        }
        
			}
			else
			{
				$message= '<label>Wrong username or password !</label>';
			}

    }
  }
?>

<html>
    <title>Acasa - Sistem de monitorizare al aerului</title>
    
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.2.3/animate.min.css">
    <link rel="stylesheet" href="styles/home.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <script src="javascripts/home.js"></script>
    </head>

    <body>

    <div class='box'>
  <div class='box-form'>
    <div class='box-login-tab'></div>
    <div class='box-login-title'>
      <div class='i i-login'></div><h2>LOGARE</h2>
    </div>
    <div class='box-login'>
      <div class='fieldset-body' id='login_form'>
      
        <button onclick="openLoginInfo();" class='b b-form i i-more' title='Mai multe informatii'></button>
        	<form id="loginform" method="post">
          <p class='field'>
          <label for='user'>E-MAIL</label>
          <input type='text' id='user' name='user' title='E-mail' />
          <span id='valida' class='i i-warning'></span>
        </p>
      	  <p class='field'>
          <label for='pass'>PAROLA</label>
          <input type='password' id='pass' name='pass' title='Parola' />
          <span id='valida' class='i i-close'></span>
        </p>

        	<input type='submit' id='do_login' value='Conectare' title='Conecteaza-te' name='conectare' />
      </form>

      <?php
		if(isset($message))
		{
			echo '<label class="text-danger" style="font-size:30px; color:red; text-transform:uppercase;">'.$message.'</label>';
		}

		?>

      </div>
    </div>
  </div>
  <div class='box-info'>
					    <p><button onclick="closeLoginInfo();" class='b b-info i i-left' title='Inchide'></button><h3>Ai nevoie de ajutor?</h3>
    </p>
					    <div class='line-wh'></div>
    					<!-- <button onclick="" class='b-support' title='Forgot Password?'> Ai uitat parola?</button> -->
    <button onclick="location.href='contactsupport.php'" class='b-support' title='Contact Support'> Contacteaza-ne</button>
    					<div class='line-wh'></div>
    <button onclick="location.href='register.php'" class='b-cta' title='Creeaza cont acum!'> CREEAZA CONT</button>
  				</div>
</div>
   
    </body>

</html>