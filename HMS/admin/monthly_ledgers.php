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
            <h2 class="mb-4">Monthly Ledger</h2>

            <!-- Monthly Ledger Table -->
            <div class="table-responsive">
                <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Total Customers</th>
                            <th>Total Bookings</th>
                            <th>Paid Amount</th>
                            <th>Unpaid Amount</th>
                            <th>Total Income</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = "SELECT DATE_FORMAT(v.check_in, '%M %Y') AS Month, 
                                        COUNT(DISTINCT v.visitor_logs_id) AS Total_Customers,
                                        COUNT(v.visitor_logs_id) AS Total_Bookings,
                                        COALESCE(SUM(CASE WHEN p.payment_status = 'Paid' THEN p.amount ELSE 0 END), 0) AS Paid_Amount,
                                        COALESCE(SUM(CASE WHEN p.payment_status = 'Unpaid' THEN p.amount ELSE 0 END), 0) AS Unpaid_Amount,
                                        COALESCE(SUM(p.amount), 0) AS Total_Income
                                 FROM visitor_logs v
                                 LEFT JOIN payments p ON v.visitor_logs_id = p.visitor_logs_id
                                 GROUP BY Month
                                 ORDER BY v.check_in DESC";

                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$row['Month']}</td>
                                        <td>{$row['Total_Customers']}</td>
                                        <td>{$row['Total_Bookings']}</td>
                                        <td>$" . number_format($row['Paid_Amount'], 2) . "</td>
                                        <td class='text-danger'>$" . number_format($row['Unpaid_Amount'], 2) . "</td>
                                        <td class='text-success'>$" . number_format($row['Total_Income'], 2) . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center text-muted'>No records found</td></tr>";
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
