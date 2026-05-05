<?php
require_once('connection.php');

$page = basename($_SERVER['PHP_SELF']);

/* DELETE FEEDBACK */
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($con,"DELETE FROM feedback WHERE id='$id'");
    header("Location: admindash.php");
    exit();
}

/* FETCH FEEDBACK */
$query = "SELECT * FROM feedback ORDER BY id DESC";
$result = mysqli_query($con,$query);

$count = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>ADMIN - FEEDBACKS</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Arial;
}

body{
background:#f4f6f9;
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

.logo{
color:#ff7200;
font-size:30px;
text-align:center;
margin-bottom:30px;
}

.sidebar ul{
list-style:none;
}

.sidebar ul li{
text-align:center;
margin:18px 0;
}

.sidebar ul li a{
color:white;
text-decoration:none;
font-size:15px;
display:block;
padding:10px;
}

.sidebar ul li a.active{
background:#ff7200;
border-radius:6px;
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

/* MAIN */

.main{
margin-left:230px;
padding:30px;
}

/* HEADER */

.header-box{
background:white;
padding:20px;
border-radius:12px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
margin-bottom:20px;
text-align:center;
}

.header{
font-size:26px;
margin-bottom:10px;
}

.count{
color:#555;
}

/* TABLE */

.table-box{
background:white;
border-radius:12px;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
overflow:hidden;
}

table{
width:100%;
border-collapse:collapse;
}

thead{
background:#ff7200;
color:white;
}

th,td{
padding:12px;
text-align:center;
font-size:14px;
}

tbody tr{
border-bottom:1px solid #ddd;
}

tbody tr:nth-child(even){
background:#f9f9f9;
}

/* DELETE BUTTON */

.delete{
background:red;
color:white;
padding:6px 12px;
border-radius:6px;
text-decoration:none;
font-size:13px;
}

.delete:hover{
background:#cc0000;
}

</style>

</head>

<body>

<!-- SIDEBAR -->

<div class="sidebar">

<h2 class="logo">CaRs</h2>

<ul>

<li>
<a href="adminvehicle.php"
class="<?php if($page=='adminvehicle.php'){echo 'active';} ?>">
Vehicle Management
</a>
</li>

<li>
<a href="adminusers.php"
class="<?php if($page=='adminusers.php'){echo 'active';} ?>">
Users
</a>
</li>

<li>
<a href="admindash.php"
class="<?php if($page=='admindash.php'){echo 'active';} ?>">
Feedbacks
</a>
</li>

<li>
<a href="adminbook.php"
class="<?php if($page=='adminbook.php'){echo 'active';} ?>">
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

<!-- MAIN -->

<div class="main">

<div class="header-box">
<h1 class="header">CUSTOMER FEEDBACKS</h1>
<div class="count">
Total Feedbacks : <?php echo $count; ?>
</div>
</div>

<div class="table-box">

<table>

<thead>
<tr>
<th>ID</th>
<th>EMAIL</th>
<th>COMMENT</th>
<th>RATING</th>
<th>ACTION</th>
</tr>
</thead>

<tbody>

<?php
if($count > 0){
    while($row = mysqli_fetch_assoc($result)){

        // ✅ Safe values
        $id = $row['id'] ?? 'N/A';
        $email = $row['email'] ?? 'N/A';
        $message = $row['message'] ?? 'No Comment';
        $rating = $row['rating'] ?? 0;
?>

<tr>

<td><?php echo $id; ?></td>

<td><?php echo htmlspecialchars($email); ?></td>

<td><?php echo htmlspecialchars($message); ?></td>

<td>
<?php 
if($rating > 0){
    for($i=1; $i<=5; $i++){
        if($i <= $rating){
            echo "<span style='color:#FFD700;font-size:18px;'>★</span>";
        } else {
            echo "<span style='color:#ccc;font-size:18px;'>★</span>";
        }
    }
}else{
    echo "<span style='color:#999;'>No Rating</span>";
}
?>
</td>

<td>
<a class="delete"
href="admindash.php?delete=<?php echo $id; ?>"
onclick="return confirm('Are you sure you want to delete this feedback?')">
Delete
</a>
</td>

</tr>

<?php
    }
}else{
?>

<tr>
<td colspan="5">No Feedback Available</td>
</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</body>
</html>