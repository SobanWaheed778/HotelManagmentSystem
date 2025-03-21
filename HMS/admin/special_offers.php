<?php ob_start(); ?>
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

        <div class="container">
            <h2 class="mb-4">Add Special Offers</h2>

            <?php
            // Handle Delete Functionality
            if (isset($_GET['delete_id'])) {
                $delete_id = $_GET['delete_id'];
                $delete_sql = "DELETE FROM special_offers WHERE special_offers_id = ?";
                if ($stmt = $conn->prepare($delete_sql)) {
                    $stmt->bind_param("i", $delete_id);
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Special offer deleted successfully!</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Error: Could not delete the special offer.</div>";
                    }
                    $stmt->close();
                }
            }

            // Check if a special offer already exists
            $check_sql = "SELECT * FROM special_offers LIMIT 1";
            $result = $conn->query($check_sql);

            if ($result->num_rows > 0) {
                $offer = $result->fetch_assoc();
                echo "<div class='alert alert-warning'>
                        <strong>Note:</strong> A special offer already exists. To add a new offer, please delete the current one.
                      </div>";

                // Display the existing offer details
                echo "<div class='card'>
                        <div class='card-body'>
                            <h4 class='card-title'>" . htmlspecialchars($offer['title']) . "</h4>
                            <p class='card-text'>" . htmlspecialchars($offer['description']) . "</p>
                            <p class='card-text'><strong>Tags:</strong> " . htmlspecialchars($offer['tags']) . "</p>
                            <div class='d-flex'>
                                <a href='special_offers.php?delete_id=" . $offer['special_offers_id'] . "' class='btn btn-danger'>Delete</a>
                            </div>
                        </div>
                      </div>";
            } else {
                // Handle form submission
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $title = $_POST['title'];
                    $description = $_POST['description'];
                    $tags = $_POST['tags'];

                    // Insert the new offer into the database
                    $sql = "INSERT INTO special_offers (title, description, tags) VALUES (?, ?, ?)";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sss", $title, $description, $tags);
                        if ($stmt->execute()) {
                            // Redirect to the same page with a success message
                            header("Location: special_offers.php?status=success");
                            exit;
                        } else {
                            echo "<div class='alert alert-danger'>Error: Could not add the special offer.</div>";
                        }
                        $stmt->close();
                    } else {
                        echo "<div class='alert alert-danger'>Error: Database query failed.</div>";
                    }
                }

                // Show the form for adding a new offer
                if (isset($_GET['status']) && $_GET['status'] == 'success') {
                    echo "<div class='alert alert-success'>Special offer added successfully!</div>";
                }
                ?>

                <!-- Form for Adding Special Offers -->
                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Offer Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Offer Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="tags" class="form-label">What it includes?</label>
                        <input type="text" class="form-control" id="tags" name="tags" placeholder="e.g., Balcony,Mountain view, Terrace" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Offer</button>
                </form>
                <?php
            }
            ?>
        </div>

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>
