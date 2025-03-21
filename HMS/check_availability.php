<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];
    $total_persons = (int) $_POST['total_persons'];
    $room_category_id = isset($_POST['room_category_id']) ? (int) $_POST['room_category_id'] : 0;

    // Validate room category ID
    if ($room_category_id <= 0) {
        echo json_encode(["status" => "error", "message" => "Please select a valid room category."]);
        exit();
    }

    // Validate date format
    if (empty($check_in) || empty($check_out)) {
        echo json_encode(["status" => "error", "message" => "Check-in and Check-out dates are required."]);
        exit();
    }

    // Convert to Y-m-d format
    $check_in = date('Y-m-d', strtotime($check_in));
    $check_out = date('Y-m-d', strtotime($check_out));

    // Step 1: Get total rooms for the selected category
    $category_query = "SELECT rooms_count FROM room_categories WHERE room_category_id = ?";
    $stmt1 = $conn->prepare($category_query);
    $stmt1->bind_param("i", $room_category_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();
    $category = $result1->fetch_assoc();

    if (!$category) {
        echo json_encode(["status" => "error", "message" => "Invalid room category."]);
        exit();
    }

    $total_rooms = $category['rooms_count'];

    // Step 2: Count already booked rooms for this category
    $query = "SELECT COUNT(*) AS occupied_rooms FROM visitor_logs 
              WHERE room_category_id = ? 
              AND NOT (check_out <= ? OR check_in >= ?)";

    $stmt2 = $conn->prepare($query);
    $stmt2->bind_param("iss", $room_category_id, $check_out, $check_in);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $row = $result2->fetch_assoc();
    $occupied_rooms = $row['occupied_rooms'];

    // Step 3: Check availability
    if ($occupied_rooms < $total_rooms) {
        echo json_encode(["status" => "success", "message" => "Rooms are available. 😊"]);
    } else {
        echo json_encode(["status" => "error", "message" => "No rooms available for the selected dates. 😔"]);
    }
}
?>
