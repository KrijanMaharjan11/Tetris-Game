<?php
// Start the session
// session_start();

// Include database configuration and session management
include 'Authentication/session.php';
include 'Authentication/dbconfig.php';

// Initialize variables
$name = '';
$email = '';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Prepare SQL statement for retrieving user info
    $sql = "SELECT username, email FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();

    // Fetch the result
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $name = $user['username'];
        $email = $user['email'];
    } else {
        echo "No user found";
    }

    $stmt->close();
} else {
    // Redirect to login page if not logged in
    header("Location: Authentication/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <link rel="stylesheet" href="userProfile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <div class="navbar">
        <div class="header">
            <h2><a href="index.php">Tetris</a></h2>
        </div>
        <div class="home">
            <!-- <a href="index.php" class="button">Home</a> -->
        </div>
    </div>
    <div class="container">
        <form method="post">
            <h1>View Profile</h1>
            <hr>
            <label for="username" class="form-label">User Name:</label><br>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($name); ?>" required>
            <button type="submit" class="update">Update Username</button>

            <label for="email" class="form-label">Email:</label>
            <input type="text" id="email" value="<?php echo htmlspecialchars($email); ?>" readonly class="inactive-input">

            <label for="new_password" class="form-label">New Password:</label>
            <input type="password" id="new_password" name="new_password">
            <button type="submit" class="update">Change Password</button>


            <!-- delete user  -->
            <input type="hidden" name="delete_user" id="delete_user" value="">

            <button type="button" class="delete">Delete Account</button>
        </form>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <p>Updated Successfully</p>
        </div>
    </div>

    <!-- delete popup  -->
    <div id="deletePopup" class="popup">
        <div class="popup-content">
            <h2>Confirm Account Deletion</h2>
            <p>Are you sure you want to delete your account? <br> This action cannot be undone.</p>
            <div class="popup-buttons">
                <button id="confirmDelete" class="confirm-btn">Yes, Delete</button>
                <button id="cancelDelete" class="cancel-btn">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        function showModal() {
            document.getElementById("successModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("successModal").style.display = "none";
        }

        window.onclick = function(event) {
            const modal = document.getElementById("successModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }


        // delete user 

        document.querySelector('.delete').addEventListener('click', function(event) {
            event.preventDefault(); // Prevent the form from submitting immediately
            document.getElementById('deletePopup').style.display = 'flex'; // Show the popup
        });

        // Handle Confirm Delete button click
        document.getElementById('confirmDelete').addEventListener('click', function() {
            // Redirect to delete_user.php page
            window.location.href = 'delete_user.php';
        });

        // Handle Cancel button click
        document.getElementById('cancelDelete').addEventListener('click', function() {
            document.getElementById('deletePopup').style.display = 'none'; // Hide the popup
        });

        // Optional: Close the popup if the user clicks outside the popup content
        window.onclick = function(event) {
            var popup = document.getElementById('deletePopup');
            if (event.target == popup) {
                popup.style.display = 'none';
            }
        }
    </script>
</body>

</html>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Update username
    if (isset($_POST['username'])) {
        $newUsername = $_POST['username'];
        $updateSql = "UPDATE users SET username = ? WHERE id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $newUsername, $userId); // "si" -> string, integer
        if ($updateStmt->execute()) {
            echo '<script>showModal();</script>';
        } else {
            echo '<script>alert("Error updating username.");</script>';
        }
        $updateStmt->close(); // Close the statement
    }

    // Change password
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the new password
        $passwordUpdateSql = "UPDATE users SET password_hash = ? WHERE id = ?";
        $passwordUpdateStmt = $conn->prepare($passwordUpdateSql);
        $passwordUpdateStmt->bind_param("si", $newPassword, $userId); // "si" -> string, integer
        if ($passwordUpdateStmt->execute()) {
            echo '<script>showModal();</script>';
        } else {
            echo '<script>alert("Error changing password.");</script>';
        }
        $passwordUpdateStmt->close(); // Close the statement
    }
}

// Close the connection (optional with mysqli)
$conn->close();
?>