<?php
require_once('connection.php');

/* SEARCH */
$search = $_GET['search'] ?? '';

/* PAGINATION */
$limit = 5;
$page_no = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page_no = max($page_no,1);
$offset = ($page_no - 1) * $limit;

/* QUERY */
$stmt = $con->prepare("SELECT * FROM drivers WHERE NAME LIKE ? LIMIT ?,?");
$param = "%{$search}%";
$stmt->bind_param("sii",$param,$offset,$limit);
$stmt->execute();
$result = $stmt->get_result();

/* COUNT */
$count = $con->prepare("SELECT COUNT(*) as count FROM drivers WHERE NAME LIKE ?");
$count->bind_param("s",$param);
$count->execute();
$total = $count->get_result()->fetch_assoc();
$total_pages = ceil($total['count']/$limit);

/* SERIAL */
$serial = $offset + 1;
?>

<!DOCTYPE html>
<html>
<head>
<title>Driver Management</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{background:#f1f5f9;}

/* SIDEBAR */
.sidebar{
position:fixed;
width:230px;
height:100vh;
background:linear-gradient(180deg,#0f172a,#1e293b);
padding-top:20px;
}
.logo{
color:#f97316;
text-align:center;
font-size:28px;
margin-bottom:30px;
}
.sidebar ul{list-style:none;}
.sidebar ul li{margin:10px 15px;}
.sidebar ul li a{
display:block;
padding:12px;
color:white;
text-decoration:none;
border-radius:8px;
transition:0.3s;
}
.sidebar ul li a:hover,
.sidebar ul li a.active{
background:#f97316;
}

/* MAIN */
.main{
margin-left:230px;
padding:30px;
}

/* TOP */
.top{
display:flex;
justify-content:space-between;
align-items:center;
}

.search-box{display:flex;gap:10px;}

.search{
padding:10px;
border-radius:8px;
border:1px solid #ccc;
}

.search-btn{
background:#0f172a;
color:white;
padding:10px 15px;
border:none;
border-radius:8px;
cursor:pointer;
}

.add-btn{
background:#0f172a;
color:white;
padding:10px 18px;
border:none;
border-radius:8px;
cursor:pointer;
}

/* TABLE */
.table-box{
margin-top:20px;
background:white;
border-radius:12px;
overflow:hidden;
box-shadow:0 5px 20px rgba(0,0,0,0.1);
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#f97316;
color:white;
padding:15px;
}

td{
padding:14px;
text-align:center;
}

tr:nth-child(even){
background:#f9fafb;
}

/* IMAGE */
img{
width:55px;
height:55px;
border-radius:50%;
object-fit:cover;
border:2px solid #f97316;
}

/* STATUS */
.status{
padding:5px 12px;
border-radius:20px;
color:white;
font-size:12px;
}

.active-status{background:#22c55e;}
.inactive-status{background:#ef4444;}
.booked-status{background:#f59e0b;}

/* BUTTON */
.btn{
padding:6px 10px;
border-radius:6px;
color:white;
text-decoration:none;
font-size:12px;
margin:2px;
}

.toggle{background:green;}
.assign{background:purple;}
.edit{background:blue;}
.delete{background:red;}

/* PAGINATION */
.pagination{
margin-top:20px;
text-align:center;
}
.pagination a{
padding:8px 12px;
margin:5px;
background:#ddd;
border-radius:5px;
text-decoration:none;
color:black;
}
.pagination a.active{
background:#f97316;
color:white;
}
</style>

</head>

<body>

<div class="sidebar">
<h2 class="logo">CaRs</h2>

<ul>
<li><a href="adminvehicle.php">🚗 Vehicles</a></li>
<li><a href="drivers.php" class="active">👨‍✈️ Drivers</a></li>
<li><a href="adminbook.php">📖 Bookings</a></li>
<li><a href="transactions.php">💰 Transactions</a></li>
<li><a href="index.php">🚪 Logout</a></li>
</ul>
</div>

<div class="main">

<div class="top">
<h2>Drivers</h2>

<form method="GET" class="search-box">
<input type="text" name="search" class="search" placeholder="Search Driver"
value="<?php echo htmlspecialchars($search); ?>">
<button class="search-btn">Search</button>
</form>

<a href="add_driver.php">
<button class="add-btn">+ Add</button>
</a>
</div>

<div class="table-box">
<table>

<tr>
<th>S.No</th>
<th>Image</th>
<th>Name</th>
<th>Phone</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php if($result->num_rows > 0){ ?>
<?php while($row = $result->fetch_assoc()){ ?>

<tr>

<td><?php echo $serial++; ?></td>

<td>
<?php
$imgName = $row['IMAGE'] ?? '';
$imgPath = "images/".$imgName;

if(!empty($imgName) && file_exists($imgPath)){ ?>
<img src="<?php echo $imgPath; ?>">
<?php } else { ?>
<img src="images/default.png">
<?php } ?>
</td>

<td><?php echo htmlspecialchars($row['NAME']); ?></td>
<td><?php echo htmlspecialchars($row['PHONE']); ?></td>

<td>
<?php
$status = $row['STATUS'] ?? 'Inactive';

if($status == 'Active'){
echo "<span class='status active-status'>Active</span>";
}elseif($status == 'Inactive'){
echo "<span class='status inactive-status'>Inactive</span>";
}else{
echo "<span class='status booked-status'>$status</span>";
}
?>
</td>

<td>
<a href="toggle_driver.php?id=<?php echo $row['ID']; ?>" class="btn toggle">Toggle</a>
<a href="assign_driver.php?id=<?php echo $row['ID']; ?>" class="btn assign">Assign</a>
<a href="edit_driver.php?id=<?php echo $row['ID']; ?>" class="btn edit">Edit</a>
<a href="delete_driver.php?id=<?php echo $row['ID']; ?>" class="btn delete"
onclick="return confirm('Delete driver?')">Delete</a>
</td>

</tr>

<?php } ?>
<?php } else { ?>
<tr><td colspan="6">No drivers found</td></tr>
<?php } ?>

</table>
</div>

<!-- PAGINATION -->
<div class="pagination">
<?php for($i=1;$i<=$total_pages;$i++){ ?>
<a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"
class="<?php echo ($i==$page_no)?'active':''; ?>">
<?php echo $i; ?>
</a>
<?php } ?>
</div>

</div>

</body>
</html>