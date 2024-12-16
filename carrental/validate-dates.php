<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fromdate = $_POST['fromdate'];
    $todate = $_POST['todate'];
    $vhid = intval($_POST['vhid']);

    // Validate date range
    $sql = "SELECT * FROM tblbooking WHERE 
          (:fromdate BETWEEN FromDate AND ToDate 
          OR :todate BETWEEN FromDate AND ToDate 
          OR FromDate BETWEEN :fromdate AND :todate) 
          AND VehicleId = :vhid";
    $query = $dbh->prepare($sql);
    $query->bindParam(':vhid', $vhid, PDO::PARAM_INT);
    $query->bindParam(':fromdate', $fromdate, PDO::PARAM_STR);
    $query->bindParam(':todate', $todate, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() > 0) {
        echo json_encode(['status' => 'error', 'message' => 'The selected dates are not available.']);
        exit;
    }

    // Calculate total days
    $from = new DateTime($fromdate);
    $to = new DateTime($todate);
    $interval = $from->diff($to);
    $days = $interval->days + 1; // Include both start and end dates

    echo json_encode(['status' => 'success', 'days' => $days]);
}
