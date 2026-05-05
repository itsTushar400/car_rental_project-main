<?php
require_once('connection.php');

/* FILTER */
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';
$status = $_GET['status'] ?? '';

/* REFUND */
if(isset($_GET['refund'])){
    $id = intval($_GET['refund']);

    mysqli_query($con,"UPDATE booking SET PAYMENT='Refunded' WHERE BOOK_ID='$id'");
    mysqli_query($con,"UPDATE payment SET refund_status='Refunded' WHERE BOOK_ID='$id'");

    header("Location: transactions.php");
    exit;
}

/* WHERE */
$where = "WHERE (b.PAYMENT='Paid' OR b.PAYMENT='Refunded')";

if($from && $to){
    $where .= " AND DATE(b.BOOK_DATE) BETWEEN '$from' AND '$to'";
}

/* STATS */
$total = mysqli_fetch_assoc(mysqli_query($con,"
SELECT SUM(b.PRICE) as total
FROM booking b
$where AND b.PAYMENT='Paid'
"))['total'] ?? 0;

$refunded = mysqli_fetch_assoc(mysqli_query($con,"
SELECT SUM(b.PRICE) as total
FROM booking b
$where AND b.PAYMENT='Refunded'
"))['total'] ?? 0;

$count = mysqli_fetch_assoc(mysqli_query($con,"
SELECT COUNT(*) as total
FROM booking b
$where
"))['total'] ?? 0;

$net = $total - $refunded;

/* FETCH */
$q = mysqli_query($con,"
SELECT 
b.BOOK_ID,
b.PRICE,
b.PAYMENT,
c.CAR_NAME,
u.FNAME,
u.LNAME,
IFNULL(p.payment_method,'Online') as payment_method,
IFNULL(p.card_number,'') as card_number,
IFNULL(p.upi_id,'') as upi_id,
IFNULL(p.refund_status,'Paid') as refund_status
FROM booking b
JOIN cars c ON b.CAR_ID=c.CAR_ID
JOIN users u ON b.EMAIL=u.EMAIL
LEFT JOIN payment p ON b.BOOK_ID=p.BOOK_ID
$where
ORDER BY b.BOOK_ID DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Transactions Dashboard</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:'Segoe UI',sans-serif;}
body{display:flex;background:#f1f5f9;}

/* SIDEBAR */
.sidebar{
width:240px;height:100vh;
background:linear-gradient(180deg,#0f172a,#1e293b);
color:white;padding:25px;position:fixed;
}
.sidebar h2{color:#f97316;margin-bottom:30px;text-align:center;}
.sidebar a{
display:block;padding:12px;margin:8px 0;
border-radius:8px;color:white;text-decoration:none;
transition:0.3s;
}
.sidebar a:hover,
.sidebar a.active{
background:#f97316;
transform:translateX(5px);
}

/* MAIN */
.main{margin-left:260px;padding:30px;width:100%;}
h1{margin-bottom:20px;}

/* FILTER */
.filter input,.filter select{
padding:10px;border-radius:8px;border:1px solid #ccc;margin-right:10px;
}
.filter button{
padding:10px 18px;background:#3b82f6;color:white;border:none;border-radius:8px;
}

/* CARDS */
.cards{
display:grid;
grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
gap:20px;margin-bottom:25px;
}
.card{
background:white;padding:25px;border-radius:15px;
box-shadow:0 10px 25px rgba(0,0,0,0.08);
text-align:center;
}
.card h3{color:#64748b;}
.card p{font-size:28px;color:#f97316;font-weight:bold;}

/* TABLE */
.table-box{
background:white;border-radius:12px;overflow:hidden;
box-shadow:0 5px 20px rgba(0,0,0,0.08);
}
table{width:100%;border-collapse:collapse;}
th{background:#f97316;color:white;padding:15px;}
td{padding:14px;text-align:center;}
tr:nth-child(even){background:#f9fafb;}
tr:hover{background:#f1f5f9;}

/* BADGE */
.badge{padding:6px 12px;border-radius:20px;font-size:12px;color:white;}
.paid{background:#22c55e;}
.refunded{background:#ef4444;}

/* BUTTON */
.btn{padding:6px 12px;border:none;border-radius:8px;cursor:pointer;}
.refund{background:#ef4444;color:white;}
.done{background:#22c55e;color:white;}

/* EXPORT */
.export-btn{
background:linear-gradient(135deg,#22c55e,#15803d);
color:white;padding:10px 18px;border:none;border-radius:8px;
}
</style>

</head>

<body>

<!-- SIDEBAR -->
<div class="sidebar">
<h2>CaRs</h2>
<a href="adminvehicle.php">Vehicle</a>
<a href="adminusers.php">Users</a>
<a href="adminbook.php">Bookings</a>
<a href="transactions.php" class="active">Transactions</a>
<a href="drivers.php">Drivers</a>
<a href="logout.php">Logout</a>
</div>

<!-- MAIN -->
<div class="main">

<h1>💳 Transactions Dashboard</h1>

<!-- FILTER -->
<form method="GET" class="filter">
<input type="date" name="from" value="<?=$from?>">
<input type="date" name="to" value="<?=$to?>">
<select name="status">
<option value="">All</option>
<option value="paid" <?=($status=="paid")?"selected":""?>>Paid</option>
<option value="refunded" <?=($status=="refunded")?"selected":""?>>Refunded</option>
</select>
<button type="submit">Filter</button>
</form>

<!-- EXPORT -->
<div style="text-align:right;margin:15px 0;">
<a href="export.php">
<button class="export-btn">📥 Export Excel</button>
</a>
</div>

<!-- CARDS -->
<div class="cards">
<div class="card"><h3>Total Revenue</h3><p>₹<?=$total?></p></div>
<div class="card"><h3>Refunded</h3><p>₹<?=$refunded?></p></div>
<div class="card"><h3>Transactions</h3><p><?=$count?></p></div>
<div class="card"><h3>Net</h3><p>₹<?=$net?></p></div>
</div>

<!-- TABLE -->
<div class="table-box">
<table>

<tr>
<th>ID</th>
<th>User</th>
<th>Car</th>
<th>Amount</th>
<th>Method</th>
<th>Details</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row=mysqli_fetch_assoc($q)){ ?>

<tr>
<td><?=$row['BOOK_ID']?></td>
<td><?=$row['FNAME']." ".$row['LNAME']?></td>
<td><?=$row['CAR_NAME']?></td>
<td>₹<?=$row['PRICE']?></td>
<td><?=$row['payment_method']?></td>

<td>
<?php
if($row['payment_method']=="Card"){
echo "XXXX ".substr($row['card_number'],-4);
}elseif($row['payment_method']=="UPI"){
echo $row['upi_id'];
}else{
echo "Online";
}
?>
</td>

<td>
<?php if($row['refund_status']=="Refunded"){ ?>
<span class="badge refunded">Refunded</span>
<?php } else { ?>
<span class="badge paid">Paid</span>
<?php } ?>
</td>

<td>
<?php if($row['refund_status']=="Refunded"){ ?>
<span class="btn done">Done</span>
<?php } else { ?>
<a href="?refund=<?=$row['BOOK_ID']?>" onclick="return confirm('Refund?')">
<button class="btn refund">Refund</button>
</a>
<?php } ?>
</td>

</tr>

<?php } ?>

</table>
</div>

</div>

</body>
</html>