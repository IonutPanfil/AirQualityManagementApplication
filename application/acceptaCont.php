<?php
require_once('connect.php');

$query_accepta = 'UPDATE conturi SET permisiune = "DA" WHERE id="'.$_GET['acc_id'].'"';
$stmt_accepta_cont = $connect->prepare($query_accepta);
$stmt_accepta_cont->bindValue(':id', $_GET['acc_id']);
$stmt_accepta_cont->execute();
header("location:admin_cereri.php");
?>