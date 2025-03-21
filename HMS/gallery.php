<?php include("includes/header.php"); ?>
	<!-- Home -->

	<div class="home">
		<div class="background_image" style="background-image:url(images/booking.jpg)"></div>
		<div class="home_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="home_content text-center">
							<div class="home_title">Room Details</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Booking -->

	<div class="booking">
    <div class="container">
        <div class="row">
            <div class="col">
                

                <!-- Booking Slider -->
                <div class="booking_slider_container">
                    <div class="owl-carousel owl-theme booking_slider">
                        <?php
                        include_once("connection.php"); // Include the database connection

                        // Query to fetch all categories
                        $sql = "SELECT * FROM room_categories";
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($category = $result->fetch_assoc()) {
                                $category_id = $category['room_category_id'];
                                $category_name = $category['category_name'];
                                $category_description = $category['category_description'];
                                $category_image = $category['category_image'];
                                $price = $category['price'];
                        ?>
                        <!-- Slide -->
                        <div class="booking_item">
                            <div class="background_image" style="background-image:url(admin/<?php echo $category_image; ?>)"></div>
                            <div class="booking_overlay trans_200"></div>
                            <div class="booking_item_content">
                                <div class="booking_item_list">
                                    <ul>
                                        <!-- Parse the category description into a list -->
                                        <?php
                                        $description_items = explode(',', $category_description); // Assuming comma-separated features
                                        foreach ($description_items as $item) {
                                            echo "<li>" . htmlspecialchars(trim($item)) . "</li>";
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="booking_price">$<?php echo htmlspecialchars($price); ?>/Night</div>
                            <div class="booking_link">
                                <a href="rooms_cat_details.php?category_id=<?php echo htmlspecialchars($category_id); ?>">
                                    <?php echo htmlspecialchars($category_name); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                            }
                        } else {
                            echo "<h3>No categories found.</h3>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




	<!-- Special -->

<!-- Special -->
<div class="special">
    <div class="parallax_background parallax-window" data-parallax="scroll" data-image-src="images/special.jpg" data-speed="0.8"></div>
    <div class="container">
        <div class="row">
            <div class="col-xl-6 offset-xl-6 col-lg-8 offset-lg-2">
                <div class="special_content">
                    <?php
                    // Include the database connection
                    include_once("connection.php");

                    // Query to fetch the latest special offer
                    $sql = "SELECT * FROM special_offers ORDER BY special_offers_id DESC LIMIT 1"; // Adjust query if needed
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        $special_offer = $result->fetch_assoc();
                    ?>
                        <!-- Display Special Offer -->
                        <div class="details_title"><?php echo htmlspecialchars($special_offer['title']); ?></div>
                        
                        <!-- Special Offer Description -->
                        <p class="details_description mt-3">
                            <?php echo nl2br(htmlspecialchars($special_offer['description'])); ?>
                        </p>

                        <!-- Special Offer Tags in the class details_long_list -->
                        <div class="details_long_list">
                            <ul class="d-flex flex-row align-items-start justify-content-start flex-wrap">
                                <?php
                                // Parse the tags (assumed comma-separated in the database)
                                $tags = explode(',', $special_offer['tags']);
                                foreach ($tags as $tag) {
                                    echo "<li>" . htmlspecialchars(trim($tag)) . "</li>";
                                }
                                ?>
                            </ul>
                        </div>

                        <div class="book_now_button"><a href="#">Book Now</a></div>
                    <?php
                    } else {
                        echo "<div class='details_title'>No special offers available at the moment.</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>


	<!-- Footer -->
	<?php include("includes/footer.php"); ?>