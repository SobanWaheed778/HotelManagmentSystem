<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("includes/header.php");
include 'connection.php';
require 'vendor/autoload.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = mysqli_real_escape_string($conn, $_SESSION['customer_id']);
$query = "SELECT * FROM customers WHERE customer_id = '$customer_id'";
$result = mysqli_query($conn, $query);
$customer = mysqli_fetch_assoc($result);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $current_password = mysqli_real_escape_string($conn, $_POST['current_password']);
    $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);

    $password_query = "SELECT password FROM customers WHERE customer_id = '$customer_id'";
    $password_result = mysqli_query($conn, $password_query);
    $password_data = mysqli_fetch_assoc($password_result);

    if (!password_verify($current_password, $password_data['password'])) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        // <script>
            Swal.fire({
                icon: 'error',
                title: 'Incorrect Password!',
                text: 'Your current password is incorrect. Please try again.',
                confirmButtonColor: '#d33'
            });
        </script>";
    } else {
        $update_query = "UPDATE customers SET name='$name', email='$email', phone='$phone', address='$address'";

        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query .= ", password='$hashed_password'";
        }

        $update_query .= " WHERE customer_id='$customer_id'";

        if (mysqli_query($conn, $update_query)) {
            echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    title: 'Profile Updated!',
                    text: 'Your profile has been updated successfully. Please log in again.',
                    icon: 'success',
                    confirmButtonColor: '#3085d6'
                }).then(() => {
                    window.location.href = 'logout.php';
                });
            </script>";
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Update Failed!',
                    text: 'Something went wrong while updating your profile. Please try again.',
                    confirmButtonColor: '#d33'
                });
            </script>";
        }
    }
}
?>

<style>
    .home {
        position: relative;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .background_image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: brightness(0.7);
    }
    .profile-container {
        position: relative;
        width: 70%;
        background: rgba(255, 255, 255, 0.85);
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        text-align: center;
        color: black;
        transition: 0.3s ease-in-out;
    }
    .profile-container:hover {
        transform: scale(1.02);
    }
    .btn-custom {
        display: inline-block;
        padding: 5px 10px;
        margin-top: 10px;
        background-color: #FFA37B;
        border-radius: 5px;
        text-decoration: none;
        color: white;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-custom:hover {
        background-color: #ff7b50;
    }
    .password-container {
        position: relative;
    }
    .password-container input {
        width: 100%;
        padding-right: 40px;
    }
    .toggle-password {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>

<!-- Profile Update Form -->
<div class="home">
    <div class="background_image" style="background-image:url(images/booking.jpg)"></div>
    <div class="profile-container">
        <h2>Welcome, <?php echo $customer['name']; ?> 👋</h2>
        <p>Email: <?php echo $customer['email']; ?></p>

        <form method="POST" class="container mt-4 p-4 bg-white shadow rounded">
            <h4 class="text-center mb-3">Update Profile</h4>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" value="<?php echo $customer['name']; ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="<?php echo $customer['email']; ?>" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" value="<?php echo $customer['phone']; ?>" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" required><?php echo $customer['address']; ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Current Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter current password" required>
                        <span class="input-group-text">
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password')"></i>
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">New Password (optional)</label>
                    <div class="input-group">
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password">
                        <span class="input-group-text">
                            <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary w-50">Update Profile</button>
            </div>
        </form>

        <a href="user_profile.php" class="btn-custom">Booking Details</a>
        <a href="#" class="btn-custom logout-btn" onclick="confirmLogout()">Logout</a>
    </div>
</div>

<!-- SweetAlert and FontAwesome -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://kit.fontawesome.com/a076d05399.js"></script>

<script>
    function togglePassword(fieldId) {
        let field = document.getElementById(fieldId);
        let icon = field.nextElementSibling.firstElementChild;
        if (field.type === "password") {
            field.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            field.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    function confirmLogout() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You will be logged out of your account.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Logout!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'logout.php';
            }
        });
    }
</script>