<?php
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    // Check if the record exists
    $check_query = "SELECT * FROM payments WHERE visitor_email = '$email'";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) > 0) {
        // If record exists, update the payment_status
        $update_query = "UPDATE payments SET payment_status = 'Unpaid' WHERE visitor_email = '$email'";
        if (mysqli_query($conn, $update_query)) {
            echo "Payment status updated to Unpaid";
        } else {
            echo "Error updating payment: " . mysqli_error($conn);
        }
    } else {
        echo "No existing payment record found for this email.";
    }
}
?>
