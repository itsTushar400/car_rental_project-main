<?php
require_once('connection.php');

$id = $_GET['id'];

$res = mysqli_query($con,"SELECT * FROM drivers WHERE ID='$id'");
$d = mysqli_fetch_assoc($res);

if(isset($_POST['update'])){

$name = $_POST['name'];
$phone = $_POST['phone'];
$image = $d['IMAGE'];

/* IMAGE UPDATE */
if(!empty($_FILES['image']['name'])){

    // delete old image
    if(!empty($image) && file_exists("images/".$image)){
        unlink("images/".$image);
    }

    $newimg = $_FILES['image']['name'];
    $tmp = $_FILES['image']['tmp_name'];

    move_uploaded_file($tmp,"images/".$newimg);

    $image = $newimg;
}

/* UPDATE QUERY */
mysqli_query($con,"UPDATE drivers SET 
NAME='$name',
PHONE='$phone',
IMAGE='$image'
WHERE ID='$id'");

header("Location: drivers.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Driver</title>

<style>
body{font-family:Arial;background:#f5f5f5;}

.box{
width:380px;
margin:50px auto;
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.2);
}

input{
width:100%;
padding:10px;
margin:10px 0;
border-radius:6px;
border:1px solid #ccc;
}

button{
width:100%;
padding:10px;
background:orange;
border:none;
color:white;
border-radius:6px;
cursor:pointer;
}

/* IMAGE */

.preview{
width:100%;
height:150px;
border-radius:10px;
overflow:hidden;
margin-bottom:10px;
}

.preview img{
width:100%;
height:100%;
object-fit:cover;
}
</style>

</head>

<body>

<div class="box">

<h2>Edit Driver</h2>

<!-- CURRENT IMAGE -->
<div class="preview">
<?php if(!empty($d['IMAGE'])){ ?>
<img id="imgPreview" src="images/<?php echo $d['IMAGE']; ?>">
<?php } else { ?>
<img id="imgPreview" src="images/default.png">
<?php } ?>
</div>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" value="<?php echo $d['NAME']; ?>" required>

<input type="text" name="phone" value="<?php echo $d['PHONE']; ?>" required>

<label>Change Image</label>
<input type="file" name="image" accept="image/*" onchange="previewImage(event)">

<button name="update">Update</button>

</form>

</div>

<script>
function previewImage(event){
const file = event.target.files[0];
if(file){
document.getElementById("imgPreview").src = URL.createObjectURL(file);
}
}
</script>

</body>
</html>