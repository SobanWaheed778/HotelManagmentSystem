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
            <h2 class="mb-4">Manage Special Offers</h2>

            <!-- Notifications Section -->
            <div class="mb-4">
                <h4>Notifications</h4>
                <ul class="list-group">
                    <?php
                    $notif_query = "SELECT * FROM notifications ORDER BY created_at DESC";
                    $notif_result = $conn->query($notif_query);
                    
                    if ($notif_result->num_rows > 0) {
                        while ($notif = $notif_result->fetch_assoc()) {
                            echo "<li class='list-group-item d-flex justify-content-between align-items-center border-bottom pb-2'>
                                    <div>
                                        {$notif['message']}  
                                        <small class='text-muted d-block'>" . date("d-m-Y H:i", strtotime($notif['created_at'])) . "</small>
                                    </div>
                                    <button class='btn btn-sm btn-danger delete-notif' data-id='{$notif['notification_id']}'>
                                        <i class='fas fa-trash-alt'></i>
                                    </button>
                                  </li>";
                        }
                    } else {
                        echo "<li class='list-group-item text-muted'>No notifications</li>";
                    }
                    ?>
                </ul>
            </div>
        </div>

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>


<!-- jQuery for AJAX -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Delete Notification AJAX -->
<script>
$(document).ready(function() {
    $(".delete-notif").click(function() {
        var notifId = $(this).data("id");
        var btn = $(this);

        $.ajax({
            url: "delete_notification.php",
            type: "POST",
            data: { id: notifId },
            success: function(response) {
                if (response == "success") {
                    btn.closest("li").fadeOut(300, function() { $(this).remove(); });
                } else {
                    alert("Error deleting notification.");
                }
            }
        });
    });
});
</script>
