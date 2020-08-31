<?php include('config.php'); ?>

<?php

echo $uuid = addslashes($_POST['uuid']);

$query = "DELETE FROM `properties` WHERE `uuid` = '$uuid'";

if(mysqli_multi_query($connect, $query)) { }

header('Location: ' . $_SERVER['HTTP_REFERER']);

?>
