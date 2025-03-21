<?php include("includes/header.php"); ?>
<?php include 'connection.php'; ?>

<style>
    /* Full-height background */
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
    .auth-container {
        position: relative;
        width: 380px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        text-align: center;
        color: white;
    }
    .auth-container h2 {
        margin-bottom: 15px;
        font-size: 24px;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .toggle-link {
        cursor: pointer;
        color: #FFD700;
        text-decoration: underline;
    }
</style>

<div class="home">
    <div class="background_image" style="background-image:url(images/booking.jpg)"></div>

    <div class="auth-container">
        <!-- Login Form -->
        <div id="loginForm">
            <h2 class="text-light">Login</h2>
            <form method="POST" action="auth_process.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="login_email" placeholder="Enter Email" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="login_password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3">Don't have an account? <span class="toggle-link" id="showRegister">Register</span></p>
        </div>

        <!-- Registration Form (Initially Hidden) -->
        <div id="registerForm" style="display: none;">
            <h2  class="text-light">Register</h2>
            <form method="POST" action="auth_process.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="reg_username" placeholder="Username" required>
                </div>
                <div class="form-group">
                    <input type="email" class="form-control" name="reg_email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="reg_password" placeholder="Password" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Register</button>
            </form>
            <p class="mt-3">Already have an account? <span class="toggle-link" id="showLogin">Login</span></p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $("#showRegister").click(function() {
        $("#loginForm").fadeOut(300, function() {
            $("#registerForm").fadeIn(300);
        });
    });

    $("#showLogin").click(function() {
        $("#registerForm").fadeOut(300, function() {
            $("#loginForm").fadeIn(300);
        });
    });
</script>
