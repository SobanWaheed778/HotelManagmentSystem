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

        <!-- Edit Room Category Form -->
        <div class="container">
            <h2 class="mb-4">Edit Room Category</h2>

            <?php
            // Start output buffering to prevent premature output
            ob_start();

            // Fetch room category details
            if (isset($_GET['room_category_id']) && is_numeric($_GET['room_category_id'])) {
                $room_category_id = $_GET['room_category_id'];

                // Fetch room category details
                $sql = "SELECT * FROM room_categories WHERE room_category_id = ?";
                if ($stmt = $conn->prepare($sql)) {
                    $stmt->bind_param("i", $room_category_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $category = $result->fetch_assoc();
                    } else {
                        echo "<div class='alert alert-danger'>Category not found!</div>";
                        exit;
                    }
                    $stmt->close();
                }
            } else {
                echo "<div class='alert alert-danger'>Invalid category ID.</div>";
                exit;
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $category_name = $_POST['category_name'];
                $category_description = $_POST['category_description'];
                $price = $_POST['price'];
                $rooms_count = $_POST['rooms_count'];
                $image_updated = false;

                // Handle file upload (if a new image is provided)
                if (!empty($_FILES['category_image']['name'])) {
                    $target_dir = "uploads/";
                    $file_name = basename($_FILES["category_image"]["name"]);

                    // Sanitize the file name
                    $file_name = preg_replace('/\s+/', '_', $file_name); // Replace spaces with underscores
                    $file_name = preg_replace('/[^A-Za-z0-9_\-\.]/', '', $file_name); // Remove any special characters except _, -, .

                    $target_file = $target_dir . $file_name;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                    if (getimagesize($_FILES["category_image"]["tmp_name"])) {
                        if (move_uploaded_file($_FILES["category_image"]["tmp_name"], $target_file)) {
                            $image_updated = true;
                        } else {
                            echo "<div class='alert alert-danger'>Error uploading the image file.</div>";
                        }
                    } else {
                        echo "<div class='alert alert-danger'>Invalid image file. Please upload a valid image.</div>";
                    }
                }

                // Update the database
                if ($image_updated) {
                    $sql = "UPDATE room_categories SET category_name = ?, category_description = ?, category_image = ?, price = ?, rooms_count = ? WHERE room_category_id = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sssiii", $category_name, $category_description, $target_file, $price, $rooms_count, $room_category_id);
                        if ($stmt->execute()) {
                            header("Location: view_rooms_categories.php?status=success");
                            exit;
                        } else {
                            echo "<div class='alert alert-danger'>Error updating category: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }
                } else {
                    $sql = "UPDATE room_categories SET category_name = ?, category_description = ?, price = ?, rooms_count = ? WHERE room_category_id = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("ssiii", $category_name, $category_description, $price, $rooms_count, $room_category_id);
                        if ($stmt->execute()) {
                            header("Location: view_rooms_categories.php?status=success");
                            exit;
                        } else {
                            echo "<div class='alert alert-danger'>Error updating category: " . $stmt->error . "</div>";
                        }
                        $stmt->close();
                    }
                }
            }
            ?>

            <!-- Form for Editing Room Category -->
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="category_name" class="form-label">Room Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" value="<?php echo htmlspecialchars($category['category_name']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="category_description" class="form-label">Room Category Description</label>
                    <textarea class="form-control" name="category_description" id="category_description" required><?php echo htmlspecialchars($category['category_description']); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="category_image" class="form-label">Category Image (Upload a new image to replace)</label>
                    <input type="file" class="form-control" id="category_image" name="category_image">
                    <?php if (!empty($category['category_image'])): ?>
                        <p class="mt-2">Current Image:</p>
                        <img src="<?php echo htmlspecialchars('../admin/' . $category['category_image']); ?>" alt="Category Image" style="width: 100px; height: 100px; object-fit: cover;">
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="price" class="form-label">Price per Night ($)</label>
                    <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?php echo htmlspecialchars($category['price']); ?>" required>
                </div>

                <div class="mb-3">
                    <label for="rooms_count" class="form-label">Rooms Count</label>
                    <input type="number" class="form-control" id="rooms_count" name="rooms_count" min="1" value="<?php echo htmlspecialchars($category['rooms_count']); ?>" required>
                </div>


                <button type="submit" class="btn btn-primary">Update Category</button>
            </form>
        </div>
        <!-- End of Edit Room Category Form -->

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>