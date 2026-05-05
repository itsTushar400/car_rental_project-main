<?php
require_once('connection.php');
session_start();

$error = "";

if(isset($_POST['login']))
{
$email = mysqli_real_escape_string($con, $_POST['email']);
$pass  = mysqli_real_escape_string($con, $_POST['pass']);

if(empty($email) || empty($pass))
{
$error = "Please fill all fields";
}
else
{
$query="select * from users where EMAIL='$email'";
$res=mysqli_query($con,$query);

if(mysqli_num_rows($res)>0)
{
$row=mysqli_fetch_assoc($res);
$db_password=$row['PASSWORD'];

if(password_verify($pass, $db_password))
{
$_SESSION['email']=$email;
header("location:cardetails.php");
exit();
}
else{
$error = "Wrong Password";
}
}
else{
$error = "Email not found";
}
}
}
?>
<!DOCTYPE html>
<html>
<head>

<title>CAR RENTAL</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins;
}

body{
background:
linear-gradient(rgba(0,0,0,0.65),rgba(0,0,0,0.75)),
url("images/carbg3.jpg");
background-size:cover;
background-position:center;
min-height:100vh;
}

/* NAVBAR */

.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:25px 80px;
background:rgba(0,0,0,0.4);
backdrop-filter:blur(10px);
}

.logo{
font-size:34px;
color:#ff7200;
font-weight:700;
}

.menu ul{
display:flex;
gap:40px;
list-style:none;
align-items:center;
}

.menu ul li a{
text-decoration:none;
color:white;
font-weight:500;
transition:0.3s;
}

.menu ul li a:hover{
color:#ff7200;
}

/* 🌈 FINAL ROTATING BORDER BUTTON */

.adminbtn{
position: relative;
padding: 10px 25px;
border-radius: 30px;
color: white;
text-decoration: none;
font-weight: 600;
background: #111;
z-index: 1;
overflow: hidden;
display: inline-block;
}

/* rotating gradient */
.adminbtn::before{
content: "";
position: absolute;
width: 300%;
height: 300%;
top: 50%;
left: 50%;
transform: translate(-50%, -50%) rotate(0deg);
background: conic-gradient(
red, orange, yellow, green, cyan, blue, violet, red
);
animation: spin 2s linear infinite;
z-index: -2;
}

/* inner background */
.adminbtn::after{
content: "";
position: absolute;
inset: 3px;
background: #111;
border-radius: 30px;
z-index: -1;
}

/* rotation animation */
@keyframes spin{
0%{
transform: translate(-50%, -50%) rotate(0deg);
}
100%{
transform: translate(-50%, -50%) rotate(360deg);
}
}

/* glow on hover */
.adminbtn:hover::before{
filter: blur(6px);
}

/* HERO */

.content{
margin-left:100px;
margin-top:140px;
max-width:550px;
}

.title{
font-size:65px;
font-weight:700;
color:white;
line-height:1.1;
}

.title span{
color:#ff7200;
}

.par{
margin-top:20px;
color:#ddd;
line-height:1.8;
font-size:17px;
}

/* BUTTON */

.cn{
margin-top:30px;
padding:16px 36px;
background:linear-gradient(45deg,#ff6a00,#ff8c00);
border:none;
border-radius:30px;
font-size:18px;
font-weight:600;
transition:0.3s;
cursor:pointer;
color:white;
}

.cn a{
text-decoration:none;
color:white;
}

.cn:hover{
transform:scale(1.08);
box-shadow:0 0 20px #ff6a00;
}

/* LOGIN CARD */

.form{
position:absolute;
right:80px;
top:50%;
transform:translateY(-50%);
width:340px;
padding:35px;
background:rgba(0,0,0,0.5);
backdrop-filter:blur(12px);
border-radius:15px;
box-shadow:0 0 40px rgba(0,0,0,0.6);
}

.form h2{
color:white;
text-align:center;
margin-bottom:20px;
}

.form input{
width:100%;
padding:12px;
margin-top:15px;
border:none;
border-radius:6px;
outline:none;
}

/* LOGIN BUTTON */

.btnn{
margin-top:20px;
background:linear-gradient(45deg,#ff6a00,#ff8c00);
color:white;
font-weight:600;
cursor:pointer;
transition:0.3s;
}

.btnn:hover{
box-shadow:0 0 15px #ff6a00;
}

/* ERROR */

.error{
color:#ff4d4d;
text-align:center;
margin-top:10px;
font-size:14px;
}

/* LINK */

.link{
margin-top:15px;
text-align:center;
color:white;
}

.link a{
color:#ff7200;
text-decoration:none;
}

/* RESPONSIVE */

@media(max-width:900px){

.content{
margin-left:30px;
margin-top:100px;
}

.title{
font-size:40px;
}

.form{
position:static;
transform:none;
margin:30px auto;
}

}

</style>

</head>

<body>

<div class="navbar">

<div class="logo">CaRs</div>

<div class="menu">

<ul>

<li><a href="#">HOME</a></li>
<li><a href="about.php">ABOUT</a></li>
<li><a href="services.php">SERVICES</a></li>
<li><a href="contactus.php">CONTACT</a></li>

<li>
<a href="adminlogin.php" class="adminbtn">ADMIN LOGIN</a>
</li>

</ul>

</div>

</div>

<div class="content">

<h1 class="title">Drive Your <br><span>Dream Car</span></h1>

<p class="par">
Experience luxury, performance, and comfort like never before.<br>
Book premium cars instantly for your perfect journey.
</p>

<button class="cn">
<a href="register.php">EXPLORE CARS</a>
</button>

</div>

<div class="form">

<h2>Login Here</h2>

<form method="POST">

<input type="email" name="email" placeholder="Enter Email" required>

<input type="password" name="pass" placeholder="Enter Password" required>

<input class="btnn" type="submit" name="login" value="Login">

</form>

<?php if(!empty($error)) { ?>
<div class="error"><?php echo $error; ?></div>
<?php } ?>

<p class="link">
Don't have an account?<br>
<a href="register.php">Sign up here</a>
</p>

</div>

</body>
</html>