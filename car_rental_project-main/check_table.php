<?php
$con = mysqli_connect('localhost','root','','carproject');
if(!$con) {
    die('Connection failed');
}
$result = mysqli_query($con, 'DESCRIBE booking');
while($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . ' - ' . $row['Type'] . PHP_EOL;
}
?>