<?php
require_once("connection.php");

$error = "";
$success = "";

if(isset($_POST['send']))
{
$name = mysqli_real_escape_string($con,$_POST['name']);
$email = mysqli_real_escape_string($con,$_POST['email']);
$message = mysqli_real_escape_string($con,$_POST['message']);

if(empty($name) || empty($email) || empty($message)){
    $error = "Please fill all fields";
}
else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
    $error = "Invalid Email";
}
else{
    $query="INSERT INTO contact_messages(name,email,message)
    VALUES('$name','$email','$message')";

    if(mysqli_query($con,$query)){
        $success = "Message Sent Successfully ✅";
    } else {
        $error = "Database Error";
    }
}
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Contact Us</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>

*{margin:0;padding:0;box-sizing:border-box;font-family:Poppins;}

.navbar{
display:flex;
justify-content:space-between;
align-items:center;
padding:20px 80px;
background:rgba(0,0,0,0.6);
backdrop-filter:blur(10px);
position:sticky;
top:0;
z-index:1000;
}

.logo{font-size:30px;color:#ff7200;font-weight:700;}

.menu ul{display:flex;gap:30px;list-style:none;}

.menu ul li a{
text-decoration:none;
color:white;
transition:0.3s;
}

.menu ul li a:hover{color:#ff7200;}

body{
background: radial-gradient(circle at 20% 30%, #ff7200, transparent 40%),
            radial-gradient(circle at 80% 70%, #ff0080, transparent 40%),
            radial-gradient(circle at 50% 50%, #7928ca, transparent 40%),
            linear-gradient(135deg, #0f0f0f, #1c1c1c);
color:#fff;
}

.title{text-align:center;font-size:45px;margin-top:30px;}

.container{
display:flex;
justify-content:space-between;
max-width:1100px;
margin:auto;
padding:50px 20px;
gap:40px;
flex-wrap:wrap;
}

.box{display:flex;align-items:center;margin-bottom:25px;}

.icon{
width:55px;height:55px;background:#ff7200;
border-radius:50%;
display:flex;align-items:center;justify-content:center;
margin-right:15px;
}

.form{
flex:1;
background:rgba(0,0,0,0.75);
padding:35px;
border-radius:16px;
}

.form h2{color:#ff7200;margin-bottom:15px;}

.form input,.form textarea{
width:100%;
padding:12px;
margin-top:12px;
border:none;
border-radius:8px;
background:rgba(255,255,255,0.1);
color:#fff;
}

.btn{
margin-top:15px;
padding:12px 25px;
background:#ff7200;
border:none;
color:white;
border-radius:8px;
cursor:pointer;
}

.btn:hover{background:#ff8c1a;}

.error{color:red;text-align:center;margin-top:10px;}
.success{color:lightgreen;text-align:center;margin-top:10px;}

.map{max-width:1100px;margin:30px auto;}
.map iframe{width:100%;height:250px;border-radius:10px;}

.faq{max-width:1100px;margin:40px auto;}
.faq h2{text-align:center;color:#ff7200;margin-bottom:20px;}

.faq-item{
background:rgba(0,0,0,0.6);
margin-bottom:10px;
border-radius:10px;
padding:15px;
cursor:pointer;
}

.faq-item p{
color:#ddd;
max-height:0;
overflow:hidden;
transition:0.3s;
}

/* FOOTER */
.footer{
background:#0f0f0f;
padding:30px 0;
margin-top:50px;
text-align:center;
}

.footer h2{color:#ff7200;}

.social-icons{
margin:15px 0;
display:flex;
justify-content:center;
gap:15px;
}

.social-icons a{
width:40px;height:40px;background:#222;
display:flex;align-items:center;justify-content:center;
border-radius:50%;color:white;
transition:0.3s;
}

.social-icons a:hover{
background:#ff7200;
transform:scale(1.2);
}

.copy{
font-size:13px;
color:#888;
margin-top:10px;
}

@media(max-width:768px){
.container{flex-direction:column;}
.navbar{padding:20px;}
}

</style>
</head>

<body>

<div class="navbar">
<div class="logo">CaRs</div>
<div class="menu">
<ul>
<li><a href="index.php">HOME</a></li>
<li><a href="about.php">ABOUT</a></li>
<li><a href="services.php">SERVICES</a></li>
<li><a href="contactus.php">CONTACT</a></li>
</ul>
</div>
</div>

<h1 class="title">Contact <span style="color:#ff7200;">Us</span></h1>

<div class="container">

<div>
<div class="box"><div class="icon">📍</div><p>Bijnor, Uttar Pradesh</p></div>
<div class="box"><div class="icon">📞</div><p>+91 6396200316</p></div>
<div class="box"><div class="icon">✉</div><p>contactcars@gmail.com</p></div>
</div>

<div class="form">
<h2>Send Message</h2>

<form method="POST">
<input type="text" name="name" placeholder="Full Name" required>
<input type="email" name="email" placeholder="Email" required>
<textarea rows="4" name="message" placeholder="Your Message" required></textarea>

<button class="btn" name="send">Send Message</button>
</form>

<?php if($error) echo "<div class='error'>$error</div>"; ?>
<?php if($success) echo "<div class='success'>$success</div>"; ?>

</div>

</div>

<div class="map">
<iframe src="https://www.google.com/maps?q=Bijnor,Uttar%20Pradesh&output=embed"></iframe>
</div>

<div class="faq">
<h2>Frequently Asked Questions</h2>

<div class="faq-item">
<h4>How can I rent a car?</h4>
<p>Simply register and choose your favorite car.</p>
</div>

<div class="faq-item">
<h4>What documents are required?</h4>
<p>Driving license and ID proof required.</p>
</div>

<div class="faq-item">
<h4>Can I cancel my booking?</h4>
<p>Yes, cancel anytime before pickup.</p>
</div>

</div>

<footer class="footer">
<h2>CaRs</h2>
<p>Drive your dreams with us 🚗</p>

<div class="social-icons">
<a href="#"><i class="fab fa-facebook-f"></i></a>
<a href="#"><i class="fab fa-instagram"></i></a>
<a href="#"><i class="fab fa-twitter"></i></a>
<a href="#"><i class="fab fa-linkedin-in"></i></a>
<a href="#"><i class="fab fa-youtube"></i></a>
</div>

<p class="copy">© 2026 CaRs | All Rights Reserved</p>
</footer>

<script>
document.querySelectorAll(".faq-item").forEach(item=>{
item.addEventListener("click",()=>{
let p=item.querySelector("p");
p.style.maxHeight = p.style.maxHeight ? null : p.scrollHeight + "px";
});
});
</script>

</body>
</html>