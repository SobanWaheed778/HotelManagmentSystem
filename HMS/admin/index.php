<?php include("admin_header.php"); ?>
<?php include("admin_sidebar.php"); ?>
<?php include_once("../connection.php"); ?>

<div class="container-xxl position-relative bg-white d-flex p-0">
    <div class="content">
        <?php include("admin_navigation.php"); ?>

        <div class="container-fluid pt-4 px-4">
            <div class="row g-4">
                <?php
                // Fetch statistics from the database
                $total_customers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM customers"))['count'];
                $guests_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM visitor_logs WHERE DATE(check_in) = CURDATE()"))['count'];
                $room_categories = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM room_categories"))['count'];
                $total_rooms = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(rooms_count) AS count FROM room_categories"))['count'];
                $total_earnings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status='Paid'"))['total'];
                $today_earnings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status='Paid' AND DATE(created_at) = CURDATE()"))['total'];
                $weekly_earnings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status='Paid' AND YEARWEEK(created_at, 1) = YEARWEEK(CURDATE(), 1)"))['total'];
                $annual_earnings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status='Paid' AND YEAR(created_at) = YEAR(CURDATE())"))['total'];
                $total_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM visitor_logs"))['count'];
                $pending_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM visitor_logs WHERE status='Pending'"))['count'];
                $checked_out_today = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM visitor_logs WHERE status='Checked-Out' AND DATE(check_out) = CURDATE()"))['count'];
                $cancelled_bookings = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM visitor_logs WHERE status='Cancelled'"))['count'];
                $unpaid_payments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM payments WHERE payment_status='Unpaid'"))['total'];
                $most_booked_room = mysqli_fetch_assoc(mysqli_query($conn, "SELECT category_name FROM room_categories WHERE room_category_id = (SELECT room_category_id FROM visitor_logs GROUP BY room_category_id ORDER BY COUNT(*) DESC LIMIT 1)"))['category_name'];
                $active_offers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM special_offers"))['count'];
                $unread_notifications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM notifications WHERE status='unread'"))['count'];
                ?>

                <?php
                $stats = [
                    ["Total Customers", $total_customers, "fa-user"],
                    ["Guests Today", $guests_today, "fa-users"],
                    ["Room Categories", $room_categories, "fa-th-large"],
                    ["Total Rooms", $total_rooms, "fa-hotel"],
                    ["Total Earnings", "$" . number_format($total_earnings, 2), "fa-dollar-sign"],
                    ["Today's Earnings", "$" . number_format($today_earnings, 2), "fa-dollar-sign"],
                    ["Weekly Earnings", "$" . number_format($weekly_earnings, 2), "fa-dollar-sign"],
                    ["Annual Earnings", "$" . number_format($annual_earnings, 2), "fa-dollar-sign"],
                    ["Total Bookings", $total_bookings, "fa-calendar-alt"],
                    ["Pending Bookings", $pending_bookings, "fa-clock"],
                    ["Checked-Out Today", $checked_out_today, "fa-check"],
                    ["Cancelled Bookings", $cancelled_bookings, "fa-times"],
                    ["Unpaid Payments", "$" . number_format($unpaid_payments, 2), "fa-exclamation-circle"],
                    ["Most Booked Room", $most_booked_room, "fa-bed"],
                    ["Active Special Offers", $active_offers, "fa-tags"],
                    ["Unread Notifications", $unread_notifications, "fa-bell"]
                ];
                ?>

                <?php foreach ($stats as $stat) : ?>
                    <div class="col-sm-6 col-xl-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-between p-4">
                            <i class="fa <?= $stat[2] ?> fa-3x text-primary"></i>
                            <div class="ms-3">
                                <p class="mb-2"> <?= $stat[0] ?> </p>
                                <h6 class="mb-0"> <?= $stat[1] ?> </h6>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php include("admin_footer.php"); ?>
    </div>
</div>