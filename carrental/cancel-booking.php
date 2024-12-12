<?php
include('includes/config.php');
if (isset($_GET['bookingno'])) {
    $bookingno = $_GET['bookingno'];
    $sql = "UPDATE tblbooking SET Status=2 WHERE BookingNumber=:bookingno";
    $query = $dbh->prepare($sql);
    $query->bindParam(':bookingno', $bookingno, PDO::PARAM_STR);
    $query->execute();
    echo "<script>alert('Booking Successfully Cancelled');</script>";
    echo "<script type='text/javascript'> document.location = 'my-booking.php'; </script>";
} else {
    echo "<script>alert('Something went wrong. Please try again');</script>";
    echo "<script type='text/javascript'> document.location = 'my-booking.php'; </script>";
}
