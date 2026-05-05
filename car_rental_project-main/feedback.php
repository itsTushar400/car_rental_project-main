<?php
require_once('connection.php');
session_start();

$email = $_SESSION['email'] ?? '';

// ✅ booking id get
$booking_id = $_GET['booking_id'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

/* BODY */
body{
background:linear-gradient(135deg,#0d0d0d,#1a1a1a);
color:white;
}

/* NAVBAR */
.navbar{
width:100%;
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 60px;
background:rgba(0,0,0,0.6);
backdrop-filter:blur(10px);
position:fixed;
top:0;
z-index:1000;
}

.logo{
font-size:26px;
font-weight:bold;
color:#ff7200;
}

.menu a{
margin:0 12px;
text-decoration:none;
color:white;
font-weight:500;
}

.menu a:hover{
color:#ff7200;
}

/* CENTER */
.container{
height:100vh;
display:flex;
justify-content:center;
align-items:center;
padding-top:80px;
}

/* CARD */
.card{
width:420px;
background:rgba(255,255,255,0.05);
backdrop-filter:blur(15px);
padding:30px;
border-radius:15px;
box-shadow:0 10px 30px rgba(0,0,0,0.5);
}

.card h2{
text-align:center;
margin-bottom:20px;
color:#ff7200;
}

/* INPUT */
input,textarea{
width:100%;
padding:12px;
margin:10px 0;
border:none;
border-radius:8px;
outline:none;
}

/* STARS */
.stars{
display:flex;
justify-content:center;
margin:15px 0;
flex-direction:row-reverse;
}

.stars input{display:none;}

.stars label{
font-size:30px;
color:#555;
cursor:pointer;
transition:0.3s;
}

.stars input:checked ~ label{
color:#ff7200;
}

.stars label:hover,
.stars label:hover ~ label{
color:#ff7200;
}

/* BUTTON */
button{
width:100%;
padding:12px;
background:linear-gradient(45deg,#ff7200,#ff8c1a);
border:none;
border-radius:8px;
color:white;
font-size:16px;
cursor:pointer;
transition:0.3s;
}

button:hover{
transform:scale(1.05);
}

/* POPUP */
#popup{
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.6);
display:none;
justify-content:center;
align-items:center;
}

.popup-box{
background:white;
padding:30px;
border-radius:10px;
text-align:center;
width:300px;
color:black;
}

.popup-box h2{
color:green;
margin-bottom:10px;
}

</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
<div class="logo">CaRs</div>

<div class="menu">
<a href="home.php">Home</a>
<a href="about.php">About</a>
<a href="contactus.php">Contact</a>
<a href="feedback.php">Feedback</a>
</div>
</div>

<!-- CONTENT -->
<div class="container">

<div class="card">

<h2>Give Your Feedback</h2>

<form action="" method="POST">

<input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">

<input type="email" name="email" value="<?php echo $email; ?>" readonly>

<input type="text" name="name" placeholder="Your Name" required>

<!-- ⭐ STARS -->
<div class="stars">
<input type="radio" name="rating" value="5" id="star5"><label for="star5">★</label>
<input type="radio" name="rating" value="4" id="star4"><label for="star4">★</label>
<input type="radio" name="rating" value="3" id="star3"><label for="star3">★</label>
<input type="radio" name="rating" value="2" id="star2"><label for="star2">★</label>
<input type="radio" name="rating" value="1" id="star1"><label for="star1">★</label>
</div>

<textarea name="message" placeholder="Write your feedback..." required></textarea>

<button type="submit" name="submit">Submit Feedback</button>

</form>

</div>

</div>

<!-- POPUP -->
<div id="popup">
<div class="popup-box">
<h2>✅ Success</h2>
<p>Feedback Submitted Successfully!</p>
<button onclick="closePopup()">OK</button>
</div>
</div>

<script>
function closePopup(){
document.getElementById("popup").style.display="none";
}
</script>

</body>
</html>

<?php
// ✅ SAVE DATA
if(isset($_POST['submit'])){
    $email = $_POST['email'];
    $name = $_POST['name'];
    $rating = $_POST['rating'];
    $message = $_POST['message'];
    $booking_id = $_POST['booking_id'];

    mysqli_query($con,"INSERT INTO feedback (booking_id,email,name,rating,message)
    VALUES ('$booking_id','$email','$name','$rating','$message')");

    echo "<script>document.getElementById('popup').style.display='flex';</script>";
}
?>