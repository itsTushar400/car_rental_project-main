<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ADMIN - USERS</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:url("../images/carbg2.jpg");
background-size:cover;
background-position:center;
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
font-size:32px;
text-align:center;
margin-bottom:30px;
}

.sidebar ul{
padding-left:0;
}

.sidebar ul li{
list-style:none;
margin:20px 0;
text-align:center;
}

.sidebar ul li a{
color:white;
text-decoration:none;
font-size:16px;
display:block;
padding:10px;
transition:0.3s;
}

/* ACTIVE MENU */

.sidebar ul li a.active{
background:#ff7200;
border-radius:5px;
}

.sidebar ul li a:hover{
background:#ff7200;
}

.logout-btn{
background:#ff7200;
border:none;
padding:8px 15px;
border-radius:6px;
cursor:pointer;
}

.logout-btn a{
color:white;
text-decoration:none;
}

/* MAIN CONTENT */

.main-content{
margin-left:230px;
padding:40px;
}

.header{
text-align:center;
margin-bottom:30px;
}

/* TABLE */

.content-table{
border-collapse:collapse;
width:100%;
background:white;
box-shadow:0 0 20px rgba(0,0,0,0.2);
}

.content-table thead tr{
background:orange;
color:white;
}

.content-table th,
.content-table td{
padding:12px;
text-align:center;
}

.content-table tbody tr{
border-bottom:1px solid #ddd;
}

.content-table tbody tr:nth-child(even){
background:#f3f3f3;
}

.deletebtn{
background:red;
color:white;
border:none;
padding:6px 12px;
border-radius:6px;
cursor:pointer;
}

</style>

</head>

<body>

<?php

require_once('connection.php');

$page = basename($_SERVER['PHP_SELF']);

$query="SELECT * FROM users";
$result=mysqli_query($con,$query);

?>

<!-- SIDEBAR -->

<div class="sidebar">

<h2 class="logo">CaRs</h2>

<ul>

<li>
<a href="adminvehicle.php" class="<?php if($page=='adminvehicle.php'){echo 'active';} ?>">
Vehicle Management
</a>
</li>

<li>
<a href="adminusers.php" class="<?php if($page=='adminusers.php'){echo 'active';} ?>">
Users
</a>
</li>

<li>
<a href="admindash.php" class="<?php if($page=='admindash.php'){echo 'active';} ?>">
Feedbacks
</a>
</li>

<li>
<a href="adminbook.php" class="<?php if($page=='adminbook.php'){echo 'active';} ?>">
Booking Request
</a>
</li>

<li>
<button class="logout-btn">
<a href="index.php">Logout</a>
</button>
</li>

</ul>

</div>

<!-- MAIN CONTENT -->

<div class="main-content">

<h1 class="header">USERS</h1>

<table class="content-table">

<thead>

<tr>

<th>NAME</th>
<th>EMAIL</th>
<th>LICENSE NUMBER</th>
<th>PHONE NUMBER</th>
<th>GENDER</th>
<th>DELETE USER</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?php echo $row['FNAME']." ".$row['LNAME']; ?></td>
<td><?php echo $row['EMAIL']; ?></td>
<td><?php echo $row['LIC_NUM']; ?></td>
<td><?php echo $row['PHONE_NUMBER']; ?></td>
<td><?php echo $row['GENDER']; ?></td>

<td>
<a href="deleteuser.php?id=<?php echo $row['EMAIL']; ?>">
<button class="deletebtn">DELETE USER</button>
</a>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</body>
</html>