<?php
require_once('connect.php');

$query_respinge = 'UPDATE conturi SET permisiune = "NU" WHERE id="'.$_GET['resp_id'].'"';
$stmt_respinge_cont = $connect->prepare($query_respinge);
$stmt_respinge_cont->bindValue(':id', $_GET['resp_id']);
$stmt_respinge_cont->execute();
header("location:admin_cereri.php");
?>