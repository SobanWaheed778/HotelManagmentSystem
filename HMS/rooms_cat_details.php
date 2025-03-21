<?php 
include("connection.php"); // Database connection
include("includes/header.php"); 

// Fetch the `category_id` from the URL
if (isset($_GET['category_id']) && is_numeric($_GET['category_id'])) {
    $category_id = intval($_GET['category_id']);
    
    // Query to fetch details of the specific category
    $sql = "SELECT * FROM room_categories WHERE room_category_id = ?";
    $stmt = $conn->prepare($sql); // Use prepared statement to prevent SQL injection
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if the category exists
    if ($result->num_rows > 0) {
        $category = $result->fetch_assoc();
        $category_name = $category['category_name'];
        $category_description = $category['category_description'];
        $category_image = $category['category_image'];
        $price = $category['price'];
    } else {
        echo "<h3 class='text-center my-5'>Room category not found.</h3>";
        exit();
    }
} else {
    echo "<h3 class='text-center my-5'>Invalid request. No category ID provided.</h3>";
    exit();
}
?>

<!-- Home -->
<div class="home">
    <div class="background_image" style="background-image:url(images/booking.jpg)"></div>
    <div class="home_container">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="home_content text-center">
                        <div class="home_title"><?php echo htmlspecialchars($category_name); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Details Right -->
<div class="details my-5">
    <div class="container">
        <div class="row">
            
            <!-- Details Image -->
            <div class="col-xl-7 col-lg-6">
                <div class="details_image">
                    <div class="background_image" style="background-image:url(admin/<?php echo htmlspecialchars($category_image); ?>)"></div>
                </div>
            </div>

            <!-- Details Content -->
            <div class="col-xl-5 col-lg-6">
                <div class="details_content">
                    <div class="details_title"><?php echo htmlspecialchars($category_name); ?></div>
                    <div class="details_list">
                        <ul>
                            <?php 
                            // Split the category description into a list
                            $description_items = explode(',', $category_description);
                            foreach ($description_items as $item) {
                                echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                            }
                            ?>
                        </ul>
                    </div>
                    <div class="details_price">Price: $<?php echo htmlspecialchars($price); ?>/Night</div>
                    <div class="book_now_button"><a href="#">Book Now</a></div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include("includes/footer.php"); ?>
