<?php
include('includes/config.php');

if (isset($_POST['vhid']) && isset($_POST['fromdate']) && isset($_POST['todate'])) {
    $vhid = intval($_POST['vhid']);
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];

    if (strtotime($fromdate) > strtotime($todate)) {
        echo json_encode(['status' => 'error', 'message' => 'From Date cannot be after To Date.']);
        exit;
    }

    $sql = "SELECT * FROM tblbooking WHERE 
            (:fromdate BETWEEN date(FromDate) AND date(ToDate) 
            OR :todate BETWEEN date(FromDate) AND date(ToDate) 
            OR date(FromDate) BETWEEN :fromdate AND :todate) 
            AND VehicleId = :vhid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':vhid', $vhid, PDO::PARAM_STR);
    $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $query->bindParam(':todate', $todate, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0 && $query->fetch()['Status'] != 2) {
        echo json_encode(['status' => 'error', 'message' => 'These dates are already reserved.']);
    } else {
        echo json_encode(['status' => 'success', 'message' => 'Dates are available.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
