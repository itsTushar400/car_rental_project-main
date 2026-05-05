<?php
require_once('connection.php');

if(isset($_POST['method'])){

$booking_id = $_POST['booking_id'];
$method = $_POST['method'];

$card_name = $_POST['card_name'];
$card_number = substr($_POST['card_number'], -4);
$upi_id = $_POST['upi_id'];

/* DEBUG (temporary) */
echo "Booking ID: ".$booking_id; 
// remove later

/* INSERT */
mysqli_query($con,"INSERT INTO payment 
(BOOK_ID, payment_method, card_name, card_number, upi_id)
VALUES 
('$booking_id','$method','$card_name','$card_number','$upi_id')");

header("Location: bookingstatus.php?id=".$booking_id);

}
?>