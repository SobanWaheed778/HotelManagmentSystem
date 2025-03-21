<?php
include 'connection.php';
session_start();

if (isset($_SESSION['customer_id'])) {
    $user_id = $_SESSION['customer_id'];
    $message = "User ID $user_id has chosen to pay later for their booking.";

    $query = "INSERT INTO notifications (user_id, message) VALUES ('$user_id', '$message')";
    
    if (mysqli_query($conn, $query)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conn)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "User not logged in"]);
}
?>
