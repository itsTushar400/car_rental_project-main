<?php
require_once('connection.php');

/* SEARCH */
$search = $_GET['search'] ?? '';

/* PAGINATION */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

/* SAFE SEARCH */
$search_safe = mysqli_real_escape_string($con,$search);

/* QUERY */
$query = "SELECT * FROM contact_messages 
WHERE name LIKE '%$search_safe%' OR email LIKE '%$search_safe%'
ORDER BY id DESC LIMIT $offset,$limit";

$result = mysqli_query($con,$query);

/* TOTAL */
$total = mysqli_query($con,"SELECT COUNT(*) as count FROM contact_messages 
WHERE name LIKE '%$search_safe%' OR email LIKE '%$search_safe%'");

$total_row = mysqli_fetch_assoc($total);
$total_pages = ceil($total_row['count'] / $limit);

/* DELETE */
if(isset($_GET['delete'])){
    $id = intval($_GET['delete']);
    mysqli_query($con,"DELETE FROM contact_messages WHERE id='$id'");
    header("Location: admin_messages.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Messages</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial;}
body{background:#f4f4f4;}

/* SIDEBAR */
.sidebar{
position:fixed;
width:220px;
height:100%;
background:#111;
}
.sidebar h2{
color:#ff7200;
text-align:center;
padding:20px;
}
.sidebar a{
display:block;
color:white;
text-decoration:none;
padding:12px;
text-align:center;
}
.sidebar a:hover,
.sidebar .active{
background:#ff7200;
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
margin-bottom:15px;
}
.search{
padding:8px;
border-radius:5px;
border:1px solid #ccc;
}

/* TABLE */
table{
width:100%;
background:white;
border-collapse:collapse;
box-shadow:0 0 10px rgba(0,0,0,0.2);
border-radius:10px;
overflow:hidden;
}
th,td{
padding:12px;
text-align:center;
}
th{
background:#ff7200;
color:white;
}
tr:nth-child(even){
background:#f2f2f2;
}

/* BUTTON */
.delete{
background:red;
color:white;
padding:6px 10px;
border-radius:5px;
text-decoration:none;
}
.view{
background:green;
color:white;
padding:6px 10px;
border-radius:5px;
cursor:pointer;
}

/* PAGINATION */
.pagination{
margin-top:20px;
text-align:center;
}
.pagination a{
margin:5px;
padding:8px 12px;
background:#ddd;
text-decoration:none;
border-radius:5px;
}
.pagination a:hover{
background:#ff7200;
color:white;
}

/* MODAL */
.modal{
display:none;
position:fixed;
top:0;left:0;
width:100%;height:100%;
background:rgba(0,0,0,0.7);
justify-content:center;
align-items:center;
}
.modal-content{
background:white;
padding:20px;
border-radius:10px;
width:400px;
}
.close{
float:right;
cursor:pointer;
font-size:20px;
}
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h2>CaRs</h2>


<a href="adminvehicle.php">Vehicle</a>
<a href="users.php">Users</a>
<a href="feedback.php">Feedbacks</a>
<a href="adminbook.php">Booking</a>
<a href="admin_messages.php" class="active">Messages</a>
<a href="index.php">Logout</a>
</div>

<div class="main">

<div class="top">
<h2>Messages</h2>

<form method="GET">
<input type="text" name="search" class="search" placeholder="Search..." 
value="<?php echo htmlspecialchars($search); ?>">
<button>Search</button>
</form>
</div>

<table>

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Message</th>
<th>Date</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo htmlspecialchars($row['name']); ?></td>
<td><?php echo htmlspecialchars($row['email']); ?></td>

<td>
<?php echo htmlspecialchars(substr($row['message'],0,20)); ?>...
<button class="view" 
onclick="showMsg('<?php echo htmlspecialchars(addslashes($row['message'])); ?>')">
View
</button>
</td>

<td><?php echo $row['created_at']; ?></td>

<td>
<a href="?delete=<?php echo $row['id']; ?>" 
class="delete"
onclick="return confirm('Delete message?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>

<!-- PAGINATION -->
<div class="pagination">
<?php for($i=1;$i<=$total_pages;$i++){ ?>
<a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>">
<?php echo $i; ?>
</a>
<?php } ?>
</div>

</div>

<!-- MODAL -->
<div class="modal" id="modal">
<div class="modal-content">
<span class="close" onclick="closeModal()">&times;</span>
<p id="fullMsg"></p>
</div>
</div>

<script>
function showMsg(msg){
document.getElementById("modal").style.display="flex";
document.getElementById("fullMsg").innerText=msg;
}

function closeModal(){
document.getElementById("modal").style.display="none";
}
</script>

</body>
</html>