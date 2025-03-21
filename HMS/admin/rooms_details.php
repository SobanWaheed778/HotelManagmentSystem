<?php 
ob_start();
include("admin_header.php");
include_once("../connection.php");
?>

<div class="container-xxl position-relative bg-white d-flex p-0">
    <!-- Sidebar Start -->
    <?php include("admin_sidebar.php"); ?>
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        <?php include("admin_navigation.php"); ?>
        <!-- Navbar End -->

        <div class="container">
            <h2 class="mb-4">Room Details</h2>

            <!-- Room Details Table -->
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Room Name</th>
                            <th>Guest Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Payment Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Room Categories Fetching
                        $query = "SELECT * FROM room_categories ORDER BY category_name";
                        $result = $conn->query($query);
                        $count = 1;

                        while ($category = $result->fetch_assoc()) {
                            $room_category_id = $category['room_category_id'];
                            $category_name = $category['category_name'];

                            // Rooms from Categories
                            $stored_rooms = $category['rooms_count'];

                            // Count Booked Rooms
                            $booked_rooms_query = "SELECT COUNT(*) as booked_count FROM visitor_logs WHERE room_category_id = $room_category_id";
                            $booked_rooms_result = $conn->query($booked_rooms_query);
                            $booked_rooms = $booked_rooms_result->fetch_assoc()['booked_count'];

                            // Total Rooms = Registered + Booked
                            $total_rooms = $stored_rooms + $booked_rooms;

                            // Fetch Booked Rooms Data
                            $bookings_query = "SELECT vl.visitor_name, vl.visitor_email, vl.contact_number, vl.check_in, vl.check_out, 
                                                       p.payment_status
                                               FROM visitor_logs vl
                                               LEFT JOIN payments p ON vl.visitor_logs_id = p.visitor_logs_id
                                               WHERE vl.room_category_id = $room_category_id
                                               ORDER BY vl.check_in DESC";
                            $bookings_result = $conn->query($bookings_query);

                            // Show Booked Rooms First
                            while ($booking = $bookings_result->fetch_assoc()) {
                                $room_number = "{$category_name} {$count}"; // Generate Room Number
                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$room_number}</td>
                                        <td>{$booking['visitor_name']}</td>
                                        <td>{$booking['visitor_email']}</td>
                                        <td>{$booking['contact_number']}</td>
                                        <td>{$booking['check_in']}</td>
                                        <td>{$booking['check_out']}</td>
                                        <td>" . (!empty($booking['payment_status']) ? $booking['payment_status'] : '<p class="text-danger">Unpaid</p>') . "</td>
                                      </tr>";
                                $count++;
                            }

                            // Available Rooms Calculation
                            $available_rooms = $total_rooms - $booked_rooms;

                            // Show Available Rooms
                            for ($i = 1; $i <= $available_rooms; $i++) {
                                $room_number = "{$category_name} {$count}"; // Generate Room Number
                                echo "<tr style='background-color: #ffff99;'>
                                        <td>{$count}</td>
                                        <td>{$room_number}</td>
                                        <td colspan='6' class='text-center'><strong>Available</strong></td>
                                      </tr>";
                                $count++;
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>

<?php ob_end_flush(); ?>
