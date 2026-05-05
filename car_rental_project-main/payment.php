<?php
require_once('connection.php');
session_start();

$showSuccess = false;

if(isset($_POST['pay'])){

    $bid = intval($_POST['booking_id']); // ✅ FIX (safe int)
    $method = $_POST['method'];

    // ✅ CARD DATA
    $card_number = $_POST['card_number'] ?? '';
    $card_name = $_POST['card_name'] ?? '';

    // ✅ CLEAN + LAST 4 DIGIT
    if($card_number){
        $card_number = preg_replace('/\s+/', '', $card_number);
        $card_number = substr($card_number, -4);
    }

    // ✅ CHECK BOOKING EXIST (FOREIGN KEY FIX)
    $checkBooking = mysqli_query($con,"SELECT * FROM booking WHERE BOOK_ID='$bid'");
    if(mysqli_num_rows($checkBooking)==0){
        die("Invalid Booking ID ❌");
    }

    // ✅ USE BOOK_ID (IMPORTANT FIX)
    $check = mysqli_query($con,"SELECT * FROM payment WHERE BOOK_ID='$bid'");

    if(mysqli_num_rows($check)==0){

        mysqli_query($con,"
        INSERT INTO payment 
        (BOOK_ID, payment_method, card_number, card_name, payment_status)
        VALUES 
        ('$bid','$method','$card_number','$card_name','Paid')
        ");

    } else {

        mysqli_query($con,"
        UPDATE payment 
        SET payment_method='$method',
            card_number='$card_number',
            card_name='$card_name',
            payment_status='Paid'
        WHERE BOOK_ID='$bid'
        ");
    }

    // ✅ UPDATE BOOKING
    mysqli_query($con,"
    UPDATE booking SET PAYMENT='Paid' WHERE BOOK_ID='$bid'
    ");

    $showSuccess = true;
}

// ✅ SAFE GET
$id = intval($_GET['id']);
$data = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM booking WHERE BOOK_ID='$id'"));

if(!$data){
    die("Invalid Booking");
}

$amount = $data['PRICE'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins;
}

/* BODY FIX (IMPORTANT) */
body{
min-height:100vh;
background:linear-gradient(135deg,#1a0033,#3a0ca3,#4361ee);
color:white;
}

/* CENTER CARD */
.main{
position:absolute;
top:50%;
left:50%;
transform:translate(-50%,-50%);
width:380px;
padding:25px;
border-radius:20px;
background:rgba(255,255,255,0.08);
backdrop-filter:blur(20px);
box-shadow:0 0 25px rgba(0,0,0,0.3);
}

/* TABS */
.tabs{
display:flex;
gap:10px;
margin-bottom:15px;
}

.tab{
flex:1;
padding:10px;
text-align:center;
background:#222;
border-radius:10px;
cursor:pointer;
transition:0.3s;
}

.tab:hover{
background:#333;
}

.tab.active{
background:#ff6600;
}

/* CARD */
.card-box{
height:210px;
perspective:1000px;
margin-bottom:15px;
}

.card-inner{
width:100%;
height:100%;
transition:0.6s;
transform-style:preserve-3d;
position:relative;
}

.card-box.flip .card-inner{
transform:rotateY(180deg);
}

.card-front,.card-back{
position:absolute;
width:100%;
height:100%;
border-radius:15px;
padding:20px;
backface-visibility:hidden;
}

.card-front{
background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
}

.card-back{
background:#333;
transform:rotateY(180deg);
}

.chip{
width:45px;
margin-top:12px;
}

.top-row{
display:flex;
justify-content:space-between;
}

.logo{
width:60px;
}

.number{
margin-top:20px;
letter-spacing:2px;
}

.bottom-row{
display:flex;
justify-content:space-between;
margin-top:20px;
}

.cvv-box{
background:#fff;
color:#000;
padding:10px;
margin-top:70px;
text-align:right;
}

/* INPUT */
input{
width:100%;
padding:10px;
margin:6px 0;
border:none;
border-radius:8px;
background:rgba(255,255,255,0.2);
color:white;
outline:none;
transition:0.3s;
}

input:focus{
background:rgba(255,255,255,0.3);
box-shadow:0 0 5px #ff6600;
}

input::placeholder{
color:#ddd;
opacity:1;
}

/* BUTTON */
button{
width:100%;
padding:12px;
background:#ff6600;
border:none;
border-radius:8px;
color:white;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:#ff8533;
}

/* CONTENT */
.content{
display:none;
}

.content.active{
display:block;
}

/* UPI */
.upi-logos{
display:flex;
justify-content:space-between;
margin:20px 0;
}

.upi-box{
background:white;
padding:10px;
border-radius:10px;
width:90px;
display:flex;
justify-content:center;
align-items:center;
transition:0.3s;
}

.upi-box:hover{
transform:scale(1.05);
}

.upi-box img{
width:60px;
}

/* QR */
#qr img{
width:200px;
display:block;
margin:20px auto;
border-radius:10px;
background:white;
padding:10px;
}

/* ✅ SUCCESS OVERLAY FIX (MAIN PART) */
.success-box{
position:fixed;
top:0;
left:0;
width:100vw;
height:100vh;
background:rgba(0,0,0,0.7);

display:flex;
justify-content:center;
align-items:center;

visibility:hidden;
opacity:0;
transition:0.4s ease;
z-index:9999;

backdrop-filter:blur(6px);
}

.success-box.show{
visibility:visible;
opacity:1;
}

/* POPUP */
.success-content{
background:linear-gradient(135deg,#111,#1a1a1a);
padding:25px 30px;
border-radius:15px;
text-align:center;
width:280px;
box-shadow:0 0 30px rgba(0,0,0,0.6);
animation:pop 0.4s ease;
}

@keyframes pop{
0%{transform:scale(0.5);opacity:0;}
100%{transform:scale(1);opacity:1;}
}

/* CHECKMARK */
.checkmark{
width:70px;
height:70px;
border-radius:50%;
border:4px solid #00ff88;
margin:0 auto 15px;
position:relative;
animation:circle 0.4s ease;
}

@keyframes circle{
from{transform:scale(0);}
to{transform:scale(1);}
}

.checkmark::after{
content:'';
position:absolute;
left:22px;
top:18px;
width:18px;
height:35px;
border:solid #00ff88;
border-width:0 4px 4px 0;
transform:rotate(45deg);
}
</style>

</head>

<body>

<div class="main">

<h2 style="text-align:center;">₹ <?php echo $amount; ?></h2>

<div class="tabs">
<div class="tab active" onclick="showTab(event,'card')">Card</div>
<div class="tab" onclick="showTab(event,'upi')">UPI</div>
<div class="tab" onclick="showTab(event,'qr')">QR</div>
</div>

<!-- CARD -->
<div id="card" class="content active">

<div class="card-box" id="cardBox">
<div class="card-inner">

<div class="card-front">
<div class="top-row">
<span>Bank</span>
<img id="cardLogo" src="images/visa.png" class="logo">
</div>
<img src="images/chip.svg" class="chip">
<div class="number" id="cnumber">0000 0000 0000 0000</div>

<div class="bottom-row">
<span id="cname">YOUR NAME</span>
<span id="cexp">MM/YY</span>
</div>
</div>

<div class="card-back">
<div class="cvv-box" id="ccvv">123</div>
</div>

</div>
</div>

<form method="POST">

<input type="text" id="number" name="card_number" placeholder="Card Number" required>
<input type="text" id="name" name="card_name" placeholder="Name" required>

<input type="text" id="exp" placeholder="MM/YY" required>
<input type="password" id="cvv" placeholder="CVV" required>

<input type="hidden" name="booking_id" value="<?php echo $id; ?>">
<input type="hidden" name="method" value="Card">

<button type="submit" name="pay">Pay Now</button>
</form>

</div>

<!-- UPI -->
<div id="upi" class="content">
<div class="upi-logos">
<div class="upi-box"><img src="images/gpay.png"></div>
<div class="upi-box"><img src="images/phonepe.png"></div>
<div class="upi-box"><img src="images/paytm.png"></div>
</div>

<form method="POST">
<input type="text" name="upi_id" placeholder="Enter UPI ID" required>
<input type="hidden" name="booking_id" value="<?php echo $id; ?>">
<input type="hidden" name="method" value="UPI">
<button type="submit" name="pay">Pay via UPI</button>
</form>
</div>  



<!-- ✅ QR PAYMENT -->
<div id="qr" class="content">
<img src="images/qr.png" style="width:200px; display:block; margin:20px auto;">

<form method="POST">
<input type="hidden" name="booking_id" value="<?php echo $id; ?>">
<input type="hidden" name="method" value="QR">
<button type="submit" name="pay">I Paid</button>
</form>
</div>
</div>

<!-- SUCCESS -->
<div id="successBox" class="success-box">
    <div class="success-content">
        <div class="checkmark"></div>
        <h2 style="margin-bottom:5px;">Payment Successful</h2>
        <p style="color:#aaa;">Redirecting...</p>
    </div>
</div>

<script>
function showTab(e,id){
    document.querySelectorAll(".tab").forEach(t=>t.classList.remove("active"));
    document.querySelectorAll(".content").forEach(c=>c.classList.remove("active"));

    document.getElementById(id).classList.add("active");
    e.target.classList.add("active");
}

/* CARD INPUTS */
let number = document.getElementById("number");
let name   = document.getElementById("name");
let exp    = document.getElementById("exp");
let cvv    = document.getElementById("cvv");
let cardBox= document.getElementById("cardBox");

/* CARD NUMBER */
number.oninput = function(){
    let val = this.value.replace(/\D/g,'');
    if(val.length>16) val = val.slice(0,16);

    let formatted = val.match(/.{1,4}/g);
    formatted = formatted ? formatted.join(" ") : "";

    this.value = formatted;
    document.getElementById("cnumber").innerText = formatted || "0000 0000 0000 0000";

    if(val.startsWith("4")){
        document.getElementById("cardLogo").src="images/visa.png";
    } 
    else if(val.startsWith("5")){
        document.getElementById("cardLogo").src="images/mastercard.png";
    }
};

/* NAME */
name.oninput = () => {
    document.getElementById("cname").innerText = name.value || "YOUR NAME";
};

/* EXPIRY */
exp.oninput = function(){
    let val = this.value.replace(/\D/g,'');
    if(val.length>4) val = val.slice(0,4);

    if(val.length>=3){
        val = val.slice(0,2)+"/"+val.slice(2);
    }

    this.value = val;
    document.getElementById("cexp").innerText = val || "MM/YY";
};

/* CVV */
cvv.oninput = function(){
    let val = this.value.replace(/\D/g,'');
    if(val.length>3) val = val.slice(0,3);

    this.value = val;
    document.getElementById("ccvv").innerText = val || "123";
};

/* CARD FLIP */
cvv.onfocus = () => cardBox.classList.add("flip");
cvv.onblur  = () => cardBox.classList.remove("flip");


/* ✅ SUCCESS POPUP FIX */
<?php if($showSuccess){ ?>
let box = document.getElementById("successBox");

/* show popup */
box.classList.add("show");

/* hide after 5 sec + redirect */
setTimeout(()=>{
    box.classList.remove("show");

    /* thoda delay for smooth fade */
    setTimeout(()=>{
        window.location = "bookingstatus.php";
    },300);

},5000);
<?php } ?>

</script>

</body>
</html>