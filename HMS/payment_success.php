<?php
include 'connection.php';

// Check if visitor_logs_id and amount are provided
if (!isset($_GET['visitor_logs_id']) || empty($_GET['visitor_logs_id']) || !isset($_GET['amount']) || empty($_GET['amount'])) {
    die("Invalid request: Booking ID or amount not provided.");
}

$visitor_logs_id = mysqli_real_escape_string($conn, $_GET['visitor_logs_id']);
$amount = mysqli_real_escape_string($conn, $_GET['amount']); 

// Generate a unique transaction ID
$transaction_id = "TXN" . strtoupper(uniqid()); // Example: TXN65D3A9C1F2B

// Insert payment record into payments table
$query1 = "INSERT INTO payments (visitor_logs_id, amount, payment_status, payment_method, transaction_id) 
           VALUES ('$visitor_logs_id', '$amount', 'Paid', 'Stripe', '$transaction_id')";

// Update booking status in visitor_logs
$query2 = "UPDATE visitor_logs 
           SET payment_status = 'Paid' 
           WHERE visitor_logs_id = '$visitor_logs_id'";

if (mysqli_query($conn, $query1) && mysqli_query($conn, $query2)) {
    echo "<script>
            alert('Payment Successful! Your Transaction ID: $transaction_id.\\nPlease take a screenshot for future reference.');
            window.location.href = 'user_profile.php'; 
          </script>";
} else {
    echo "Error processing payment: " . mysqli_error($conn);
}
?>