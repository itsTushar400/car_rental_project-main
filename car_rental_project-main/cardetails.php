<?php
require_once('connection.php');
session_start();

/* LOGIN CHECK */
if(!isset($_SESSION['email'])){
    header("Location: index.html");
    exit();
}

$value = $_SESSION['email'];

/* USER */
$sql = "SELECT * FROM users WHERE EMAIL='$value'";
$name = mysqli_query($con,$sql);
$rows = mysqli_fetch_assoc($name);

/* CURRENT PAGE (ACTIVE MENU FIX) */
$current_page = basename($_SERVER['PHP_SELF']);

/* SEARCH */
$search = "";
if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($con,$_GET['search']);
}

/* SORT */
$order = "";
if(isset($_GET['sort'])){
    if($_GET['sort']=="low"){ $order="ORDER BY PRICE ASC"; }
    if($_GET['sort']=="high"){ $order="ORDER BY PRICE DESC"; }
}

/* ALL CARS */
$sql2 = "SELECT * FROM cars 
WHERE CAR_NAME LIKE '%$search%' $order";

$cars = mysqli_query($con,$sql2);
?>

<!DOCTYPE html>
<html>
<head>
<title>Car Details</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}
body{background:#f4f6f9;}

/* NAVBAR */
.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:15px 80px;
background:white;
box-shadow:0 5px 10px rgba(0,0,0,0.1);
position:sticky;
top:0;
z-index:1000;
}

.logo{font-size:28px;font-weight:bold;color:#ff6b00;}

.menu{
display:flex;
align-items:center;
}

.menu a{
margin:0 8px;
text-decoration:none;
color:#333;
font-weight:500;
padding:6px 12px;
border-radius:6px;
transition:0.3s;
}

/* HOVER */
.menu a:hover{
background:#ff6b00;
color:white;
}

/* ACTIVE */
.menu a.active{
background:#ff6b00;
color:white;
font-weight:600;
}

/* BOOKING BUTTON */
.booking-btn{
background:#ff6b00;
color:white !important;
}

/* RIGHT SIDE */
.right{
display:flex;
align-items:center;
gap:10px;
}

.logout{
background:#ff6b00;
color:white;
padding:8px 18px;
border-radius:6px;
text-decoration:none;
}

/* HEADING */
.heading{
text-align:center;
margin-top:40px;
font-size:30px;
}

/* SEARCH */
.search-box{
display:flex;
justify-content:center;
margin:30px 0;
}

.search-form{
display:flex;
gap:10px;
align-items:center;
}

.search-form input{
width:260px;
padding:10px;
border-radius:6px;
border:1px solid #ccc;
}

.search-form button{
padding:10px 20px;
background:#ff6b00;
color:white;
border:none;
border-radius:6px;
cursor:pointer;
}

.search-form select{
padding:10px;
border-radius:6px;
border:1px solid #ccc;
cursor:pointer;
}

/* CAR GRID */
.car-container{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(320px,1fr));
gap:30px;
padding:40px 80px;
}

/* CARD */
.car-card{
background:white;
border-radius:15px;
overflow:hidden;
box-shadow:0 10px 20px rgba(0,0,0,0.1);
transition:0.3s;
}

.car-card:hover{
transform:translateY(-8px);
}

.car-card img{
width:100%;
height:200px;
object-fit:cover;
}

/* INFO */
.car-info{
padding:20px;
}

.car-info h3{margin-bottom:10px;}
.car-info p{color:#666;margin:5px 0;}

.price{
font-size:20px;
font-weight:bold;
color:#ff6b00;
margin-top:10px;
}

/* BUTTON */
.book-btn{
display:inline-block;
margin-top:15px;
background:#ff6b00;
color:white;
padding:10px 20px;
border-radius:6px;
text-decoration:none;
border:none;
cursor:pointer;
}

/* POPUP */
#popup{
position:fixed;
top:0;left:0;
width:100%;height:100%;
background:rgba(0,0,0,0.6);
display:none;
justify-content:center;
align-items:center;
z-index:999;
}

