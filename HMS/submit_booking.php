<?php
include 'connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $customer_id = $_SESSION['customer_id'];
    $visitor_name = $_POST['visitor_name'];
    $contact_number = $_POST['contact_number'];
    $visitor_email = $_POST['visitor_email'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_persons = $_POST['total_persons'];
    $purpose_of_visit = $_POST['purpose_of_visit'];
    $room_category_id = $_POST['room_category_id'];

    // Check room availability again before booking
    $query = "SELECT rooms_count FROM room_categories WHERE room_category_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $room_category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if ($room && $room['rooms_count'] > 0) {
        // ✅ Insert booking into visitor_logs with customer_id
        $insert_query = "INSERT INTO visitor_logs (customer_id, visitor_name, contact_number, visitor_email, check_in, check_out, total_persons, purpose_of_visit, created_at, room_category_id)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
        $insert_stmt = $conn->prepare($insert_query);
        $insert_stmt->bind_param("isssssssi", $customer_id, $visitor_name, $contact_number, $visitor_email, $check_in, $check_out, $total_persons, $purpose_of_visit, $room_category_id);

        if ($insert_stmt->execute()) {
            // Update room availability
            $update_query = "UPDATE room_categories SET rooms_count = rooms_count - 1 WHERE room_category_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $room_category_id);
            $update_stmt->execute();

            echo json_encode(["status" => "success", "message" => "Booking confirmed!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Booking failed!"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No rooms available!"]);
    }
}
?>
