<?php include("includes/header.php"); ?>
<?php include("connection.php"); ?>

<div class="home">
    <div class="home_slider_container">
        <div class="owl-carousel owl-theme home_slider">
            <div class="slide">
                <div class="background_image" style="background-image:url(images/index_1.jpg)"></div>
                <div class="home_container">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="home_content text-center">
                                    <div class="home_title">UrbanCodez HMS</div>
                                    <div class="booking_form_container">
                                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                                        <form id="availability_form" class="booking_form">
                                            <div class="d-flex flex-xl-row flex-wrap align-items-right justify-content-center">
                                                
                                                <!-- Room Category Dropdown -->
                                                <div>
                                                    <select class="booking_input" name="room_category_id" required>
                                                        <option value="" class="text-dark">Select Room Category</option>
                                                        <?php
                                                        $result = $conn->query("SELECT * FROM room_categories");
                                                        while ($row = $result->fetch_assoc()) {
                                                            echo "<option class='text-dark' value='{$row['room_category_id']}'>{$row['category_name']} - Rs.{$row['price']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>

                                                <!-- Check-in -->
                                                <div>
                                                    <input type="date" class="booking_input" name="check_in" required>
                                                </div>
                                                <!-- Check-out -->
                                                <div>
                                                    <input type="date" class="booking_input" name="check_out" required>
                                                </div>
                                                <!-- Total Persons -->
                                                <div>
                                                    <input type="number" class="booking_input" name="total_persons" placeholder="Total Persons" required min="1">
                                                </div>
                                                <!-- Submit Button -->
                                                <div>
                                                    <button class="booking_button trans_200" type="submit">Check Availability</button>
                                                </div>
                                            </div>
                                        </form>
                                        <div id="availability_result"></div> 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>   
        </div>
    </div>
</div>

<?php include("testimonials.php"); ?>
<?php include("includes/footer.php"); ?>

<script>
    document.getElementById("availability_form").addEventListener("submit", function(e) {
        e.preventDefault(); 

        const formData = new FormData(this);

        fetch("check_availability.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            Swal.fire({
                icon: data.status === "success" ? "success" : "error",
                title: data.message
            });
        })
        .catch(error => {
            Swal.fire({
                icon: "error",
                title: "An error occurred!",
                text: "Please try again."
            });
        });
    });
</script>
