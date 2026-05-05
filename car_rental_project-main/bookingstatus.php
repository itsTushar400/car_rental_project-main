<?php
require_once('connection.php');
session_start();

/* LOGIN CHECK */
if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['email'];

/* FETCH BOOKINGS WITH REFUND */
$sql = "
SELECT b.*, p.refund_status 
FROM booking b
LEFT JOIN payment p ON b.BOOK_ID = p.BOOK_ID
WHERE b.EMAIL='$user_email'
ORDER BY b.BOOK_ID DESC
";

$result = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Bookings</title>

<style>
body{
    font-family: Arial;
    margin:0;
    background:#f5f5f5;
}

/* NAVBAR */
.navbar{
    background:#111;
    color:white;
    padding:15px 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.navbar h2{
    color:#ff6600;
}
.navbar a{
    color:white;
    text-decoration:none;
    margin-left:15px;
}
.navbar a:hover{
    color:#ff6600;
}

/* CONTAINER */
.container{
    width:90%;
    margin:auto;
    margin-top:30px;
    background:white;
    padding:20px;
    border-radius:10px;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#ff6600;
    color:white;
    padding:10px;
}

td{
    padding:10px;
    text-align:center;
    border-bottom:1px solid #ddd;
}

/* BUTTONS */
.pay-btn{
    background:green;
    color:white;
    padding:6px 12px;
    border:none;
    border-radius:5px;
}

.invoice-btn{
    background:#ff6600;
    color:white;
    padding:6px 12px;
    border:none;
    border-radius:5px;
}

.done{
    color:green;
    font-weight:bold;
}

.refunded{
    color:red;
    font-weight:bold;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
    <h2>CaRs</h2>
    <div>
        <a href="cardetails.php">Home</a>
        <a href="bookingstatus.php">My Bookings</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<!-- MAIN -->
<div class="container">
<h2 style="text-align:center;">My Bookings</h2>

<table>
<tr>
<th>ID</th>
<th>Place</th>
<th>Destination</th>
<th>Price</th>
<th>Status</th>
<th>Payment</th>
<th>Pay</th>
<th>Rating</th>
<th>Invoice</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){

    $bid = $row['BOOK_ID'];

    $status = $row['BOOK_STATUS'] ?? 'Pending';
    $payment = $row['PAYMENT'] ?? 'Not Paid';
    $refund = $row['refund_status'] ?? '';

    $isPaid = ($payment == 'Paid');
?>

<tr>

<td><?php echo $bid; ?></td>
<td><?php echo $row['BOOK_PLACE'] ?? 'N/A'; ?></td>
<td><?php echo $row['DESTINATION'] ?? 'N/A'; ?></td>
<td>₹<?php echo $row['PRICE']; ?></td>

<!-- STATUS -->
<td>
<?php
if($refund == "Refunded"){
    echo "<span class='refunded'>↺ Refunded</span>";
}
elseif($status == 'Approved'){
    echo "<span style='color:green;'>✔ Approved</span>";
}
elseif($status == 'Returned'){
    echo "<span style='color:blue;'>↩ Returned</span>";
}
elseif($status == 'Rejected'){
    echo "<span style='color:red;'>✖ Rejected</span>";
}
else{
    echo "<span style='color:orange;'>Pending</span>";
}
?>
</td>

<!-- PAYMENT -->
<td>
<?php
if($refund == "Refunded"){
    echo "<span class='refunded'>Refunded</span>";
}
elseif($isPaid){
    echo "<span style='color:green;'>✔ Paid</span>";
}
else{
    echo "<span style='color:red;'>❌ Not Paid</span>";
}
?>
</td>

<!-- PAY -->
<td>
<?php if(!$isPaid && $refund != "Refunded"){ ?>
    <a href="payment.php?id=<?php echo $bid; ?>">
        <button class="pay-btn">Pay</button>
    </a>
<?php } else { ?>
    <span class="done">Done</span>
<?php } ?>
</td>

<td>
<?php if($row['status'] == 'Approved'){ ?>

<a href="feedback.php?booking_id=<?php echo $row['BOOK_ID']; ?>">
    <button>Rate</button>
</a>

<?php } else { ?>
    N/A
<?php } ?>
</td>

<!-- INVOICE (FINAL FIX 🔥) -->
<td>
<?php 
if(($isPaid || $refund=="Refunded") && ($status == 'Approved' || $status == 'Returned')){ 
?>
    <a href="invoice.php?id=<?php echo $bid; ?>">
        <?php if($refund=="Refunded"){ ?>
            <button class="invoice-btn">Refund Slip</button>
        <?php } else { ?>
            <button class="invoice-btn">View</button>
        <?php } ?>
    </a>
<?php } else { ?>
    <span style="color:gray;">N/A</span>
<?php } ?>
</td>

</tr>

<?php } ?>

</table>
</div>

</body>
</html>