.popup-box{
background:white;
padding:30px;
border-radius:12px;
text-align:center;
width:320px;
}

.popup-box h2{
color:red;
margin-bottom:15px;
}

.popup-box p{
margin-bottom:20px;
color:#333;
}

.popup-box button{
background:#ff6b00;
color:white;
border:none;
padding:10px 20px;
border-radius:6px;
cursor:pointer;
}
</style>

</head>

<body>

<!-- NAVBAR -->
<div class="navbar">

<div class="logo">CaRs</div>

<div class="menu">

<a href="cardetails.php" class="<?=($current_page=='cardetails.php')?'active':''?>">HOME</a>

<a href="about.php" class="<?=($current_page=='about.php')?'active':''?>">ABOUT</a>

<a href="contactus.php" class="<?=($current_page=='contactus.php')?'active':''?>">CONTACT</a>

<a href="feedback.php" class="<?=($current_page=='feedback.php')?'active':''?>">FEEDBACK</a>

<a href="bookingstatus.php" class="booking-btn <?=($current_page=='bookingstatus.php')?'active':''?>">
My Booking
</a>

</div>

<div class="right">
Hello! <?php echo $rows['FNAME']; ?>
<a class="logout" href="logout.php">Logout</a>
</div>

</div>

<h2 class="heading">OUR CARS OVERVIEW</h2>

<!-- SEARCH -->
<div class="search-box">
<form method="GET" class="search-form">

<input type="text" name="search" placeholder="Search Car">

<button type="submit">Search</button>

<select name="sort" onchange="this.form.submit()">
<option value="">Sort By</option>
<option value="low">Price Low → High</option>
<option value="high">Price High → Low</option>
</select>

</form>
</div>

<!-- CAR LIST -->
<div class="car-container">

<?php
while($result=mysqli_fetch_array($cars))
{

$cid = $result['CAR_ID'];

$q = mysqli_query($con,"SELECT MAX(RETURN_DATE) as last_date 
FROM booking 
WHERE CAR_ID='$cid' AND BOOK_STATUS!='Returned'");

$data = mysqli_fetch_assoc($q);

$available = true;
$return_date = "";
$next_date = "";

if($data['last_date']){
    $today = date('Y-m-d');

    if($data['last_date'] >= $today){
        $available = false;
        $return_date = $data['last_date'];
        $next_date = date('Y-m-d', strtotime($return_date.' +1 day'));
    }
}
?>

<div class="car-card">

<img src="images/<?php echo $result['CAR_IMG']?>">

<div class="car-info">

<h3><?php echo $result['CAR_NAME']?></h3>

<p>Fuel Type : <?php echo $result['FUEL_TYPE']?></p>
<p>Capacity : <?php echo $result['CAPACITY']?></p>

<div class="price">
₹<?php echo $result['PRICE']?> / Day
</div>

<?php if($available){ ?>
<a class="book-btn" href="booking.php?id=<?php echo $cid?>">
Book Now
</a>
<?php } else { ?>
<button class="book-btn" onclick="showPopup('<?php echo $return_date?>','<?php echo $next_date?>')">
Not Available
</button>
<?php } ?>

</div>
</div>

<?php } ?>

</div>

<!-- POPUP -->
<div id="popup">
<div class="popup-box">
<h2>Car Not Available</h2>
<p id="popupText"></p>
<button onclick="closePopup()">OK</button>
</div>
</div>

<script>
function showPopup(returnDate, nextDate){
document.getElementById("popup").style.display="flex";
document.getElementById("popupText").innerHTML =
"Return Date: " + returnDate + "<br><br>" +
"Available From: " + nextDate;
}

function closePopup(){
document.getElementById("popup").style.display="none";
}
</script>

</body>
</html>