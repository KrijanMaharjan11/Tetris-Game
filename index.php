<?php
include 'Authentication/session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tetris Game</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.4.0/p5.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>

    </style>

</head>

<body>
    <div class="navbar">
        <div class="header">
            <h2><a href="index.php">Tetris</a></h2>
        </div>
        <div class="game_controls">
            <button id="startButton" class="nav-button" onclick="startGame()">Start</button>
            <button id="pauseButton" class="nav-button" onclick="togglePause()" disabled>Pause</button>
            <button id="restartButton" class="nav-button" onclick="startGame()">Reset</button>
            <button id="resumeButton" class="nav-button" onclick="resumeGame()" disabled>Resume</button>
        </div>
        <div class="nav-control">
            <a href="Leaderboard.php" class="nav-button">
                Leaderboard
            </a>


            <div class="dropdown">
                <i class="fa-solid fa-gear" style="font-size:24px; cursor: pointer;"></i>
                <div class="dropdown-content">
                    <a href="userProfile.php"><i class="fa-solid fa-user"></i> User Profile</a>
                    <a href="#" id="openPopupLink" onclick="showControls()"><i class="fa-solid fa-gamepad"></i> Controls</a>

                    <a href="History.php">
                        <i class="fa-solid fa-calendar-days"></i> History
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="#" id="logoutLink" onclick="redirectToLogout()"><i class="fa-solid fa-lock"></i> Logout</a>
                    <?php else: ?>
                        <a href="#" id="loginLink" onclick="redirectToLogin()"><i class="fa-solid fa-lock"></i> Login</a>
                    <?php endif; ?>

                </div>
            </div>





        </div>
    </div>

    <div class="main">
        <div class="score" id="score">
            <h3>Score: <span id="score-value">0</span></h3>
        </div>
        <div class="game-container" id="game-container">
        </div>
        <div class="next-piece" id="next-piece-container">

        </div>
    </div>

    <!-- Game Over Popup -->
    <div id="game-over-popup" class="popup-gameover">
        <div class="popup-content2">
            <h2>Game Over</h2>
            <p>Your Score: <span id="final-score"></span></p>
            <button onclick="startGame()">Play Again</button>
        </div>
    </div>


    <!-- The Popup Modal -->
    <div id="controlsPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <h2>Tetris Game Controls</h2>
            <hr>
            <ul>
                <li>Left Arrow: <strong><i class="fa-solid fa-arrow-left"></i></strong></li>
                <li>Right Arrow: <strong><i class="fa-solid fa-arrow-right"></i></strong></li>
                <li>Rotate: <strong><i class="fa-solid fa-arrow-up"></i></strong></li>
                <li>Soft Drop: <strong><i class="fa-solid fa-arrow-down"></i></strong></li>
            </ul>
        </div>
    </div>

    <script src="sketch.js"></script>
    <script>
        const openPopupLink = document.getElementById('openPopupLink'); // Updated to the link ID
        const popup = document.getElementById('controlsPopup');
        const closeBtn = document.querySelector('.close');

        openPopupLink.addEventListener('click', (event) => {
            event.preventDefault();
            popup.style.display = 'block';
        });
        closeBtn.addEventListener('click', () => {
            popup.style.display = 'none';
        });
        window.addEventListener('click', (event) => {
            if (event.target === popup) {
                popup.style.display = 'none';
            }
        });

        function redirectToLogin() {
            window.location.href = 'Authentication/login.php';
        }

        function redirectToLogout() {
            window.location.href = 'logout.php';
        }
    </script>
    <script>
        const icon = document.querySelector('.fa-gear');
        const dropdownContent = document.querySelector('.dropdown-content');

        icon.addEventListener('click', function() {
            dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
        });

        window.addEventListener('click', function(e) {
            if (!icon.contains(e.target)) {
                dropdownContent.style.display = 'none';
            }
        });
    </script>

</body>

</html>