<?php 
include_once("../connection.php");

// Debugging ke liye errors enable karein
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_POST['visitor_logs_id']) && isset($_POST['visitor_email']) && isset($_POST['amount'])) {
    $visitor_logs_id = $_POST['visitor_logs_id'];
    $visitor_email = $_POST['visitor_email'];
    $amount = $_POST['amount'];

    // Amount should not be empty or zero
    if ($amount == "" || $amount <= 0) {
        echo "invalid_amount";
        exit;
    }

    // Generate a unique transaction ID
    $transaction_id = "TXN" . strtoupper(uniqid()); // Example: TXN65D3A9C1F2B

    // Insert into payments table with transaction_id
    $insert_payment_sql = "INSERT INTO payments (visitor_logs_id, amount, payment_status, payment_method, transaction_id) 
                           VALUES (?, ?, 'Paid', 'Handed Over', ?)";

    $stmt2 = $conn->prepare($insert_payment_sql);

    if (!$stmt2) {
        echo "prepare_failed: " . $conn->error; // Show error if prepare fails
        exit;
    }

    $stmt2->bind_param("sds", $visitor_logs_id, $amount, $transaction_id);

    if ($stmt2->execute()) {
        // Update visitor_logs to mark as paid
        $update_log_sql = "UPDATE visitor_logs SET payment_status = 'Paid' WHERE visitor_logs_id = ?";
        $stmt1 = $conn->prepare($update_log_sql);

        if (!$stmt1) {
            echo "prepare_log_failed: " . $conn->error; // Show error if prepare fails
            exit;
        }

        $stmt1->bind_param("i", $visitor_logs_id);
        $stmt1->execute();
        $stmt1->close();

        echo "success";
    } else {
        echo "error: " . $stmt2->error; // Print actual query error
    }
    $stmt2->close();
} else {
    echo "missing_data";
}
?>