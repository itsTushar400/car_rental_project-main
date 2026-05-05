<?php
require_once('connection.php');

$driver_id = $_GET['id'];

/* Find pending booking */
$res = mysqli_query($con,"SELECT * FROM booking 
WHERE DRIVER_ID IS NULL LIMIT 1");

if(mysqli_num_rows($res) > 0){

    $booking = mysqli_fetch_assoc($res);
    $book_id = $booking['BOOK_ID'];

    /* Assign driver */
    mysqli_query($con,"UPDATE booking 
    SET DRIVER_ID='$driver_id' 
    WHERE BOOK_ID='$book_id'");

    /* Update driver status */
    mysqli_query($con,"UPDATE drivers 
    SET STATUS='Booked' 
    WHERE ID='$driver_id'");

    echo "<script>alert('Driver Assigned');window.location='drivers.php';</script>";

}else{
    echo "<script>alert('No Pending Booking');window.location='drivers.php';</script>";
}
?>