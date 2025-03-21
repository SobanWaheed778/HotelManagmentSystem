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

        <!-- Room Category Form -->
        <div class="container">
            <h2 class="mb-4">Add New Room Category</h2>

            <?php
            // Success message after form submission
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo "<div class='alert alert-success' role='alert'>
                        Category updated successfully! ADD NEW
                      </div>";
            }
            ?>

            <?php
            // Form Submission
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $category_name = $_POST['category_name'];
                $category_description = $_POST['category_description'];
                $price = $_POST['price'];
                $rooms_count = $_POST['rooms_count']; // New input field

                // Handle file upload for category image
                $target_dir = "uploads/";
                $file_name = basename($_FILES["category_image"]["name"]);
                
                // Replace spaces with underscores and sanitize the filename
                $file_name = preg_replace('/\s+/', '_', $file_name); // Replace spaces with underscores
                $file_name = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $file_name); // Remove any special characters except _, -, .
                
                $target_file = $target_dir . $file_name;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                // Check if the file is an image
                if (getimagesize($_FILES["category_image"]["tmp_name"])) {
                    if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
                        // Insert data into the database with rooms_count
                        $sql = "INSERT INTO room_categories (category_name, category_description, category_image, price, rooms_count) 
                                VALUES (?, ?, ?, ?, ?)";
                        if ($stmt = $conn->prepare($sql)) {
                            $stmt->bind_param("sssdi", $category_name, $category_description, $target_file, $price, $rooms_count);
                            if ($stmt->execute()) {
                                echo "<div class='alert alert-success'>Room category added successfully!</div>";
                            } else {
                                echo "<div class='alert alert-danger'>Error: Could not add room category.</div>";
                            }
                            $stmt->close();
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Sorry, there was an error uploading your file.</div>";
                    }
                } else {
                    echo "<div class='alert alert-danger'>Please upload a valid image file.</div>";
                }
            }
            ?>

            <!-- Form for Adding Room Category -->
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="category_name" class="form-label">Room Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" required>
                </div>
                <div class="mb-3">
                    <label for="category_description" class="form-label">Room Category Description</label>
                    <textarea class="form-control" name="category_description" id="category_description" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="category_image" class="form-label">Category Image</label>
                    <input type="file" class="form-control" id="category_image" name="category_image" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price per Night ($)</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                </div>
                <div class="mb-3">
                    <label for="rooms_count" class="form-label">Number of Rooms</label>
                    <input type="number" class="form-control" id="rooms_count" name="rooms_count" min="1" required>
                </div>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
        </div>
        <!-- End of Room Category Form -->

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>
