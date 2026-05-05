<?php
require_once('connection.php');

if(!isset($_GET['id'])){
    die("Invalid ID");
}

$id = $_GET['id'];

// FETCH OLD DATA
$res = mysqli_query($con,"SELECT * FROM cars WHERE CAR_ID='$id'");
$car = mysqli_fetch_assoc($res);

// UPDATE
if(isset($_POST['update'])){

$name = $_POST['name'];
$fuel = $_POST['fuel'];
$capacity = $_POST['capacity'];
$price = $_POST['price'];
$available = $_POST['available'];

$image = $car['CAR_IMG'];

// IMAGE CHANGE
if(!empty($_FILES['image']['name'])){
    
    if(file_exists("images/".$image)){
        unlink("images/".$image);
    }

    $filename = $_FILES['image']['name'];
    $tempname = $_FILES['image']['tmp_name'];
    move_uploaded_file($tempname,"images/".$filename);

    $image = $filename;
}

// UPDATE QUERY
$query = "UPDATE cars SET 
CAR_NAME='$name',
FUEL_TYPE='$fuel',
CAPACITY='$capacity',
PRICE='$price',
AVAILABLE='$available',
CAR_IMG='$image'
WHERE CAR_ID='$id'";

mysqli_query($con,$query);

echo "<script>alert('Car Updated Successfully'); window.location='adminvehicle.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Car</title>

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
width:420px;
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

/* INPUT */

input,select{
width:100%;
padding:10px;
margin:10px 0;
border:none;
border-radius:6px;
}

input:focus,select:focus{
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
overflow:hidden;
}

.preview img{
width:100%;
height:100%;
object-fit:cover;
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

<h2>Edit Car</h2>

<!-- CURRENT IMAGE -->
<div class="preview">
<img id="previewImg" src="images/<?php echo $car['CAR_IMG']; ?>">
</div>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" value="<?php echo $car['CAR_NAME']; ?>" required>

<input type="text" name="fuel" value="<?php echo $car['FUEL_TYPE']; ?>" required>

<input type="number" name="capacity" value="<?php echo $car['CAPACITY']; ?>" required>

<input type="number" name="price" value="<?php echo $car['PRICE']; ?>" required>

<select name="available">
<option value="Y" <?php if($car['AVAILABLE']=='Y') echo 'selected'; ?>>YES</option>
<option value="N" <?php if($car['AVAILABLE']=='N') echo 'selected'; ?>>NO</option>
</select>

<label>Change Image</label>
<input type="file" name="image" accept="image/*" onchange="previewImage(event)">

<button name="update">Update Car</button>

</form>

</div>

</div>

<!-- JS IMAGE PREVIEW -->

<script>
function previewImage(event){
const file = event.target.files[0];
if(file){
document.getElementById("previewImg").src = URL.createObjectURL(file);
}
}
</script>

</body>
</html>