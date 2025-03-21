<?php include("admin_header.php"); ?>
<?php include_once("../connection.php"); ?>

<div class="container-xxl position-relative bg-white d-flex p-0">
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Spinner End -->

    <!-- Sidebar Start -->
    <?php include("admin_sidebar.php"); ?>
    <!-- Sidebar End -->

    <!-- Content Start -->
    <div class="content">
        <!-- Navbar Start -->
        <?php include("admin_navigation.php"); ?>
        <!-- Navbar End -->

        <!-- Visitor Logs Start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Visitor Logs</h6>
                    <a href="">Show All</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                        <thead>
                            <tr class="text-dark">
                                <th scope="col">#</th>
                                <th scope="col">Visitor Name</th>
                                <th scope="col">Visitor Email</th>
                                <th scope="col">Check-In</th>
                                <th scope="col">Check-Out</th>
                                <th scope="col">Total Persons</th>
                                <th scope="col">Booked Room</th>
                                <th scope="col">Purpose of Visit</th>
                                <th scope="col">Booking Status</th>
                                <th scope="col">Payment Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT v.*, r.category_name 
                                    FROM visitor_logs v 
                                    JOIN room_categories r ON v.room_category_id = r.room_category_id 
                                    ORDER BY v.created_at DESC";

                            $result = $conn->query($sql);
                            if ($result->num_rows > 0) {
                                $count = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $count++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['visitor_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['visitor_email']) . "</td>";
                                    echo "<td>" . htmlspecialchars(date('d-M-Y H:i', strtotime($row['check_in']))) . "</td>";
                                    echo "<td>" . ($row['check_out'] ? htmlspecialchars(date('d-M-Y H:i', strtotime($row['check_out']))) : "N/A") . "</td>";
                                    echo "<td>" . htmlspecialchars($row['total_persons']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['purpose_of_visit']) . "</td>";
                                    echo "<td>
                                    <select class='form-select status-change' data-id='" . $row['visitor_logs_id'] . "' " . 
                                    (($row['status'] == 'Checked-Out' || $row['status'] == 'Cancelled') ? 'disabled' : '') . ">
                                        <option value='Pending' " . ($row['status'] == 'Pending' ? 'selected' : '') . ">Pending</option>
                                        <option value='Confirmed' " . ($row['status'] == 'Confirmed' ? 'selected' : '') . ">Confirmed</option>
                                        <option value='Checked-Out' " . ($row['status'] == 'Checked-Out' ? 'selected' : '') . ">Checked-Out</option>
                                        <option value='Cancelled' " . ($row['status'] == 'Cancelled' ? 'selected' : '') . ">Cancelled</option>
                                    </select>
                                  </td>";

                                  echo "<td>";
                                if ($row['payment_status'] == 'Paid') {
                                    echo "<span class='text-success fw-bold'>Paid</span> 
                                          <i class='fas fa-check-circle text-success ms-2'></i>";
                                } else {
                                    echo "<span class='text-danger fw-bold'>Unpaid</span> 
                                          <i class='fas fa-exchange-alt text-warning ms-2 change-payment-status' 
                                             data-id='" . $row['visitor_logs_id'] . "' 
                                             data-email='" . $row['visitor_email'] . "' 
                                             style='cursor:pointer;'></i>";
                                }
                                echo "</td>";


                            
                                    echo "<td><a href='#' class='btn btn-danger btn-sm delete-btn' data-id='" . $row['visitor_logs_id'] . "'>Delete</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='9' class='text-center'>No records found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Visitor Logs End -->

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>

<!-- SweetAlert for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        
        const visitorId = this.getAttribute('data-id');
        
        Swal.fire({
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'visitors_logs.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        button.closest('tr').remove();
                        Swal.fire('Deleted!', 'The record has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'There was an error deleting the record.', 'error');
                    }
                };
                xhr.send('delete_id=' + visitorId);
            }
        });
    });
});

// Status Update
document.querySelectorAll('.status-change').forEach(select => {
    select.addEventListener('change', function() {
        const visitorId = this.getAttribute('data-id');
        const newStatus = this.value;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'visitors_logs.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status == 200) {
                Swal.fire('Updated!', 'Status has been updated.', 'success');
            } else {
                Swal.fire('Error!', 'There was an error updating the status.', 'error');
            }
        };
        xhr.send('update_status_id=' + visitorId + '&new_status=' + newStatus);
    });
});
</script>

<?php
if (isset($_POST['update_status_id']) && isset($_POST['new_status'])) {
    $update_status_id = $_POST['update_status_id'];
    $new_status = $_POST['new_status'];

    // Get room_category_id and check if already adjusted
    $getRoomQuery = "SELECT room_category_id, room_adjusted FROM visitor_logs WHERE visitor_logs_id = ?";
    if ($stmt = $conn->prepare($getRoomQuery)) {
        $stmt->bind_param("i", $update_status_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $room_category_id = $row['room_category_id'];
            $room_adjusted = $row['room_adjusted']; // Check if already incremented
        }
        $stmt->close();
    }

    // Update status
    $updateStatusQuery = "UPDATE visitor_logs SET status = ? WHERE visitor_logs_id = ?";
    if ($stmt = $conn->prepare($updateStatusQuery)) {
        $stmt->bind_param("si", $new_status, $update_status_id);
        if ($stmt->execute()) {
            // Only increase room count if not already adjusted
            if (($new_status == 'Checked-Out' || $new_status == 'Cancelled') && $room_adjusted == 0) {
                $updateRoomCount = "UPDATE room_categories SET rooms_count = rooms_count + 1 WHERE room_category_id = ?";
                if ($stmt2 = $conn->prepare($updateRoomCount)) {
                    $stmt2->bind_param("i", $room_category_id);
                    $stmt2->execute();
                    $stmt2->close();
                }

                // Mark as adjusted
                $markAdjusted = "UPDATE visitor_logs SET room_adjusted = 1 WHERE visitor_logs_id = ?";
                if ($stmt3 = $conn->prepare($markAdjusted)) {
                    $stmt3->bind_param("i", $update_status_id);
                    $stmt3->execute();
                    $stmt3->close();
                }
            }
            echo 'success';
        } else {
            echo 'error';
        }
        $stmt->close();
    }
}






// deletion 
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM visitor_logs WHERE visitor_logs_id = ?";
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        echo 'success';
        $stmt->close();
    }
}
?>



<script>
    document.querySelectorAll('.change-payment-status').forEach(icon => {
    icon.addEventListener('click', function() {
        const visitorId = this.getAttribute('data-id');
        const visitorEmail = this.getAttribute('data-email');

        Swal.fire({
            title: 'Enter Payment Amount',
            input: 'number',
            inputPlaceholder: 'Enter amount',
            showCancelButton: true,
            confirmButtonText: 'Confirm Payment',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value || value <= 0) {
                    return 'Amount must be greater than zero!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const amount = result.value; // Admin entered amount

                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_payment_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    console.log("Response: ", xhr.responseText); // Debugging ke liye

                    if (xhr.status == 200 && xhr.responseText.trim() == 'success') {
                        Swal.fire('Updated!', 'Payment status changed to Paid.', 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('Error!', 'Response: ' + xhr.responseText, 'error'); // Debugging alert
                    }
                };
                xhr.send(`visitor_logs_id=${visitorId}&visitor_email=${visitorEmail}&amount=${amount}`);
            }
        });
    });
});




</script>