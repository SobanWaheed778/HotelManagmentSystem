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

        <!-- Handle Delete Functionality -->
        <?php
        if (isset($_GET['delete_id'])) {
            $delete_id = $_GET['delete_id'];

            // Fetch the category to delete its image
            $fetch_sql = "SELECT category_image FROM room_categories WHERE room_category_id = ?";
            $fetch_stmt = $conn->prepare($fetch_sql);
            $fetch_stmt->bind_param("i", $delete_id);
            $fetch_stmt->execute();
            $fetch_result = $fetch_stmt->get_result();
            $category = $fetch_result->fetch_assoc();

            if ($category) {
                // Delete the category record
                $delete_sql = "DELETE FROM room_categories WHERE room_category_id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $delete_id);

                if ($delete_stmt->execute()) {
                    // Delete the image file
                    if (file_exists($category['category_image'])) {
                        unlink($category['category_image']);
                    }
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Room category has been deleted successfully.',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = 'room_categories.php';
                        });
                    </script>";
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to delete the room category.',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    </script>";
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Not Found!',
                        text: 'Category not found.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>";
            }
        }
        ?>

        <!-- Room Categories Table -->
        <div class="container-fluid pt-4 px-4">
            <div class="bg-light text-center rounded p-4">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h6 class="mb-0">Room Categories</h6>
                    <a href="add_category.php">Add New Category</a>

            <?php
            // Success message after form submission
            if (isset($_GET['status']) && $_GET['status'] == 'success') {
                echo "<div class='alert alert-success' role='alert'>
                        Category updated successfully! ADD NEW
                      </div>";
            }
            ?>

                </div>
                <div class="table-responsive">
                    <table class="table text-start align-middle table-bordered table-hover mb-0">
                    <thead>
                        <tr class="text-dark">
                            <th scope="col">#</th>
                            <th scope="col">Category Name</th>
                            <th scope="col">Category Image</th>
                            <th scope="col">Price</th>
                            <th scope="col">Rooms Count</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>

                        <tbody>
                            <?php
                            // Fetch data from room_categories table
                            $sql = "SELECT * FROM room_categories ORDER BY created_at DESC";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $count = 1;
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . $count++ . "</td>";
                                    echo "<td>" . htmlspecialchars($row['category_name']) . "</td>";
                                    echo "<td><img src='../admin/" . htmlspecialchars($row['category_image']) . "' alt='Category Image' style='width: 50px; height: 50px; object-fit: cover;'></td>";
                                    echo "<td>" . htmlspecialchars($row['price']) . " USD</td>";
                                    echo "<td>" . htmlspecialchars($row['rooms_count']) . "</td>";
                                    echo "<td>" . htmlspecialchars(date('d-M-Y H:i', strtotime($row['created_at']))) . "</td>";
                                    echo "<td>";
                                    echo "<a href='edit_rooms_category.php?room_category_id=" . $row['room_category_id'] . "' class='btn btn-sm btn-warning'>Edit</a> ";
                                    echo "<a href='?delete_id=" . $row['room_category_id'] . "' class='btn btn-sm btn-danger delete-btn'>Delete</a>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center'>No categories found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php include("admin_footer.php"); ?>
    </div>
    <!-- Content End -->
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert2 Delete Confirmation
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const href = this.getAttribute('href');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = href;
                }
            });
        });
    });
</script>
