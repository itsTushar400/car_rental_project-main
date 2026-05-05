<?php
require_once('connection.php');

/* TYPE */
$type = $_GET['type'] ?? 'booking';

/* FILE NAME */
$filename = $type . "_report_" . date('Y-m-d') . ".csv";

/* HEADERS */
header("Content-Type: text/csv");
header("Content-Disposition: attachment; filename=$filename");

/* OUTPUT */
$output = fopen("php://output", "w");


/* ================= BOOKING EXPORT ================= */
if($type == "booking"){

    $headers = [
        'S.No','Car','Email','Place','Date','Days','Phone','License',
        'Destination','Driver Option','Driver Name','Driver Phone',
        'Return Date','Price','Status','Payment'
    ];
    fputcsv($output, $headers);

    $query = mysqli_query($con,"
    SELECT 
    b.*, 
    c.CAR_NAME,
    d.NAME AS DRIVER_NAME,
    d.PHONE AS DRIVER_PHONE
    FROM booking b
    LEFT JOIN cars c ON b.CAR_ID = c.CAR_ID
    LEFT JOIN drivers d ON b.DRIVER_ID = d.ID
    ORDER BY b.BOOK_ID DESC
    ");

    $i = 1;
    $total = 0;

    while($row=mysqli_fetch_assoc($query)){

        $license = $row['LICENSE_NO'] ?? '';
        $license = $license ? substr($license,0,4)."****".substr($license,-4) : "-";

        $price = (float)$row['PRICE'];
        $total += $price;

        $data = [
            $i++,
            $row['CAR_NAME'],
            $row['EMAIL'],
            $row['BOOK_PLACE'],
            $row['BOOK_DATE'],
            $row['DURATION'],
            $row['PHONE_NUMBER'],
            $license,
            $row['DESTINATION'],
            $row['DRIVER_OPTION'],
            $row['DRIVER_NAME'] ?? "-",
            $row['DRIVER_PHONE'] ?? "-",
            $row['RETURN_DATE'],
            $price,
            $row['BOOK_STATUS'],
            $row['PAYMENT']
        ];

        fputcsv($output, $data);
    }

    // TOTAL ROW
    fputcsv($output, ["","","","","","","","","","","","","TOTAL",$total]);
}


/* ================= TRANSACTIONS EXPORT ================= */
if($type == "transactions"){

    $headers = ['ID','User','Car','Amount','Method','Details','Status'];
    fputcsv($output, $headers);

    $q = mysqli_query($con,"
    SELECT 
    b.BOOK_ID,
    b.PRICE,
    c.CAR_NAME,
    u.FNAME,
    u.LNAME,
    p.payment_method,
    p.card_number,
    p.upi_id,
    p.refund_status
    FROM booking b
    JOIN cars c ON b.CAR_ID=c.CAR_ID
    JOIN users u ON b.EMAIL=u.EMAIL
    LEFT JOIN payment p ON b.BOOK_ID=p.BOOK_ID
    ORDER BY b.BOOK_ID DESC
    ");

    $total = 0;

    while($row = mysqli_fetch_assoc($q)){

        $details = "-";

        if($row['payment_method']=="Card"){
            $details = "XXXX " . substr($row['card_number'], -4);
        }
        elseif($row['payment_method']=="UPI"){
            $details = $row['upi_id'];
        }
        elseif($row['payment_method']=="QR"){
            $details = "QR";
        }

        $status = ($row['refund_status']=="Refunded") ? "Refunded" : "Paid";

        $amount = (float)$row['PRICE'];
        $total += $amount;

        $data = [
            $row['BOOK_ID'],
            $row['FNAME']." ".$row['LNAME'],
            $row['CAR_NAME'],
            $amount,
            $row['payment_method'],
            $details,
            $status
        ];

        fputcsv($output, $data);
    }

    // TOTAL ROW (Transactions)
    fputcsv($output, ["","","TOTAL",$total,"","",""]);
}

fclose($output);
exit;
?>