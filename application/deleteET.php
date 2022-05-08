<?php
require_once('connect.php');

$query_delete = 'DELETE FROM echipamente WHERE id="'.$_GET['del_idT'].'"';
$stmt_delete_cont = $connect->prepare($query_delete);
$stmt_delete_cont->bindValue(':id', $_GET['del_idT']);
$stmt_delete_cont->execute();
header("location:admin_echip.php");
?>