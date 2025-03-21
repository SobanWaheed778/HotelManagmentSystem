<?php include("includes/header.php"); ?>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'connection.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}
?>

<div class="home">
    <div class="background_image" style="background-image:url(images/booking.jpg)"></div>
    <div class="home_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="home_content text-center">
                        <div class="home_title">Book Now!</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card p-4 shadow">
                <h4 class="text-center mb-4">Book Your Stay</h4>
                <form id="bookingForm">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-control" name="visitor_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contact Number</label>
                        <input type="text" class="form-control" name="contact_number" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="visitor_email">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Room Category</label>
                        <select class="form-control" name="room_category_id" required>
                            <option value="">Select Room</option>
                            <?php
                            include 'connection.php';
                            $query = "SELECT room_category_id, category_name FROM room_categories"; // FIXED ID USAGE
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='{$row['room_category_id']}'>{$row['category_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-in Date</label>
                        <input type="date" class="form-control" name="check_in" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-out Date</label>
                        <input type="date" class="form-control" name="check_out" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Persons</label>
                        <input type="number" class="form-control" name="total_persons" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Purpose of Visit</label>
                        <input type="text" class="form-control" name="purpose_of_visit">
                    </div>
                    <button type="button" id="checkAvailability" style="background-color:#FFA37B" class="btn btn-primary w-100 py-3">BOOK NOW!</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById("checkAvailability").addEventListener("click", function() {
    let formData = new FormData(document.getElementById("bookingForm"));

    fetch("check_availability.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            Swal.fire({
                icon: "success",
                title: "Available!",
                text: data.message,
                confirmButtonText: "Proceed to Booking"
            }).then(() => {
                submitBooking(formData);
            });
        } else {
            Swal.fire({
                icon: "error",
                title: "Not Available",
                text: data.message,
                confirmButtonText: "Try Another Date"
            });
        }
    });
});

function submitBooking(formData) {
    fetch("submit_booking.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.fire({
            icon: data.status,
            title: data.status === "success" ? "Booked!" : "Error",
            text: data.message,
            confirmButtonText: "OK"
        }).then(() => {
            if (data.status === "success") {
                window.location.href = "booking.php";
            }
        });
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            icon: "error",
            title: "Oops!",
            text: "Something went wrong. Please try again.",
        });
    });
}
</script>

<?php include("includes/footer.php"); ?>
