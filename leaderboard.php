<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <!-- <link rel="stylesheet" href="userProfile.css"> -->
    <link rel="stylesheet" href="leaderboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        <h2>Leaderboard</h2>
        <hr>
        <table class="leaderboard">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Score</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'Authentication/dbconfig.php';
                $sql = "
                    SELECT users.username, scores.score, scores.score_date 
                    FROM scores 
                    JOIN users ON scores.user_id = users.id 
                    ORDER BY scores.score DESC 
                    LIMIT 5";
                $result = $conn->query($sql);

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
                    echo "<tr><td colspan='4'>No scores yet.</td></tr>";
                }

                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>