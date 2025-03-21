<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/header.php");
include 'connection.php';
require 'vendor/autoload.php';

// Check if user is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user details from database
$customer_id = mysqli_real_escape_string($conn, $_SESSION['customer_id']);
$query = "SELECT * FROM customers WHERE customer_id = '$customer_id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);
$user_email = $customer['email']; // Get the user's email

?>

<style>
    /* Background and glass effect */
    .home {
        position: relative;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .background_image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: brightness(0.7);
    }
    
    .profile-container {
        position: relative;
        width: 70%;
        background: rgba(255, 255, 255, 0.85);
        /* backdrop-filter: blur(15px); */
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        color: black;
        transition: 0.3s ease-in-out;
    }

    .profile-container:hover {
        transform: scale(1.02);
    }

    .profile-container h2 {
        margin-top: 15px;
        font-size: 24px;
        font-weight: bold;
    }

    .profile-info {
        margin-top: 15px;
        font-size: 16px;
    }

    .btn-custom {
        display: inline-block;
        padding: 5px 10px;
        margin-top: 10px;
        background-color: #FFA37B;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-custom:hover {
        background-color: #ff7b50;
    }

    .logout-btn {
        background-color: #ff4d4d;
    }

    .logout-btn:hover {
        background-color: #d63333;
    }

    /* Booking details section */
    .booking-details {
        margin-top: 20px;
        background: rgba(255, 255, 255, 0.15);
        padding: 15px;
        border-radius: 10px;
        font-size: 14px;
        text-align: left;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    th {
        background-color: #f2f2f2;
    }
    select {
        padding: 5px;
        width: 100%;
    }
</style>

<div class="home">
    <div class="background_image" style="background-image:url(images/booking.jpg)"></div>
    <div class="profile-container">
        <h2>Welcome, <?php echo $customer['name']; ?> 👋</h2>
        <p class="profile-info">Email: <?php echo $customer['email']; ?></p>

        <!-- Instruction Alert Box -->
<div id="instruction-alert" class="alert alert-info alert-dismissible fade show" role="alert">
    <strong>Important Instructions:</strong>
    <ul>
        <li>Payment option will appear only after your booking is approved.</li>
        <li>You can cancel the booking before approval.</li>
        <li>Check-in and check-out dates cannot be changed once confirmed.</li>
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close" onclick="dismissAlert()">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<script>
    // Check if user has already dismissed the alert
    if (sessionStorage.getItem("dismissedAlert")) {
        document.getElementById("instruction-alert").style.display = "none";
    }

    function dismissAlert() {
        document.getElementById("instruction-alert").style.display = "none";
        sessionStorage.setItem("dismissedAlert", "true");
    }
</script>

<!-- Bootstrap CSS (If not already included) -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <div class="booking-details">
            <h4>Your Booking Details:</h4>
            <?php
            $booking_query = "SELECT v.visitor_logs_id, v.check_in, v.check_out, v.status, 
            r.category_name, r.price, 
            COALESCE(p.payment_status, 'Unpaid') AS payment_status
            FROM visitor_logs v 
            JOIN room_categories r ON v.room_category_id = r.room_category_id 
            LEFT JOIN (
                SELECT visitor_logs_id, MAX(payment_status) AS payment_status 
                FROM payments 
                GROUP BY visitor_logs_id
            ) p ON v.visitor_logs_id = p.visitor_logs_id  
            WHERE v.customer_id = '$customer_id' 
            ORDER BY v.check_in DESC";
     
            
            $booking_result = mysqli_query($conn, $booking_query);
            
            if (mysqli_num_rows($booking_result) > 0) {
                echo "<table><tr><th>Room</th><th>Check-In</th><th>Check-Out</th><th>Status</th><th>Price</th><th>Payment</th><th>Actions</th></tr>";
                while ($row = mysqli_fetch_assoc($booking_result)) {
                    $payment_status = $row['payment_status'];
                    $room_price = isset($row['price']) ? (float) $row['price'] : 0;

            
                    echo "<tr>
                            <td>" . $row['category_name'] . "</td>
                            <td>" . $row['check_in'] . "</td>
                            <td>" . $row['check_out'] . "</td>
                            <td id='status-" . $row['visitor_logs_id'] . "'>" . $row['status'] . "</td>
                            <td>$" . number_format($room_price, 2) . "</td>
                            <td>" . $payment_status . "</td>
                            <td>";
            
                    $buttons = "";
            
                    if ($row['status'] == "Pending" || $row['status'] == "Confirmed") {
                        $buttons .= "<button class='btn-custom btn-checkout' onclick='updateStatus(" . $row['visitor_logs_id'] . ", \"Checked-Out\")'>Check-Out</button> ";
                        $buttons .= "<button class='btn-custom btn-cancel' onclick='updateStatus(" . $row['visitor_logs_id'] . ", \"Cancelled\")'>Cancel Booking</button> ";
                    }
            
                    if ($row['status'] == "Confirmed" && $payment_status == "Unpaid") {
                        $buttons .= "<button class='btn-custom ml-1' onclick='showPaymentOptions(" . $row['visitor_logs_id'] . ", " . (float)$row['price'] . ")'>Pay Charges</button>";
                    }
            
                    echo !empty($buttons) ? $buttons : "<span class='btn-custom btn-disabled'>No Action</span>";
            
                    echo "</td></tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No bookings found.</p>";
            }
            


            ?>
        </div>

        <a href="update_profile.php" class="btn-custom">Update Profile</a>
        <a href="logout.php" class="btn-custom logout-btn">Logout</a>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://js.stripe.com/v3/"></script>

<script>
    function updateStatus(bookingId, newStatus) {
        if (confirm("Are you sure you want to " + newStatus + " this booking?")) {
            fetch('update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'visitor_logs_id=' + bookingId + '&status=' + newStatus
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('status-' + bookingId).innerText = newStatus;
                document.getElementById('status-' + bookingId).nextElementSibling.innerHTML = '<span class="btn-custom btn-disabled">No Action</span>';
            });
        }
    }



    function showPaymentOptions(visitorLogsId, price) {
    console.log("Visitor Logs ID:", visitorLogsId);
    console.log("Price:", price);

    Swal.fire({
        title: "Payment Options",
        text: "Choose how you want to pay for this booking:",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Pay Now",
        cancelButtonText: "Pay Later"
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: "Enter Card Details",
                html: `
                    <div id="payment-form">
                        <div id="card-element" style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;"></div>
                        <button id="submit-payment" class="btn-custom" style="margin-top: 10px;">Pay Now</button>
                        <p id="payment-message" style="color: red; margin-top: 10px;"></p>
                    </div>
                `,
                showCancelButton: true,
                showConfirmButton: false,
                didOpen: async () => {
                    let stripe = Stripe("pk_test_51QevSDC72xlZ1IDdr4ILhTVMFmGEWYBT8HAPh0n7rMOgPWStd9fhi48H6qB2qWkAXx6knjheS4YhIx6c611uunbF006NlSoe3Y");
                    let elements = stripe.elements();
                    let card = elements.create("card");
                    card.mount("#card-element");

                    document.getElementById("submit-payment").addEventListener("click", async () => {
                        let paymentMessage = document.getElementById("payment-message");
                        paymentMessage.textContent = "Processing...";

                        try {
                            console.log("Fetching client secret from server...");

                            let response = await fetch("stripe_checkout.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/json" },
                                body: JSON.stringify({ visitor_logs_id: visitorLogsId, price: price })
                            });

                            console.log("Server response:", response);

                            if (!response.ok) {
                                throw new Error(`Server returned ${response.status}: ${response.statusText}`);
                            }

                            let data = await response.json();
                            console.log("Data from server:", data);

                            if (data.error) {
                                throw new Error(data.error);
                            }

                            console.log("Confirming payment with Stripe...");

                            let { paymentIntent, error } = await stripe.confirmCardPayment(data.clientSecret, {
                                payment_method: { card: card }
                            });

                            if (error) {
                                console.error("Stripe Error:", error);
                                throw new Error(error.message);
                            }

                            console.log("PaymentIntent status:", paymentIntent.status);

                            if (paymentIntent.status === "succeeded") {
                                Swal.fire("Success!", "Payment Successful!", "success").then(() => {
                                    window.location.href = "payment_success.php?visitor_logs_id=" + encodeURIComponent(visitorLogsId) + "&amount=" + price;
                                });
                            }
                        } catch (error) {
                            console.error("Error during payment process:", error);
                            paymentMessage.textContent = "An error occurred. Please try again.";
                        }
                    });
                }
            });
        } else {
            fetch("pay_later.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "visitor_logs_id=" + visitorLogsId
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === "success") {
                    Swal.fire("Admin Notified", "Admin has been informed about your choice.", "success");
                } else {
                    Swal.fire("Error", "Could not notify admin.", "error");
                }
            });
        }
    });
}



</script>