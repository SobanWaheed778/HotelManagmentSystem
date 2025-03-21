<?php
include_once("../connection.php");

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); // Ensure it's an integer

    $delete_query = "DELETE FROM notifications WHERE notification_id = $id";
    if ($conn->query($delete_query) === TRUE) {
        echo "success";
    } else {
        echo "error: " . $conn->error;
    }
} else {
    echo "error: ID not received";
}
?>