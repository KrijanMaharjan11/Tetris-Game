<?php
include 'Authentication/session.php'; // Ensure user is logged in
include 'Authentication/dbconfig.php'; // Include your MySQLi database connection script

// Get the posted JSON data
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['score']) && isset($_SESSION['user_id'])) {
    $score = (int)$data['score']; // Sanitize score to integer
    $user_id = $_SESSION['user_id']; // Get the user ID from the session

    // Prepare SQL query to insert the score into the database
    $stmt = $conn->prepare("INSERT INTO scores (user_id, score, score_date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $score);

    if ($stmt->execute()) {
        // If successful, send a JSON response back
        echo json_encode(['success' => true, 'message' => 'Score saved successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save score.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
?>
