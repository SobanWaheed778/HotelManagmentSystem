<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $new_status = $_POST['status'];

    // Update status in the database
    $updateQuery = "UPDATE visitor_logs SET status = '$new_status' WHERE id = '$id'";
    if (mysqli_query($conn, $updateQuery)) {
        echo "Status updated successfully!";
    } else {
        echo "Error updating status.";
    }
}
?>