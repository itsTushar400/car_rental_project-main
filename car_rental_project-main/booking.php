<?php
require_once('connection.php');
session_start();

if(!isset($_SESSION['email'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    die("Invalid Car");
}

$carid = $_GET['id'];

$car = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM cars WHERE CAR_ID='$carid'"));
if(!$car){
    die("Car Not Found");
}

$price = $car['PRICE'];

/* 🔥 RANDOM ACTIVE DRIVER */
$dq = mysqli_query($con,"SELECT * FROM drivers WHERE STATUS='Active' ORDER BY RAND() LIMIT 1");
$selected_driver = mysqli_fetch_assoc($dq);

/* BOOKING */
if(isset($_POST['book'])){

    $place = mysqli_real_escape_string($con,$_POST['place']);
    $date = $_POST['date'];
    $days = intval($_POST['days']);
    $phone = $_POST['phone'];
    $destination = mysqli_real_escape_string($con,$_POST['destination']);
    $driver_option = $_POST['driver_option'];
    $return_date = $_POST['return_date'];
    $license = strtoupper(trim($_POST['license']));

    if(strlen($license) < 13 || strlen($license) > 16){
        echo "<script>alert('Invalid Licence ❌');</script>"; exit();
    }

    if(!preg_match("/^[A-Z0-9]+$/",$license)){
        echo "<script>alert('Licence format wrong ❌');</script>"; exit();
    }

    if(!preg_match("/^[0-9]{10}$/",$phone)){
        echo "<script>alert('Invalid Phone ❌');</script>"; exit();
    }

    $total = $days * $price;
    $driver_id = NULL;

    if($driver_option == "Driver Required"){
        if($selected_driver){
            $driver_id = $selected_driver['ID'];
            $total += (500 * $days);

            mysqli_query($con,"UPDATE drivers SET STATUS='Booked' WHERE ID='$driver_id'");
        } else {
            echo "<script>alert('No Driver Available ❌');</script>"; exit();
        }
    }

    mysqli_query($con,"
    INSERT INTO booking
    (CAR_ID, EMAIL, BOOK_PLACE, BOOK_DATE, DURATION, PHONE_NUMBER, DESTINATION, PRICE, RETURN_DATE, DRIVER_OPTION, DRIVER_ID, LICENSE_NO, BOOK_STATUS, PAYMENT)
    VALUES
    ('$carid','".$_SESSION['email']."','$place','$date','$days','$phone','$destination','$total','$return_date','$driver_option',
    ".($driver_id ? "'$driver_id'" : "NULL").",
    '$license','Pending','Not Paid')
    ");

    $id = mysqli_insert_id($con);
    header("Location: payment.php?id=".$id);
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
background:
linear-gradient(rgba(0,0,0,0.75), rgba(0,0,0,0.75)),
url("images/bg.jpg") center/cover no-repeat;
color:white;
}

.container{display:flex;gap:50px;}

.card{
width:380px;
padding:30px;
border-radius:20px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(25px);
border:1px solid rgba(255,255,255,0.2);
box-shadow:0 10px 40px rgba(0,0,0,0.6);
}

h2{text-align:center;margin-bottom:20px;}

input, select{
width:100%;
padding:13px;
margin:10px 0;
border:none;
border-radius:12px;
background:rgba(0,0,0,0.6);
color:white;
}

button{
width:100%;
padding:15px;
background:#ff6600;
border:none;
border-radius:12px;
color:white;
font-weight:bold;
}

/* SUMMARY */
.summary p{
display:flex;
justify-content:space-between;
margin:8px 0;
}

.summary h3{
margin-top:15px;
color:#00ff9c;
display:flex;
justify-content:space-between;
}

#driverBox{
display:none;
margin-top:10px;
padding:10px;
background:rgba(0,0,0,0.5);
border-radius:10px;
}
</style>
</head>

<body>

<div class="container">

<!-- FORM -->
<div class="card">
<h2>🚗 <?php echo $car['CAR_NAME']; ?></h2>

<form method="POST">

<input type="text" name="place" placeholder="Booking Place" required>
<input type="date" name="date" id="date" required>
<input type="number" name="days" id="days" value="1" min="1" required>
<input type="text" name="phone" placeholder="Phone Number" required>
<input type="text" name="license" placeholder="Driving Licence Number"
maxlength="16" minlength="13"
style="text-transform:uppercase;" required>
<input type="text" name="destination" id="destination" placeholder="Destination" required>

<select name="driver_option" id="driver">
<option value="Self Drive">Self Drive</option>
<option value="Driver Required">Driver Required</option>
</select>

<input type="date" name="return_date" id="return" readonly>

<button name="book">Confirm Booking</button>

</form>
</div>

<!-- SUMMARY -->
<div class="card summary">

<h2>Summary</h2>

<p><span>Car</span><span><?php echo $car['CAR_NAME']; ?></span></p>
<p><span>Destination</span><span id="dest">-</span></p>
<p><span>Days</span><span id="daysText">1</span></p>

<p><span>Car Price</span><span>₹ <span id="carPrice">0</span></span></p>
<p><span>Driver</span><span>₹ <span id="driverCharge">0</span></span></p>

<p><span>Driver Name</span><span id="dname">-</span></p>

<h3><span>Total</span><span>₹ <span id="total">0</span></span></h3>

<div id="driverBox">
<a id="callBtn" href="#" style="text-decoration:none;color:white;">
    📞 <span id="dphone"></span>
</a>
</div>

</div>

</div>

<script>

let price = <?php echo $price; ?>;
let driverData = <?php echo json_encode($selected_driver ? $selected_driver : []); ?>;

let date = document.getElementById("date");
let days = document.getElementById("days");
let driver = document.getElementById("driver");
let returnDate = document.getElementById("return");
let destination = document.getElementById("destination");
let licenseInput = document.querySelector("input[name='license']");

/* 🔥 RETURN DATE CALC */
function calcReturn(){
    if(date.value && days.value){
        let d = new Date(date.value);
        d.setDate(d.getDate() + parseInt(days.value));
        returnDate.value = d.toISOString().split('T')[0];
    }
}

/* 🔥 PRICE CALC */
function calcPrice(){
    let d = parseInt(days.value) || 1;

    let carTotal = d * price;
    let driverTotal = (driver.value === "Driver Required") ? 500 * d : 0;

    document.getElementById("carPrice").innerText = carTotal;
    document.getElementById("driverCharge").innerText = driverTotal;
    document.getElementById("total").innerText = carTotal + driverTotal;
    document.getElementById("daysText").innerText = d;
}

/* 🔥 DRIVER SHOW + CALL */
function showDriver(){

    if(driver.value === "Driver Required" && driverData && driverData.NAME){

        document.getElementById("driverBox").style.display = "block";

        document.getElementById("dname").innerText = driverData.NAME;
        document.getElementById("dphone").innerText = driverData.PHONE;

        // 📞 CALL LINK
        document.getElementById("callBtn").href = "tel:" + driverData.PHONE;

    } else {

        document.getElementById("driverBox").style.display = "none";

        document.getElementById("dname").innerText = "-";
        document.getElementById("dphone").innerText = "-";

        document.getElementById("callBtn").href = "#";
    }
}

/* 🔥 DESTINATION LIVE */
destination.oninput = function(){
    document.getElementById("dest").innerText = this.value || "-";
}

/* 🔥 LICENSE LIMIT + CLEAN */
licenseInput.addEventListener("input", function(){
    this.value = this.value
        .toUpperCase()
        .replace(/[^A-Z0-9]/g, '')
        .slice(0,16);
});

/* 🔥 EVENTS */
date.onchange = calcReturn;

days.oninput = () => {
    calcReturn();
    calcPrice();
};

driver.onchange = () => {
    calcPrice();
    showDriver();
};

/* 🔥 INITIAL LOAD */
window.onload = () => {
    calcReturn();
    calcPrice();
    showDriver();
};

</script>

</body>
</html>