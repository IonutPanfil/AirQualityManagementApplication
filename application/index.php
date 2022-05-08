<!doctype html>

<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Calitatea aerului</title>
  </head>
  
<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once "dbconfig.php";

$query = "SELECT * FROM calitateaer";

$result = mysqli_query($conn,$query) or die("Select Query Failed.");

$count = mysqli_num_rows($result);

if($count > 0)
{
  $row=mysqli_fetch_all($result, MYSQLI_ASSOC);
  echo json_encode($row);
}
else {
  echo json_encode(array("message" => "No Data Found." , "status" => false));

}
?>

<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

//Creating Array for JSON response
$response = array();

// Check if we got the field from the user
//here, we're getting only one parameter(puissance) from raspberry
if (isset($_POST['data']))  {

// Include database connection
// all your database credentials(hostname, dbname, usrname,...) should
//be written in db_connect.php
//file
require_once "dbconfig.php";

$data = $_POST['data'];
$calitate= $_POST['calitate'];
$idrasp = $_POST['idrasp'];

// SQL query to insert data in sensorpower1
$sql = "INSERT INTO calitateaer(data, calitate, idrasp) VALUES('$data','$calitate', '$idrasp');";
$result =  mysqli_query($conn, $sql);

// Check for succesfull execution of query
if ($result) {
// successfully inserted
$response["success"] = 1;
$response["message"] = "Date adaugate cu succes.";

// Show JSON response
echo json_encode($response);
} else {
// Failed to insert data in database
$response["success"] = 0;
$response["message"] = "A avut loc o eroare.";

// Show JSON response
echo json_encode($response);
}
} else {
// If required parameter is missing
$response["success"] = 0;
$response["message"] = "Parametrul lipseste.";

// Show JSON response
echo json_encode($response);
}
?>


<!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  </body>
</html>

