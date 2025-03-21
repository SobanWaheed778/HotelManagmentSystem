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

        <!-- Payments Management Start -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Payments Management</h6>
                    <a href="">Show All</a>
                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">#</th>
                            <th scope="col">Visitor Name</th>
                            <th scope="col">Visitor Email</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Payment Method</th>
                            <th scope="col">Transaction ID</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT p.*, v.visitor_name, v.visitor_email 
                                FROM payments p
                                JOIN visitor_logs v ON p.visitor_logs_id = v.visitor_logs_id
                                ORDER BY p.created_at DESC";
                        $result = $conn->query($sql);
                        
                        if ($result->num_rows > 0) {
                            $count = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $count++ . "</td>";
                                echo "<td>" . htmlspecialchars($row['visitor_name']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['visitor_email']) . "</td>";
                                echo "<td>$" . htmlspecialchars($row['amount']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['payment_method'] ?: 'N/A') . "</td>";
                                echo "<td>" . ($row['transaction_id'] ? htmlspecialchars($row['transaction_id']) : 'N/A') . "</td>";
                                echo "<td><a href='#' class='btn btn-danger btn-sm delete-payment' data-id='" . $row['payment_id'] . "'>Delete</a></td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No payments found</td></tr>";
                        }
                        ?>
                    </tbody>

                    </table>
                </div>
            </div>
        </div>
        <!-- Payments Management End -->

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>

<!-- SweetAlert for Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-payment').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const paymentId = this.getAttribute('data-id');
        
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
                xhr.open('POST', 'payments.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        button.closest('tr').remove();
                        Swal.fire('Deleted!', 'The record has been deleted.', 'success');
                    } else {
                        Swal.fire('Error!', 'There was an error deleting the record.', 'error');
                    }
                };
                xhr.send('delete_id=' + paymentId);
            }
        });
    });
});
</script>

<?php
// Payment Deletion
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM payments WHERE id = ?";
    if ($stmt = $conn->prepare($delete_sql)) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        echo 'success';
        $stmt->close();
    }
}
?>