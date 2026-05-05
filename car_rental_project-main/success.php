<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Payment Success</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
body{
display:flex;
justify-content:center;
align-items:center;
height:100vh;
background:#111;
font-family:Poppins;
color:white;
}

.box{
background:#222;
padding:40px;
border-radius:15px;
text-align:center;
box-shadow:0 10px 30px rgba(0,0,0,0.6);
}

.tick{
font-size:60px;
color:#00ff88;
margin-bottom:15px;
}

.btn{
margin-top:20px;
padding:12px 20px;
background:#ff6b00;
border:none;
border-radius:8px;
color:white;
cursor:pointer;
}
</style>

</head>

<body>

<div class="box">
<div class="tick">✔</div>
<h2>Payment Successful</h2>
<p>Your booking is confirmed 🎉</p>

<button class="btn" onclick="window.location.href='cardetails.php'">
Go Home
</button>
</div>

</body>
</html>