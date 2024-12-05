<?php
// Include the database configuration file
include('dbconfig.php');

// Start a session to manage logged-in users
session_start();

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get email and password from the form
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    // Prepare a query to check if the email exists in the database
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if a user with that email was found
    if (mysqli_num_rows($result) > 0) {
        // Fetch user data
        $user = mysqli_fetch_assoc($result);

        // Verify the password
        if (password_verify($password, $user['password_hash'])) {
            // If password is correct, store user info in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Redirect to the homepage or dashboard
            header("Location: ../index.php");
            exit();
        } else {
            // If the password is incorrect, display an alert and redirect
            echo '<script>
                alert("Invalid password.");
                window.location.href = "login.php";
            </script>';
            exit();
        }
    } else {
        // If no user is found, display a "No user found" popup and redirect
        echo '<script>
            alert("No user found with this email.");
            window.location.href = "login.php";
        </script>';
        exit();
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}

// Close the database connection
mysqli_close($conn);
