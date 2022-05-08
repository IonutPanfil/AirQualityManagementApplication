<?php
require_once('connect.php');

$query_client = 'UPDATE conturi SET rol = "client" WHERE id="'.$_GET['C_id'].'"';
$stmt_client_cont = $connect->prepare($query_client);
$stmt_client_cont->bindValue(':id', $_GET['C_id']);
$stmt_client_cont->execute();
header("location:admin_conturi.php");
?>