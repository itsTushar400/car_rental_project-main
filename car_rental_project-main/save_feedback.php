<?php
require_once('connection.php');

$name = $_POST['name'];
$email = $_POST['email'];
$comment = $_POST['comments'];
$exp = $_POST['experience'];

mysqli_query($con,"INSERT INTO feedback (NAME, EMAIL, COMMENT, EXPERIENCE) 
VALUES ('$name','$email','$comment','$exp')");

header("Location: feedback.php?success=1");
?>