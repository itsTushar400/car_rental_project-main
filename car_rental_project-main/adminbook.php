<?php
require_once('connection.php');

/* ================= SEARCH + FILTER ================= */
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? '';

$query = "SELECT 
b.*, 
c.CAR_NAME,
d.NAME AS DRIVER_NAME, 
d.PHONE AS DRIVER_PHONE
FROM booking b
LEFT JOIN cars c ON b.CAR_ID = c.CAR_ID
LEFT JOIN drivers d ON b.DRIVER_ID = d.ID
WHERE 1";

if($search){
$query .= " AND (
b.EMAIL LIKE '%$search%' OR 
c.CAR_NAME LIKE '%$search%' OR 
b.BOOK_PLACE LIKE '%$search%'
)";
}

if($statusFilter){
$query .= " AND b.BOOK_STATUS='$statusFilter'";
}

$query .= " ORDER BY b.BOOK_ID DESC";

$result = mysqli_query($con,$query);

/* ✅ SERIAL FIX */
$serial = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html>
<head>
<title>ADMIN - BOOKINGS</title>

<meta http-equiv="refresh" content="5">

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial;}
body{background:#f4f6f9;}

.sidebar{
position:fixed;width:220px;height:100%;background:#111;padding-top:20px;
}
.logo{
color:#ff7200;font-size:28px;text-align:center;margin-bottom:30px;
}
.sidebar ul{list-style:none;}
.sidebar ul li{text-align:center;margin:15px 0;}
.sidebar ul li a{
color:white;text-decoration:none;padding:10px;display:block;
}
.sidebar ul li a.active,
.sidebar ul li a:hover{
background:#ff7200;border-radius:6px;
}
.logout-btn{
background:#ff7200;border:none;padding:8px 15px;border-radius:6px;
}
.logout-btn a{color:white;text-decoration:none;}

.main{margin-left:220px;padding:25px;}

.header-box{
background:white;padding:15px;border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
margin-bottom:20px;text-align:center;
}
.header{font-size:26px;}

.search-box{
margin-bottom:15px;
display:flex;
gap:10px;
}

.search-box input,
.search-box select{
padding:8px;
border-radius:6px;
border:1px solid #ccc;
}

.search-box button{
padding:8px 15px;
background:#ff7200;
border:none;
color:white;
border-radius:6px;
cursor:pointer;
}

.export-btn{
margin-bottom:10px;
background:green;
color:white;
padding:8px 15px;
border:none;
border-radius:6px;
cursor:pointer;
}

.table-box{
background:white;border-radius:10px;
box-shadow:0 10px 25px rgba(0,0,0,0.1);
overflow:auto;
}
table{width:100%;border-collapse:collapse;}

th{
background:#ff7200;color:white;padding:10px;font-size:12px;
}

td{
padding:10px;text-align:center;font-size:12px;
border-bottom:1px solid #ddd;
}

tr:nth-child(even){background:#f9f9f9;}

.btn{
border:none;padding:5px 10px;border-radius:6px;
cursor:pointer;font-size:11px;margin:2px;
}

.approve{background:green;color:white;}
.reject{background:red;color:white;}
.return{background:#007bff;color:white;}

.status{
padding:4px 10px;
border-radius:20px;
font-size:11px;
color:white;
}

.pending{background:orange;}
.approved{background:green;}
.rejected{background:red;}
.returned{background:#007bff;}
</style>

</head>

<body>

<div class="sidebar">
<h2 class="logo">CaRs</h2>
<ul>
<li><a href="adminvehicle.php">Vehicle Management</a></li>
<li><a href="adminusers.php">Users</a></li>
<li><a href="admindash.php">Feedbacks</a></li>
<li><a href="adminbook.php" class="active">Bookings</a></li>
<li><a href="transactions.php">Transactions</a></li>
<li><a href="drivers.php">Drivers</a></li>
<li>
<button class="logout-btn">
<a href="logout.php">Logout</a>
</button>
</li>
</ul>
</div>

<div class="main">

<div class="header-box">
<h1 class="header">BOOKING MANAGEMENT</h1>
</div>

<form method="GET" class="search-box">
<input type="text" name="search" placeholder="Search..." value="<?php echo $search; ?>">

<select name="status">
<option value="">All Status</option>
<option value="Pending" <?php if($statusFilter=="Pending") echo "selected"; ?>>Pending</option>
<option value="Approved" <?php if($statusFilter=="Approved") echo "selected"; ?>>Approved</option>
<option value="Returned" <?php if($statusFilter=="Returned") echo "selected"; ?>>Returned</option>
<option value="Rejected" <?php if($statusFilter=="Rejected") echo "selected"; ?>>Rejected</option>
</select>

<button type="submit">Search</button>
</form>

<a href="export.php">
<button class="export-btn">Export Excel</button>
</a>

<div class="table-box">

<table>

<tr>
<th>S.No</th>
<th>CAR</th>
<th>EMAIL</th>
<th>PLACE</th>
<th>DATE</th>
<th>DAYS</th>
<th>PHONE</th>
<th>LICENSE</th>
<th>DESTINATION</th>
<th>DRIVER OPTION</th>
<th>DRIVER NAME</th>
<th>DRIVER PHONE</th>
<th>RETURN DATE</th>
<th>PAYMENT</th>
<th>STATUS</th>
<th>ACTION</th>
</tr>

<?php 
while($row=mysqli_fetch_assoc($result)){ 

$status = $row['BOOK_STATUS'] ?? 'Pending';
$payment = $row['PAYMENT'] ?? 'Not Paid';

$license = $row['LICENSE_NO'] ?? '';
$license = $license ? substr($license,0,4)."****".substr($license,-4) : "-";
?>

<tr>

<!-- ✅ FIXED SERIAL -->
<td><?php echo $serial--; ?></td>

<td><?php echo $row['CAR_NAME']; ?></td>
<td><?php echo $row['EMAIL']; ?></td>
<td><?php echo $row['BOOK_PLACE']; ?></td>
<td><?php echo $row['BOOK_DATE']; ?></td>
<td><?php echo $row['DURATION']; ?></td>
<td><?php echo $row['PHONE_NUMBER']; ?></td>
<td><?php echo $license; ?></td>
<td><?php echo $row['DESTINATION']; ?></td>
<td><?php echo $row['DRIVER_OPTION']; ?></td>

<td>
<?php echo ($row['DRIVER_OPTION']=="Self Drive" || empty($row['DRIVER_NAME'])) ? "-" : $row['DRIVER_NAME']; ?>
</td>

<td>
<?php echo ($row['DRIVER_OPTION']=="Self Drive" || empty($row['DRIVER_PHONE'])) ? "-" : $row['DRIVER_PHONE']; ?>
</td>

<td><?php echo $row['RETURN_DATE']; ?></td>

<td>
<?php echo ($payment=="Paid") ? "<span style='color:green;'>✔ Paid</span>" : "<span style='color:red;'>Not Paid</span>"; ?>
</td>

<td>
<?php
if($status=="Pending") echo "<span class='status pending'>Pending</span>";
elseif($status=="Approved") echo "<span class='status approved'>Approved</span>";
elseif($status=="Rejected") echo "<span class='status rejected'>Rejected</span>";
elseif($status=="Returned") echo "<span class='status returned'>Returned</span>";
?>
</td>

<td>

<?php if($status=="Pending"){ ?>

    <?php if($payment=="Paid"){ ?>
        <a href="approve.php?id=<?php echo $row['BOOK_ID']; ?>">
            <button class="btn approve">Approve</button>
        </a>
    <?php } ?>

    <a href="reject.php?id=<?php echo $row['BOOK_ID']; ?>">
        <button class="btn reject">Reject</button>
    </a>

<?php } ?>

<?php if($status=="Approved"){ ?>
    <a href="adminreturn.php?id=<?php echo $row['BOOK_ID']; ?>">
        <button class="btn return">Return</button>
    </a>
<?php } ?>

<?php if($status=="Returned" || $status=="Rejected"){ echo "-"; } ?>

</td>

</tr>

<?php } ?>

</table>

</div>

</div>

</body>
</html>