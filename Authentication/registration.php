<?php
// Include the database configuration file
include('dbconfig.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data and sanitize
    $fullname = htmlspecialchars($_POST['fullname']);
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Hash the password using bcrypt
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);

    // Check if the email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Email already exists
        echo "Email is already registered.";
    } else {
        // Insert new user into the database
        $insertSql = "INSERT INTO users (username, email, password_hash) VALUES ('$fullname', '$email', '$passwordHash')";

        // Execute the query
        if (mysqli_query($conn, $insertSql)) {
            echo '<script>
            alert("User Registered sucessfully.");
            window.location.href = "login.php";
        </script>';
            exit();
        } else {
            echo "Error: Could not register user.";
        }
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    echo "Invalid request.";
}
