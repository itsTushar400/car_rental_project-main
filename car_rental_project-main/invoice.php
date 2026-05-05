<?php
require_once('connection.php');

if(!isset($_GET['id'])){
    die("Invalid");
}

$id = intval($_GET['id']);

/* ================= FETCH DATA ================= */
$q = mysqli_query($con,"SELECT 
b.*, 
c.CAR_NAME,
u.FNAME,
u.LNAME,
u.PHONE_NUMBER,
d.NAME as DRIVER_NAME,
d.PHONE as DRIVER_PHONE
FROM booking b
JOIN cars c ON b.CAR_ID=c.CAR_ID
JOIN users u ON b.EMAIL=u.EMAIL
LEFT JOIN drivers d ON b.DRIVER_ID=d.ID
WHERE b.BOOK_ID='$id'");

$data = mysqli_fetch_assoc($q);

if(!$data){
    die("No Data Found");
}

/* ================= PAYMENT ================= */
$payment_q = mysqli_query($con,"
SELECT * FROM payment 
WHERE BOOK_ID='$id' 
ORDER BY PAY_ID DESC LIMIT 1
");

$payment = mysqli_fetch_assoc($payment_q);
$refund = $payment['refund_status'] ?? '';

/* ================= DATE ================= */
$date = date("d-m-Y");

/* ================= MASK PHONE ================= */
$phone = $data['PHONE_NUMBER'] ?? '0000000000';
$masked_phone = "XXXXXX".substr($phone,-4);

/* ================= MASK LICENSE ================= */
$license = $data['LICENSE_NO'] ?? '';
$masked_license = $license ? "XXXX-XXXX-".substr($license,-4) : "N/A";
?>

<!DOCTYPE html>
<html>
<head>
<title>Invoice</title>

<style>
body{
    font-family:Poppins;
    background:#f2f2f2;
    padding:30px;
}

.invoice{
    max-width:900px;
    margin:auto;
    background:white;
    padding:40px;
    border-radius:12px;
    box-shadow:0 0 20px rgba(0,0,0,0.15);
}

.header{text-align:center;}
.header h1{color:#ff6600;margin-bottom:5px;}

.line{
    height:3px;
    background:#ff6600;
    margin:20px 0;
}

.flex{
    display:flex;
    justify-content:space-between;
}

.box{width:48%;}

h3{margin-bottom:10px;color:#333;}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

td{
    padding:10px;
    border-bottom:1px solid #ddd;
}

.total{
    text-align:right;
    font-size:22px;
    font-weight:bold;
    color:#ff6600;
    margin-top:20px;
}

.footer{
    text-align:center;
    margin-top:30px;
    color:#777;
}

.badge{
    padding:5px 10px;
    border-radius:5px;
    color:#fff;
}

.paid{background:green;}
.unpaid{background:red;}

.print-btn{
    text-align:center;
    margin-top:20px;
}

button{
    padding:10px 20px;
    border:none;
    background:#ff6600;
    color:white;
    border-radius:6px;
    cursor:pointer;
}

.refund-box{
    margin-top:20px;
    padding:15px;
    background:#ffe6e6;
    border-left:5px solid red;
    border-radius:5px;
}
</style>

</head>

<body>

<div class="invoice">

<!-- HEADER -->
<div class="header">
<h1>CaRs Rental Pvt Ltd</h1>
<p>Car Rental Invoice</p>
<p>Date: <?php echo $date; ?></p>
</div>

<div class="line"></div>

<!-- DETAILS -->
<div class="flex">

<div class="box">
<h3>Customer Details</h3>
<p><b>Name:</b> <?php echo $data['FNAME']." ".$data['LNAME']; ?></p>
<p><b>Email:</b> <?php echo $data['EMAIL']; ?></p>
<p><b>Phone:</b> <?php echo $masked_phone; ?></p>
<p><b>License:</b> <?php echo $masked_license; ?></p>
</div>

<div class="box">
<h3>Booking Details</h3>
<p><b>Booking ID:</b> <?php echo $data['BOOK_ID']; ?></p>
<p><b>Car:</b> <?php echo $data['CAR_NAME']; ?></p>
<p><b>Destination:</b> <?php echo $data['DESTINATION']; ?></p>

<p><b>Driver Name:</b> <?php echo $data['DRIVER_NAME'] ?? 'Not Assigned'; ?></p>
<p><b>Driver Phone:</b> <?php echo $data['DRIVER_PHONE'] ?? 'N/A'; ?></p>
</div>

</div>

<!-- TABLE -->
<table>

<tr><td>Booking Date</td><td><?php echo $data['BOOK_DATE']; ?></td></tr>
<tr><td>Return Date</td><td><?php echo $data['RETURN_DATE']; ?></td></tr>
<tr><td>Duration</td><td><?php echo $data['DURATION']; ?> days</td></tr>
<tr><td>Status</td><td><?php echo $data['BOOK_STATUS']; ?></td></tr>

<tr>
<td>Payment</td>
<td>
<?php 
if($payment && $refund=="Refunded"){
    echo "<span class='badge unpaid'>Refunded</span>";
}
elseif($payment){
    echo "<span class='badge paid'>Paid</span>";
}
else{
    echo "<span class='badge unpaid'>Not Paid</span>";
}
?>
</td>
</tr>

</table>

<!-- TOTAL -->
<div class="total">
Total Amount: ₹<?php echo $data['PRICE']; ?>
</div>

<hr>

<h3>Payment Details</h3>

<?php if($payment){ ?>

<p><b>Method:</b> <?php echo $payment['payment_method']; ?></p>

<?php if($payment['payment_method']=="Card"){ 
$last4 = substr($payment['card_number'],-4);
?>
<p><b>Card:</b> XXXX XXXX XXXX <?php echo $last4; ?></p>
<p><b>Name:</b> <?php echo $payment['card_name'] ?? 'N/A'; ?></p>
<?php } ?>

<?php if($payment['payment_method']=="UPI"){ ?>
<p><b>UPI:</b> <?php echo $payment['upi_id']; ?></p>
<?php } ?>

<?php if($payment['payment_method']=="QR"){ ?>
<p><b>QR Payment:</b> Done</p>
<?php } ?>

<?php } else { ?>
<p style="color:red;">No Payment Found</p>
<?php } ?>

<!-- 🔥 REFUND MESSAGE -->
<?php if($refund=="Refunded"){ ?>
<div class="refund-box">
<h3 style="color:red;">Refund Processed</h3>

<p>This payment has been refunded to the <b>original payment method</b>.</p>

<?php if($payment['payment_method']=="Card"){ ?>
<p>Refunded to your bank account (Card ending XXXX <?php echo substr($payment['card_number'],-4); ?>)</p>
<?php } ?>

<?php if($payment['payment_method']=="UPI"){ ?>
<p>Refunded to your UPI ID</p>
<?php } ?>

<?php if($payment['payment_method']=="QR"){ ?>
<p>Refund processed to original QR payment source</p>
<?php } ?>

<p>Please allow 3-5 working days.</p>
</div>
<?php } ?>

<!-- FOOTER -->
<div class="footer">
<p>Thank you for choosing CaRs Rental Pvt Ltd!</p>
<p>Owner: Tushar Chauhan</p>
</div>

<div class="print-btn">
<button onclick="window.print()">Print Invoice</button>
</div>

</div>

</body>
</html>