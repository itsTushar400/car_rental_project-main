<?php
require_once('connection.php');

$error = "";

if(isset($_POST['regs']))
{
    $fname = mysqli_real_escape_string($con,$_POST['fname']);
    $lname = mysqli_real_escape_string($con,$_POST['lname']);
    $email = mysqli_real_escape_string($con,$_POST['email']);
    $lic   = strtoupper(mysqli_real_escape_string($con,$_POST['lic']));
    $ph    = mysqli_real_escape_string($con,$_POST['ph']);
    $pass  = mysqli_real_escape_string($con,$_POST['pass']);
    $cpass = mysqli_real_escape_string($con,$_POST['cpass']);
    $gender= mysqli_real_escape_string($con,$_POST['gender']);

    // VALIDATION
    if(empty($fname)||empty($lname)||empty($email)||empty($lic)||empty($ph)||empty($pass)||empty($gender))
    {
        $error = "Please fill all fields";
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error = "Invalid Email";
    }
    else if(!preg_match("/^[A-Z]{2}[0-9]{2}[0-9]{4}[0-9]{7}$/", $lic))
    {
        $error = "Invalid License Number (Example: UP1420230012345)";
    }
    else if(!preg_match("/^[0-9]{10}$/", $ph))
    {
        $error = "Phone must be 10 digits";
    }
    else if($pass != $cpass)
    {
        $error = "Passwords do not match";
    }
    else
    {
        $check = "SELECT * FROM users WHERE EMAIL='$email'";
        $res = mysqli_query($con,$check);

        if(mysqli_num_rows($res)>0)
        {
            $error = "Email already exists";
        }
        else
        {
            // 🔒 SECURE PASSWORD
            $hashed = password_hash($pass, PASSWORD_DEFAULT);

            $sql="INSERT INTO users (FNAME,LNAME,EMAIL,LIC_NUM,PHONE_NUMBER,PASSWORD,GENDER)
                  VALUES('$fname','$lname','$email','$lic','$ph','$hashed','$gender')";

            if(mysqli_query($con,$sql))
            {
                echo "<script>alert('Registration Successful');window.location='index.php';</script>";
            }
            else{
                $error = "Database Error";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>

<title>Register</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<style>

/* RESET */
*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:Poppins;
}

/* BACKGROUND */
body{
height:100vh;
display:flex;
justify-content:center;
align-items:center;

background:
radial-gradient(circle at 20% 30%, rgba(255,114,0,0.15), transparent 40%),
radial-gradient(circle at 80% 70%, rgba(255,0,128,0.12), transparent 40%),
linear-gradient(135deg, #0f0f0f, #141414, #1c1c1c);

color:white;
}

/* HOME BUTTON */
.home{
position:absolute;
top:20px;
left:20px;
}

.home a{
background:#ff7200;
padding:10px 18px;
border-radius:6px;
text-decoration:none;
color:white;
transition:0.3s;
}

.home a:hover{
background:#ff8c1a;
}

/* FORM */
.form-box{
width:380px;
padding:30px;
background:#181818;
border-radius:14px;

box-shadow:
0 0 50px rgba(0,0,0,0.9),
0 0 15px rgba(255,114,0,0.2);
}

.form-box h2{
text-align:center;
margin-bottom:20px;
}

.form-box input{
width:100%;
padding:12px;
margin-top:12px;
border:none;
border-radius:8px;
background:#2a2a2a;
color:white;
outline:none;
}

.form-box input:focus{
border:1px solid #ff7200;
}

.radio{
margin-top:12px;
font-size:14px;
}

.radio label{
margin-right:15px;
}

.btn{
margin-top:15px;
width:100%;
padding:12px;
background:#ff7200;
border:none;
border-radius:8px;
color:white;
font-weight:600;
cursor:pointer;
transition:0.3s;
}

.btn:hover{
background:#ff8c1a;
}

.error{
color:#ff4d4d;
text-align:center;
margin-top:10px;
}

.link{
text-align:center;
margin-top:15px;
}

.link a{
color:#ff7200;
text-decoration:none;
}

</style>

</head>

<body>

<div class="home">
<a href="index.php">HOME</a>
</div>

<div class="form-box">

<h2>Register Here</h2>

<form method="POST">

<input type="text" name="fname" placeholder="First Name" required>
<input type="text" name="lname" placeholder="Last Name" required>
<input type="email" name="email" placeholder="Email" required>

<!-- LICENSE FIX -->
<input type="text" name="lic"
placeholder="License Number (UP1420230012345)"
maxlength="15"
oninput="this.value=this.value.toUpperCase()"
required>

<!-- PHONE FIX -->
<input type="text" name="ph"
placeholder="Phone Number"
pattern="[0-9]{10}"
maxlength="10"
required>

<input type="password" name="pass" placeholder="Password" required>
<input type="password" name="cpass" placeholder="Confirm Password" required>

<div class="radio">
Gender:
<label><input type="radio" name="gender" value="male" required> Male</label>
<label><input type="radio" name="gender" value="female"> Female</label>
</div>

<input type="submit" name="regs" value="Register" class="btn">

</form>

<?php if(!empty($error)) echo "<div class='error'>$error</div>"; ?>

<p class="link">
Already have an account?<br>
<a href="index.php">Login here</a>
</p>

</div>

</body>
</html>