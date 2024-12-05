<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>Login Page</title>
</head>

<body>
    <nav>
        <h1>Tetris Game Login</h1>
    </nav>

    <div class="container">
        <div class="signup-section">
            <header>Signup</header>

            <div class="separator">
                <div class="line"></div>
            </div>

            <form action="registration.php" method="POST">
                <input type="text" name="fullname" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email address" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="separator">
                    <div class="line"></div>
                </div>
                <button type="submit" class="btn">Signup</button>
            </form>


        </div>



        <div class="login-section">
            <header>Login</header>

            <div class="separator">
                <div class="line"></div>
            </div>

            <?php if (isset($_GET['error'])): ?>
                <div class="error-message">
                    <?php
                    if ($_GET['error'] === 'usernotfound') {
                        echo "No account found with that email address.";
                    } elseif ($_GET['error'] === 'invalidpassword') {
                        echo "Incorrect password. Please try again.";
                    }
                    ?>
                </div>
            <?php endif; ?>

            <form action="login_validation.php" method="POST">
                <input type="email" name="email" placeholder="Email address" required>
                <input type="password" name="password" placeholder="Password" required>
                <div class="separator">
                    <div class="line"></div>
                </div>
                <button type="submit" class="btn">Login</button>
            </form>
        </div>

    </div>


    <script src="script.js"></script>

</body>

</html>