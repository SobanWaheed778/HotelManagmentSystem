<?php
if (!isset($conn)) {
include_once("../connection.php");}

// Fetch latest 3 notifications
$notif_query = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 3";
$notif_result = $conn->query($notif_query);
?>

<nav class="navbar navbar-expand bg-light navbar-light sticky-top px-4 py-0">
    <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
        <h2 class="text-primary mb-0"><i class="fa fa-hashtag"></i></h2>
    </a>
    <a href="#" class="sidebar-toggler flex-shrink-0">
        <i class="fa fa-bars"></i>
    </a>
    <form class="d-none d-md-flex ms-4">
        <input class="form-control border-0" type="search" placeholder="Search">
    </form>
    <div class="navbar-nav align-items-center ms-auto">
        <!-- Messages -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa fa-envelope me-lg-2"></i>
                <span class="d-none d-lg-inline-flex">Message</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="#" class="dropdown-item">
                    <div class="d-flex align-items-center">
                        <img class="rounded-circle" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                        <div class="ms-2">
                            <h6 class="fw-normal mb-0">John sent you a message</h6>
                            <small>15 minutes ago</small>
                        </div>
                    </div>
                </a>
                <hr class="dropdown-divider">
                <a href="#" class="dropdown-item text-center">See all messages</a>
            </div>
        </div>

        <!-- Notifications -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <i class="fa fa-bell me-lg-2"></i>
                <span class="d-none d-lg-inline-flex">Notifications</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <?php
                if ($notif_result->num_rows > 0) {
                    while ($notif = $notif_result->fetch_assoc()) {
                        $boldClass = ($notif['status'] === 'unread') ? "fw-bold" : "";
                        echo "<a href='#' class='dropdown-item $boldClass'>
                                <h6 class='fw-normal mb-0'>{$notif['message']}</h6>
                                <small>" . date("d-m-Y H:i", strtotime($notif['created_at'])) . "</small>
                              </a>
                              <hr class='dropdown-divider'>";
                    }
                } else {
                    echo "<a href='#' class='dropdown-item text-muted text-center'>No new notifications</a>";
                }
                ?>
                <a href="notifications.php" class="dropdown-item text-center">See all notifications</a>
            </div>
        </div>

        <!-- User Profile -->
        <div class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                <img class="rounded-circle me-lg-2" src="img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <span class="d-none d-lg-inline-flex">HMS Admin</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end bg-light border-0 rounded-0 rounded-bottom m-0">
                <a href="#" class="dropdown-item">My Profile</a>
                <a href="#" class="dropdown-item">Settings</a>
                <a href="logout.php" class="dropdown-item">Log Out</a>
            </div>
        </div>
    </div>
</nav>