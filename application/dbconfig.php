<?php

$DBhost="localhost:3306";
$DBuser="root";
$DBpassword="";
$DBname="calitate_aer";

$conn=mysqli_connect($DBhost,$DBuser,$DBpassword,$DBname);

if(!$conn){
  die("Connection failed: " . mysqli_connect_error());
}

 ?>
