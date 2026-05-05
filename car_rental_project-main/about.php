<?php 
require_once("connection.php");

/* ================= COUNT ================= */
$carcount = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) as total FROM cars"));
$usercount = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) as total FROM users"));
$bookingcount = mysqli_fetch_assoc(mysqli_query($con,"SELECT COUNT(*) as total FROM booking"));

/* ================= ABOUT DATA ================= */
$about = mysqli_fetch_assoc(mysqli_query($con,"SELECT * FROM about LIMIT 1"));
?>

<!DOCTYPE html>
<html>
<head>
<title>About | CaRs Rental</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

body{
background: linear-gradient(135deg,#0d0d0d,#1a1a1a,#0d0d0d);
color:white;
padding-top:90px;
}

/* NAVBAR */
.navbar{
position:fixed;
top:0;
width:100%;
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 80px;
background:rgba(0,0,0,0.5);
backdrop-filter:blur(15px);
border-bottom:1px solid rgba(255,255,255,0.1);
z-index:1000;
}

.logo{
font-size:28px;
font-weight:600;
color:#ff7200;
}

.navbar ul{
display:flex;
gap:30px;
list-style:none;
}

.navbar ul li a{
text-decoration:none;
color:white;
position:relative;
}

/* ACTIVE LINK */
.navbar ul li a.active{
color:#ff7200;
}

.navbar ul li a::after{
content:"";
position:absolute;
width:0%;
height:2px;
left:0;
bottom:-5px;
background:#ff7200;
transition:0.3s;
}

.navbar ul li a.active::after,
.navbar ul li a:hover::after{
width:100%;
}

/* HERO */
.hero{
height:85vh;
background: linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.95)), url("images/carbg2.jpg");
background-size:cover;
display:flex;
align-items:center;
justify-content:center;
text-align:center;
padding:20px;
}

.hero h1{
font-size:60px;
}

.hero span{
color:#ff7200;
text-shadow:0 0 15px #ff7200;
}

.hero p{
margin-top:20px;
max-width:700px;
opacity:0.8;
}

/* ABOUT */
.about{
padding:80px 100px;
text-align:center;
max-width:1000px;
margin:auto;
line-height:1.8;
}

.section-title{
text-align:center;
font-size:35px;
margin-top:80px;
color:#ff7200;
}

/* STATS */
.stats{
display:flex;
justify-content:center;
gap:80px;
padding:60px 0;
background:#111;
}

.stat-box{text-align:center;}
.stat-box h2{
font-size:45px;
color:#ff7200;
}

/* CARS */
.cars{
display:flex;
justify-content:center;
gap:40px;
padding:60px;
flex-wrap:wrap;
}

.car-card{
background:#1c1c1c;
padding:20px;
width:260px;
border-radius:15px;
text-align:center;
transition:0.3s;
}

.car-card:hover{
transform:translateY(-10px);
box-shadow:0 0 25px rgba(255,114,0,0.5);
}

.car-card img{
width:100%;
height:150px;
object-fit:cover;
border-radius:10px;
}

.car-card h3{color:#ff7200;}

.car-card button{
margin-top:10px;
padding:10px;
border:none;
background:#ff7200;
color:white;
border-radius:5px;
cursor:pointer;
}

/* WHY */
.why{
display:flex;
justify-content:center;
gap:40px;
padding:50px;
flex-wrap:wrap;
}

.why-box{
background:#111;
padding:25px;
border-radius:10px;
width:250px;
text-align:center;
}

/* REVIEWS */
.reviews{
display:flex;
justify-content:center;
gap:40px;
padding:60px;
flex-wrap:wrap;
}

.review{
background:#1c1c1c;
padding:25px;
border-radius:10px;
width:250px;
text-align:center;
}

/* FOOTER */
.footer{
background:#000;
text-align:center;
padding:30px;
}

</style>
</head>

<body>

<!-- NAVBAR -->
<div class="navbar">
<div class="logo">CaRs</div>
<ul>
<li><a href="index.php">HOME</a></li>
<li><a href="about.php" class="active">ABOUT</a></li>
<li><a href="services.php">SERVICES</a></li>
<li><a href="contactus.php">CONTACT</a></li>
</ul>
</div>

<!-- HERO -->
<section class="hero">
<div>
<h1>About <span>CaRs</span></h1>
<p>
We provide premium and affordable car rental services for every journey.
Your comfort, safety, and satisfaction are our top priorities.
</p>
</div>
</section>

<!-- ABOUT DYNAMIC -->
<h2 class="section-title">
<?php echo htmlspecialchars($about['title']); ?>
</h2>

<section class="about">

<p>
<?php echo htmlspecialchars($about['description']); ?>
</p>

<p style="margin-top:20px;">
<?php echo htmlspecialchars($about['mission']); ?>
</p>

</section>

<!-- STATS -->
<section class="stats">
<div class="stat-box">
<h2><?php echo $carcount['total']; ?>+</h2>
<p>Cars</p>
</div>

<div class="stat-box">
<h2><?php echo $usercount['total']; ?>+</h2>
<p>Customers</p>
</div>

<div class="stat-box">
<h2><?php echo $bookingcount['total']; ?>+</h2>
<p>Bookings</p>
</div>
</section>

<!-- CARS -->
<h2 class="section-title">Popular Cars</h2>

<div class="cars">
<?php 
$day = date('d');
$result = mysqli_query($con,"SELECT * FROM cars ORDER BY MOD(CAR_ID + $day,10) LIMIT 3");

while($row=mysqli_fetch_assoc($result)){ ?>
<div class="car-card">
<img src="images/<?php echo htmlspecialchars($row['CAR_IMG']); ?>">
<h3><?php echo htmlspecialchars($row['CAR_NAME']); ?></h3>
<p><?php echo htmlspecialchars($row['FUEL_TYPE']); ?></p>
<p>₹<?php echo htmlspecialchars($row['PRICE']); ?></p>

<a href="booking.php?id=<?php echo $row['CAR_ID']; ?>">
<button>Book Now</button>
</a>
</div>
<?php } ?>
</div>

<!-- WHY -->
<h2 class="section-title">Why Choose Us</h2>

<div class="why">
<div class="why-box">🚗 Wide Range of Cars</div>
<div class="why-box">💰 Affordable Pricing</div>
<div class="why-box">⚡ Fast Booking</div>
</div>

<!-- REVIEWS -->
<h2 class="section-title">Customer Reviews</h2>

<div class="reviews">
<div class="review">⭐⭐⭐⭐⭐<p>Best service</p><b>Rahul</b></div>
<div class="review">⭐⭐⭐⭐⭐<p>Amazing cars</p><b>Priya</b></div>
<div class="review">⭐⭐⭐⭐⭐<p>Very smooth booking</p><b>Aman</b></div>
</div>

<!-- FOOTER -->
<footer class="footer">
<h2>CaRs</h2>
<p>Drive your dreams 🚗</p>
<p>© 2026 All Rights Reserved</p>
</footer>

</body>
</html>