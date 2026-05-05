<?php
require_once('connection.php');

/* ================= VALIDATE ID ================= */
if(!isset($_GET['id']) || empty($_GET['id'])){
    die("Invalid Request");
}

$id = intval($_GET['id']); // SECURITY FIX

/* ================= GET BOOKING ================= */
$booking = mysqli_query($con,"SELECT * FROM booking WHERE BOOK_ID='$id'");
$row = mysqli_fetch_assoc($booking);

if(!$row){
    die("Booking Not Found");
}

/* ================= PAYMENT CHECK (IMPORTANT) ================= */
if($row['PAYMENT'] != 'Paid'){
    echo "<script>
    alert('Payment not completed!');
    window.location='adminbook.php';
    </script>";
    exit();
}

/* ================= ALREADY APPROVED CHECK ================= */
if($row['BOOK_STATUS'] == 'Approved'){
    header("Location: adminbook.php");
    exit();
}

/* ================= DRIVER LOGIC ================= */
if($row['DRIVER_OPTION'] == "Driver Required"){

    // CHECK IF DRIVER ALREADY ASSIGNED
    if(!empty($row['DRIVER_ID'])){
        mysqli_query($con,"UPDATE booking 
        SET BOOK_STATUS='Approved'
        WHERE BOOK_ID='$id'");
    }else{

        // FIND AVAILABLE DRIVER
        $driver = mysqli_query($con,"SELECT * FROM drivers WHERE STATUS='Available' LIMIT 1");

        if(mysqli_num_rows($driver) > 0){

            $driver_data = mysqli_fetch_assoc($driver);
            $driver_id = $driver_data['ID'];

            // ASSIGN DRIVER + APPROVE
            mysqli_query($con,"UPDATE booking 
            SET BOOK_STATUS='Approved', DRIVER_ID='$driver_id'
            WHERE BOOK_ID='$id'");

            // UPDATE DRIVER STATUS
            mysqli_query($con,"UPDATE drivers 
            SET STATUS='Booked'
            WHERE ID='$driver_id'");

        }else{

            echo "<script>
            alert('No Driver Available!');
            window.location='adminbook.php';
            </script>";
            exit();
        }
    }

}else{

    /* ================= SELF DRIVE ================= */
    mysqli_query($con,"UPDATE booking 
    SET BOOK_STATUS='Approved'
    WHERE BOOK_ID='$id'");
}

/* ================= REDIRECT ================= */
header("Location: adminbook.php");
exit();
?>