<?php 
ob_start();
include("admin_header.php");
include_once("../connection.php");
?>

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

        <div class="container">
            <h2 class="mb-4">Manage Customers</h2>

            <!-- Customers Table -->
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Registered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT * FROM customers ORDER BY created_at DESC";
                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            $count = 1;
                            while ($customer = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$count}</td>
                                        <td>{$customer['name']}</td>
                                        <td>{$customer['email']}</td>
                                        <td>{$customer['phone']}</td>
                                        <td>{$customer['address']}</td>
                                        <td>" . date("d-m-Y H:i", strtotime($customer['created_at'])) . "</td>
                                        <td>
                                            <button class='btn btn-sm btn-danger delete-customer' data-id='{$customer['customer_id']}'>
                                                <i class='fas fa-trash-alt'></i>
                                            </button>
                                        </td>
                                      </tr>";
                                $count++;
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-muted'>No customers found</td></tr>";
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


<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Delete Customer AJAX -->
<script>
$(document).ready(function() {
    $(".delete-customer").click(function() {
        var customerId = $(this).data("id");
        var row = $(this).closest("tr");

        if (confirm("Are you sure you want to delete this customer?")) {
            $.ajax({
                url: "delete_customer.php",
                type: "POST",
                data: { id: customerId },
                success: function(response) {
                    if (response == "success") {
                        row.fadeOut(300, function() { $(this).remove(); });
                    } else {
                        alert("Error deleting customer.");
                    }
                }
            });
        }
    });
});
</script>
