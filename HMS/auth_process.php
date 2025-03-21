<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registration Process
    if (isset($_POST['reg_username']) && isset($_POST['reg_email']) && isset($_POST['reg_password'])) {
        $name = $conn->real_escape_string($_POST['reg_username']);
        $email = $conn->real_escape_string($_POST['reg_email']);
        $password = password_hash($_POST['reg_password'], PASSWORD_DEFAULT);  // Hash the password

        // Check if email already exists
        $checkQuery = "SELECT * FROM customers WHERE email='$email'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            echo '<script>alert("Email already exists!"); window.history.back();</script>';
        } else {
            // Insert the new user into the database
            $insertQuery = "INSERT INTO customers (name, email, password) VALUES ('$name', '$email', '$password')";
            if ($conn->query($insertQuery) === TRUE) {
                echo '<script>alert("Registration Successful! Please login."); window.location="login.php";</script>';
            } else {
                echo '<script>alert("Error: ' . $conn->error . '"); window.history.back();</script>';
            }
        }
    }

        // Login Process
        if (isset($_POST['login_email']) && isset($_POST['login_password'])) {
            $email = $conn->real_escape_string($_POST['login_email']);
            $password = $_POST['login_password'];  // Plain password entered by the user
    
            // Query to find the user
            $query = "SELECT * FROM customers WHERE email='$email'";
            $result = $conn->query($query);
    
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();  // Fetch user data
                if (password_verify($password, $row['password'])) {  // Check if the password matches the stored hash
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }

                    $_SESSION['customer_id'] = $row['customer_id'];
                    // $_SESSION['customer_name'] = $row['name'];
                    
                    echo '<script>alert("Login Successful!"); window.location="booking.php";</script>';
                } else {
                    echo '<script>alert("Invalid Credentials!"); window.history.back();</script>';
                }
            } else {
                echo '<script>alert("User not found!"); window.history.back();</script>';
            }
        }
}

?>