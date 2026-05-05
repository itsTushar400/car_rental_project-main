<?php
$con = mysqli_connect('localhost','root','','carproject');
if(!$con) {
    die('Connection failed');
}

// Add DRIVER_OPTION column
mysqli_query($con, "ALTER TABLE booking ADD COLUMN DRIVER_OPTION VARCHAR(255) DEFAULT 'Self Drive'");

// Add DRIVER_ID column
mysqli_query($con, "ALTER TABLE booking ADD COLUMN DRIVER_ID INT(11) DEFAULT NULL");

// Check if drivers table exists, if not create it
$result = mysqli_query($con, "SHOW TABLES LIKE 'drivers'");
if(mysqli_num_rows($result) == 0) {
    mysqli_query($con, "CREATE TABLE drivers (
        ID INT(11) NOT NULL AUTO_INCREMENT,
        NAME VARCHAR(255) NOT NULL,
        PHONE BIGINT(20) NOT NULL,
        EMAIL VARCHAR(255) NOT NULL,
        PASSWORD VARCHAR(255) NOT NULL,
        STATUS VARCHAR(255) DEFAULT 'Available',
        PRIMARY KEY (ID)
    )");
}

echo "Database updated successfully";
?>