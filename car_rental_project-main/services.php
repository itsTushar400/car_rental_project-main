<!DOCTYPE html>
<html>
<head>

<title>Our Services</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins;
}

body{
background:#0f172a;
color:white;
}

/* NAVBAR */

.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 80px;
background:#111;
}

.logo{
color:#ff7200;
font-size:30px;
font-weight:bold;
}

.menu ul{
display:flex;
gap:40px;
list-style:none;
}

.menu ul li a{
color:white;
text-decoration:none;
font-weight:500;
}

/* TITLE */

.title{
text-align:center;
margin-top:60px;
font-size:40px;
}

.title span{
color:#ff7200;
}

/* SERVICES GRID */

.services{
padding:60px 80px;
display:grid;
grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
gap:30px;
}

/* SERVICE CARD */

.card{
background:#1e293b;
padding:40px 30px;
border-radius:12px;
text-align:center;
transition:0.4s;
box-shadow:0 0 20px rgba(0,0,0,0.3);
}

.card:hover{
transform:translateY(-12px);
box-shadow:0 0 30px rgba(255,114,0,0.4);
}

/* ICON */

.card i{
font-size:40px;
color:#ff7200;
margin-bottom:20px;
}

/* TITLE */

.card h3{
margin-bottom:15px;
}

/* TEXT */

.card p{
font-size:14px;
color:#ddd;
line-height:1.6;
}

</style>

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

<div class="logo">CaRs</div>

<div class="menu">

<ul>

<li><a href="index.php">HOME</a></li>
<li><a href="about.php">ABOUT</a></li>
<li><a href="services.php">SERVICES</a></li>
<li><a href="contactus.php">CONTACT</a></li>

</ul>

</div>

</div>


<!-- PAGE TITLE -->

<h1 class="title">Our <span>Services</span></h1>


<!-- SERVICES -->

<div class="services">

<div class="card">
<i class="fa-solid fa-car"></i>
<h3>Self Drive Cars</h3>
<p>Rent your favorite car and enjoy the freedom of driving anytime without restrictions.</p>
</div>


<div class="card">
<i class="fa-solid fa-gem"></i>
<h3>Luxury Cars</h3>
<p>Drive premium vehicles like BMW, Audi and Mercedes for special occasions.</p>
</div>


<div class="card">
<i class="fa-solid fa-plane-departure"></i>
<h3>Airport Pickup</h3>
<p>Fast and reliable airport pickup and drop services for smooth travel.</p>
</div>


<div class="card">
<i class="fa-solid fa-road"></i>
<h3>Outstation Trips</h3>
<p>Perfect cars for road trips and vacations with family and friends.</p>
</div>


<div class="card">
<i class="fa-solid fa-briefcase"></i>
<h3>Corporate Rental</h3>
<p>Professional rental services designed for corporate clients and business travel.</p>
</div>


<div class="card">
<i class="fa-solid fa-headset"></i>
<h3>24/7 Support</h3>
<p>Our support team is available anytime to assist you during your journey.</p>
</div>

</div>

</body>
</html>