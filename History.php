<?php

// Include database configuration and session management
include 'Authentication/dbconfig.php';
include 'Authentication/session.php';

// Check if the user ID is set in the session
if (!isset($_SESSION['user_id'])) {
    echo "No user is logged in.";
    exit();
}

// Get the user ID from the session
$user_id = $_SESSION['user_id'];

// Fetch user history based on the user ID
$sql = "
    SELECT users.username, scores.score, scores.score_date 
    FROM scores 
    JOIN users ON scores.user_id = users.id 
    WHERE users.id = ?
    ORDER BY scores.score_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id); // Assuming user_id is an integer
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User History</title>
    <link rel="stylesheet" href="leaderboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* General styles for the body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            /* Light background for better contrast */
            margin: 0;
            padding: 0;
        }



        /* Table styles */
        table.History {
            width: 100%;
            /* Full width for the table */
            border-collapse: collapse;
            /* Remove double borders */
            margin-top: 20px;
            /* Space above the table */
        }

        table.History thead {
            background-color: #49474E;
            /* Bootstrap primary color */
            color: white;
            /* White text for table header */
        }

        table.History th,
        table.History td {
            padding: 12px;
            /* Padding inside table cells */
            text-align: left;
            /* Align text to the left */
            border-bottom: 1px solid #ddd;
            /* Light gray bottom border */
        }



        table.History td {
            background-color: #ffffff;
            /* White background for table cells */
        }

        table.History td:nth-child(odd) {
            background-color: #f9f9f9;
            /* Light gray for odd rows */
        }

        /* Heading styles */
        h2 {
            color: #333;
            /* Darker color for headings */
        }

        /* Horizontal line styles */
        hr {
            border: 1px solid #007bff;
            /* Blue color for horizontal line */
        }
    </style>
</head>

<body>
    <div class="navbar">
        <div class="header">
            <h2><a href="index.php">Tetris</a></h2>
        </div>
        <div class="home">
            <a href="index.php" class="button">
                Home
            </a>
        </div>
    </div>

    <div class="container">
        <h2>User History</h2>
        <hr>
        <table class="History">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>Username</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $rank = 1;
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $rank++ . "</td>";
                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['score']) . "</td>";
                        echo "<td>" . htmlspecialchars(date("Y-m-d", strtotime($row['score_date']))) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No scores yet for this user.</td></tr>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>