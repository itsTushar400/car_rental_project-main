<?php
require_once('connection.php');

/* ================= VALIDATE ID ================= */
if(!isset($_GET['id']) || empty($_GET['id'])){
    die("Invalid Request");
}

$book_id = intval($_GET['id']); // SECURITY FIX

/* ================= GET BOOKING ================= */
$query = mysqli_query($con,"SELECT * FROM booking WHERE BOOK_ID='$book_id'");

if(!$query || mysqli_num_rows($query)==0){
    die("Booking not found");
}

$data = mysqli_fetch_assoc($query);

$car_id    = $data['CAR_ID'];
$driver_id = $data['DRIVER_ID'];
$status    = $data['BOOK_STATUS'];

/* ================= VALIDATIONS ================= */
if($status == "Returned"){
    echo "<script>
    alert('Car already returned!');
    window.location.href='adminbook.php';
    </script>";
    exit();
}

if($status != "Approved"){
    echo "<script>
    alert('Booking not approved yet!');
    window.location.href='adminbook.php';
    </script>";
    exit();
}

/* ================= START TRANSACTION ================= */
mysqli_begin_transaction($con);

try{

    /* 1. UPDATE BOOKING STATUS */
    $q1 = mysqli_query($con,"UPDATE booking 
    SET BOOK_STATUS='Returned' 
    WHERE BOOK_ID='$book_id'");

    if(!$q1) throw new Exception("Booking update failed");

    /* 2. MAKE CAR AVAILABLE */
    $q2 = mysqli_query($con,"UPDATE cars 
    SET AVAILABLE='Y' 
    WHERE CAR_ID='$car_id'");

    if(!$q2) throw new Exception("Car update failed");

    /* 3. FREE DRIVER */
    if(!empty($driver_id)){
        $q3 = mysqli_query($con,"UPDATE drivers 
        SET STATUS='Available' 
        WHERE ID='$driver_id'");

        if(!$q3) throw new Exception("Driver update failed");
    }

    /* COMMIT */
    mysqli_commit($con);

}catch(Exception $e){

    /* ROLLBACK */
    mysqli_rollback($con);

    die("Error: ".$e->getMessage());
}

/* ================= SUCCESS ================= */
echo "<script>
alert('Car Returned Successfully!');
window.location.href='adminbook.php';
</script>";

exit();
?>