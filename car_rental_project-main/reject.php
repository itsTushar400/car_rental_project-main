<?php
require_once('connection.php');

/* ================= VALIDATE ID ================= */
if(!isset($_GET['id']) || empty($_GET['id'])){
    die("Invalid Request");
}

$id = intval($_GET['id']); // SECURITY

/* ================= GET BOOKING ================= */
$booking = mysqli_query($con,"SELECT * FROM booking WHERE BOOK_ID='$id'");
$row = mysqli_fetch_assoc($booking);

if(!$row){
    die("Booking Not Found");
}

/* ================= ALREADY REJECTED ================= */
if($row['BOOK_STATUS'] == 'Rejected'){
    header("Location: adminbook.php");
    exit();
}

/* ================= FREE DRIVER (IMPORTANT) ================= */
if(!empty($row['DRIVER_ID'])){
    mysqli_query($con,"UPDATE drivers 
    SET STATUS='Available' 
    WHERE ID='".$row['DRIVER_ID']."'");
}

/* ================= UPDATE STATUS ================= */
mysqli_query($con,"
UPDATE booking 
SET BOOK_STATUS='Rejected' 
WHERE BOOK_ID='$id'
");

/* ================= REDIRECT ================= */
header("Location: adminbook.php");
exit();
?>