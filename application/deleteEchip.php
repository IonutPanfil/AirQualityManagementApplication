<?php
require_once('connect.php');

$query_delete = 'DELETE FROM echipamente WHERE id="'.$_GET['del_id'].'"';
$stmt_delete_echip = $connect->prepare($query_delete);
$stmt_delete_echip->bindValue(':id', $_GET['del_id']);
$stmt_delete_echip->execute();
header("location:admin_echip.php");
?>