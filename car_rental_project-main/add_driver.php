<?php
require_once('connection.php');

if(isset($_POST['add'])){

$name=$_POST['name'];
$phone=$_POST['phone'];

$image=$_FILES['image']['name'];
$tmp=$_FILES['image']['tmp_name'];

move_uploaded_file($tmp,"images/".$image);

mysqli_query($con,"INSERT INTO drivers(NAME,PHONE,IMAGE,STATUS)
VALUES('$name','$phone','$image','Available')");

echo "<script>alert('Driver Added'); window.location='drivers.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Driver</title>

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

.box{
width:400px;
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

h2{
text-align:center;
margin-bottom:20px;
}

/* INPUT */

input{
width:100%;
padding:10px;
margin:10px 0;
border-radius:6px;
border:1px solid #ccc;
}

/* BUTTON */

.btn{
width:100%;
padding:12px;
background:#ff7200;
color:white;
border:none;
border-radius:8px;
cursor:pointer;
}

/* BACK */

.back{
display:inline-block;
margin-bottom:10px;
text-decoration:none;
color:#ff7200;
font-weight:bold;
}

/* IMAGE PREVIEW */

.preview{
width:100%;
height:150px;
background:#eee;
border-radius:10px;
display:flex;
align-items:center;
justify-content:center;
overflow:hidden;
margin-top:10px;
}

.preview img{
width:100%;
height:100%;
object-fit:cover;
display:none;
}

</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h2 class="logo">CaRs</h2>

<ul>
<li><a href="adminvehicle.php">Vehicles</a></li>
<li><a href="drivers.php">Drivers</a></li>
<li><a href="adminbook.php">Bookings</a></li>
<li><a href="index.php">Logout</a></li>
</ul>
</div>

<!-- MAIN -->
<div class="main">

<div class="box">

<a href="drivers.php" class="back">← Back</a>

<h2>Add Driver</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" placeholder="Driver Name" required>

<input type="text" name="phone" placeholder="Phone Number" required>

<input type="file" name="image" accept="image/*" onchange="previewImage(event)">

<div class="preview">
<img id="imgPreview">
</div>

<button name="add" class="btn">Add Driver</button>

</form>

</div>

</div>

<script>
function previewImage(event){
const file=event.target.files[0];
if(file){
const img=document.getElementById("imgPreview");
img.src=URL.createObjectURL(file);
img.style.display="block";
}
}
</script>

</body>
</html>