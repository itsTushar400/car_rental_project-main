<?php
require_once('connection.php');

if(isset($_POST['addcar'])){

$name = $_POST['name'];
$fuel = $_POST['fuel'];
$capacity = $_POST['capacity'];
$price = $_POST['price'];

$filename = $_FILES["image"]["name"];
$tempname = $_FILES["image"]["tmp_name"];
$folder = "images/".$filename;

move_uploaded_file($tempname, $folder);

$query = "INSERT INTO cars (CAR_NAME, CAR_IMG, FUEL_TYPE, CAPACITY, PRICE, AVAILABLE)
VALUES ('$name','$filename','$fuel','$capacity','$price','Y')";

mysqli_query($con,$query);

echo "<script>alert('Car Added Successfully'); window.location='adminvehicle.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Car</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#f5f5f5;
}

/* SIDEBAR */

.sidebar{
position:fixed;
left:0;
top:0;
width:220px;
height:100%;
background:#111;
padding-top:20px;
}

.sidebar .logo{
color:#ff7200;
font-size:28px;
text-align:center;
margin-bottom:30px;
}

.sidebar ul li{
list-style:none;
margin:15px 0;
text-align:center;
}

.sidebar ul li a{
color:white;
text-decoration:none;
display:block;
padding:10px;
}

.sidebar ul li a:hover{
background:#ff7200;
border-radius:5px;
}

/* MAIN */

.main{
margin-left:230px;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

/* FORM */

.form-box{
width:400px;
background:#2c2c2c;
padding:25px;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.5);
color:white;
}

.form-box h2{
text-align:center;
margin-bottom:20px;
}

input{
width:100%;
padding:10px;
margin:10px 0;
border:none;
border-radius:6px;
}

input:focus{
outline:none;
box-shadow:0 0 5px orange;
}

/* IMAGE PREVIEW */

.preview{
width:100%;
height:150px;
background:#444;
border-radius:8px;
margin-top:10px;
display:flex;
align-items:center;
justify-content:center;
overflow:hidden;
}

.preview img{
width:100%;
height:100%;
object-fit:cover;
display:none;
}

/* BUTTON */

button{
width:100%;
padding:12px;
background:#ff7200;
border:none;
border-radius:8px;
color:white;
font-size:16px;
cursor:pointer;
transition:0.3s;
}

button:hover{
background:#ff5500;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h2 class="logo">CaRs</h2>

<ul>
<li><a href="adminvehicle.php">Vehicle Management</a></li>
<li><a href="#">Users</a></li>
<li><a href="#">Feedbacks</a></li>
<li><a href="#">Booking</a></li>
<li><a href="index.php">Logout</a></li>
</ul>

</div>

<!-- MAIN -->

<div class="main">

<div class="form-box">

<h2>Add New Car</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" placeholder="Car Name" required>

<input type="text" name="fuel" placeholder="Fuel Type" required>

<input type="number" name="capacity" placeholder="Capacity" required>

<input type="number" name="price" placeholder="Price" required>

<input type="file" name="image" accept="image/*" onchange="previewImage(event)" required>

<div class="preview">
<img id="imgPreview">
</div>

<button name="addcar">ADD CAR</button>

</form>

</div>

</div>

<!-- JS FOR PREVIEW -->

<script>

function previewImage(event){
const input = event.target;
const preview = document.getElementById('imgPreview');

const file = input.files[0];

if(file){
preview.src = URL.createObjectURL(file);
preview.style.display = "block";
}
}

</script>

</body>
</html>