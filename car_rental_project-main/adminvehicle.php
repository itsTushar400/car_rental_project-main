<?php
require_once('connection.php');
$query="SELECT * FROM cars";
$result=mysqli_query($con,$query);
$page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Vehicle</title>

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

.sidebar ul li a.active,
.sidebar ul li a:hover{
background:#ff7200;
border-radius:5px;
}

/* MAIN */

.main{
margin-left:230px;
padding:30px;
}

/* HEADER */

h2{
text-align:center;
margin-bottom:20px;
}

/* ADD BUTTON */

.add{
display:inline-block;
margin-bottom:20px;
background:#ff7200;
color:white;
padding:10px 15px;
border-radius:6px;
text-decoration:none;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 0 10px rgba(0,0,0,0.2);
border-radius:10px;
overflow:hidden;
}

th{
background:orange;
color:white;
padding:12px;
}

td{
padding:12px;
text-align:center;
}

tr:nth-child(even){
background:#f3f3f3;
}

/* IMAGE */

img{
width:80px;
height:50px;
object-fit:cover;
border-radius:6px;
}

/* BUTTONS */

.btn{
padding:6px 12px;
border:none;
color:white;
cursor:pointer;
border-radius:6px;
}

.edit{background:blue;}
.delete{background:red;}

.action{
display:flex;
justify-content:center;
gap:8px;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h2 class="logo">CaRs</h2>

<ul>
<li>
<a href="adminvehicle.php" class="<?php if($page=='adminvehicle.php') echo 'active'; ?>">
Vehicle Management
</a>
</li>
<li><a href="adminusers.php">Users</a></li>
<li><a href="admindash.php">Feedbacks</a></li>
<li><a href="admin_messages.php">Messages</a></li>
<li><a href="adminbook.php">Booking</a></li>
<li><a href="index.php">Logout</a></li>
</ul>

</div>

<!-- MAIN -->

<div class="main">

<h2>CARS</h2>

<a href="addcar.php" class="add">+ Add Car</a>

<table>

<tr>
<th>ID</th>
<th>IMAGE</th>
<th>NAME</th>
<th>FUEL</th>
<th>CAPACITY</th>
<th>PRICE</th>
<th>AVAILABLE</th>
<th>ACTION</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?php echo $row['CAR_ID']; ?></td>

<td>
<?php if(!empty($row['CAR_IMG']) && file_exists("images/".$row['CAR_IMG'])){ ?>
<img src="images/<?php echo $row['CAR_IMG']; ?>">
<?php } else { echo "No Image"; } ?>
</td>

<td><?php echo $row['CAR_NAME']; ?></td>
<td><?php echo $row['FUEL_TYPE']; ?></td>
<td><?php echo $row['CAPACITY']; ?></td>
<td><?php echo $row['PRICE']; ?></td>
<td><?php echo ($row['AVAILABLE']=='Y')?'YES':'NO'; ?></td>

<td>
<div class="action">

<a href="editcar.php?id=<?php echo $row['CAR_ID']; ?>">
<button class="btn edit">Edit</button>
</a>

<a href="deletecar.php?id=<?php echo $row['CAR_ID']; ?>" onclick="return confirm('Delete this car?')">
<button class="btn delete">Delete</button>
</a>

</div>
</td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>