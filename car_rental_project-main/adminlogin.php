<?php
session_start();
require_once('connection.php');

$error = "";

/* 🔐 Simple rate-limit (optional but useful) */
$_SESSION['attempts'] = $_SESSION['attempts'] ?? 0;
if($_SESSION['attempts'] > 5){
    $error = "Too many attempts. Try again later.";
}

if(isset($_POST['adlog']) && $_SESSION['attempts'] <= 5){

    $id = trim($_POST['adid'] ?? '');
    $pass = trim($_POST['adpass'] ?? '');

    if(empty($id) || empty($pass)){
        $error = "Please fill all fields";
    } else {

        $stmt = $con->prepare("SELECT * FROM admin WHERE ADMIN_ID=?");
        $stmt->bind_param("s",$id);
        $stmt->execute();
        $res = $stmt->get_result();

        if($row = $res->fetch_assoc()){

            /* 🔐 If you later store hashed passwords, switch to password_verify */
            // if(password_verify($pass, $row['ADMIN_PASSWORD']))

            if($pass === $row['ADMIN_PASSWORD']){
                $_SESSION['admin_id'] = $row['ADMIN_ID'];
                $_SESSION['attempts'] = 0;
                header("Location: admindash.php");
                exit();
            } else {
                $_SESSION['attempts']++;
                $error = "Invalid credentials";
            }

        } else {
            $_SESSION['attempts']++;
            $error = "Invalid credentials";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel Login</title>

<style>

body{
    margin:0;
    font-family:'Segoe UI', sans-serif;
    height:100vh;
    background:#0f172a;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* MAIN CARD */
.container{
    width:900px;
    display:flex;
    border-radius:15px;
    overflow:hidden;
    box-shadow:0 10px 40px rgba(0,0,0,0.5);
}

/* LEFT PANEL (IMAGE + OVERLAY FIX) */
.left{
    width:50%;
    padding:50px;
    color:white;
    position:relative;
    background:url("images/adminbglock.jpg") center/cover no-repeat;
}

.left::before{
    content:"";
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.6);
}

.left *{
    position:relative;
}

.left h1{
    font-size:40px;
}

.left p{
    margin-top:15px;
    color:#ddd;
}

.badge{
    margin-top:30px;
    display:inline-block;
    padding:8px 15px;
    background:rgba(255,255,255,0.2);
    border-radius:20px;
}

/* RIGHT PANEL */
.right{
    width:50%;
    padding:50px;
    background:#1e293b;
    color:white;
}

/* INPUT */
.input{
    width:100%;
    padding:12px;
    margin:10px 0;
    border:none;
    border-radius:8px;
    background:#334155;
    color:white;
}

.input:focus{
    outline:none;
    box-shadow:0 0 8px #ff7200;
}

/* BUTTON */
.btn{
    width:100%;
    padding:12px;
    background:#ff7200;
    border:none;
    border-radius:8px;
    color:white;
    cursor:pointer;
    margin-top:15px;
}

.btn:hover{
    background:#ff9500;
}

/* ERROR */
.error{
    color:#ff4d4d;
    margin-bottom:10px;
}

/* FOOTER */
.footer{
    margin-top:20px;
    font-size:13px;
    color:#94a3b8;
}

/* SHOW PASSWORD */
.show{
    font-size:13px;
    margin-top:5px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .container{
        flex-direction:column;
        width:90%;
    }
    .left, .right{
        width:100%;
    }
}

</style>
</head>

<body>

<div class="container">

<!-- LEFT -->
<div class="left">
    <h1>Admin Control</h1>
    <p>Manage vehicles, bookings, users & system securely.</p>
    <div class="badge">🔐 Secure Access</div>
</div>

<!-- RIGHT -->
<div class="right">

<h2>Admin Login</h2>

<?php if($error){ ?>
<p class="error"><?php echo $error; ?></p>
<?php } ?>

<form method="POST">

<input class="input" type="text" name="adid" placeholder="Admin ID" autofocus>

<input class="input" type="password" name="adpass" id="pass" placeholder="Password">

<div class="show">
    <input type="checkbox" onclick="togglePass()"> Show Password
</div>

<input class="btn" id="btn" type="submit" name="adlog" value="Login">

</form>

<div class="footer">
Authorized access only • Activity monitored
</div>

</div>

</div>

<script>
function togglePass(){
    var x = document.getElementById("pass");
    x.type = x.type === "password" ? "text" : "password";
}

document.querySelector("form").addEventListener("submit", function(){
    document.getElementById("btn").value = "Checking...";
});
</script>

</body>
</html>