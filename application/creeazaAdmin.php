<?php
require_once('connect.php');

$query_admin = 'UPDATE conturi SET rol = "admin" WHERE id="'.$_GET['A_id'].'"';
$stmt_admin_cont = $connect->prepare($query_admin);
$stmt_admin_cont->bindValue(':id', $_GET['A_id']);
$stmt_admin_cont->execute();
header("location:admin_conturi.php");
?>