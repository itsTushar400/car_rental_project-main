<?php
require_once('connection.php');

$id=$_GET['id'];

/* Example: assign driver to last booking */
mysqli_query($con,"UPDATE booking SET DRIVER_ID='$id' 
WHERE DRIVER_ID IS NULL LIMIT 1");

mysqli_query($con,"UPDATE drivers SET STATUS='Booked' WHERE ID='$id'");

header("Location: admindriver.php");
?>