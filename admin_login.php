<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$database = "ladki_bahin_yojana";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $adminUsername = $_POST['username'];
    $adminPassword = $_POST['password'];
    $recaptchaResponse = $_POST['g-recaptcha-response'];

    // Verify reCAPTCHA
    $secretKey = '6LeDITAqAAAAAJdKDzZYJcxcprNLLQeW4tgIKqfa'; // Replace with your actual secret key
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if ($responseKeys['success']) {
        // For simplicity, assuming a hardcoded username and password. Replace with database check in production.
        if ($adminUsername == 'admin' && $adminPassword == '123') {
            $_SESSION['admin_logged_in'] = true;
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $loginError = "Invalid username or password.";
        }
    } else {
        $loginError = "Please complete the CAPTCHA.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ladki Bahin Yojana</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="website icon" type="png" href="images/mainLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div class="intro">
        <div class="logo-header">
            <img src="images/mainLogo.png" alt="">
            <h4>माझी लाडकी बहीण योजना</h4>
        </div>
    </div>

    

    <!-- logobar -->
    <section class="logobar">
        <div class="logos">
            <div class="icon">
                <img src="images/mainLogo.png" alt="">
                <h1>माझी लाडकी बहीण योजना</h1>
            </div>
            <img src="images/logo-maha.png" alt="" class="mahaLogo">
            <div class="editor">
                <i class="bi bi-moon-stars-fill" id="themeToggle"></i>
            </div>
        </div>
    </section>
    <div class="login-container">
        <img src="images/ladkibahin.png" id="logo1" alt="" srcset="">
        <h2>Admin Login</h2>
        <form action="admin_login.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="captcha">Captcha</label>
            <div class="g-recaptcha" data-sitekey="6LeDITAqAAAAAKnq3i2SoqcrI0qyRl9Y8g6-YMvz"></div>

            <button type="submit" id="login">Login</button><br><br>

            <!-- <p>Do you have an account?</p><a href="signup.php">Create Account</a> -->
            <!-- <p>Forget Password?</p><a href="forget.php">Click here</a> -->
        </form>
    </div>
    <section class="footer">
        <div class="mainAdd">
            <h3>Created By RAY TEAM(<a>Rohit</a>,<a href="https://www.linkedin.com/in/aditya-bhosale-624335288/" target="_blank">Aditya</a>,<a>Yojana</a>)</h3>
            <p>@Fortune Cloud Technology (Project Fest Competition 2024)</p>
        </div>

        <div class="copyrights">
            <p>Copyright © 2024 Aditya, Rohit, Yojana. All rights reserved.</p>
        </div>
    </section>
    <?php if ($loginError): ?>
        <script>
            alert('<?php echo $loginError; ?>');
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const themeToggle = document.getElementById("themeToggle");
            const body = document.body;
            
            if (themeToggle) { // Check if the themeToggle element exists
                // Check if dark mode is enabled in local storage
                if (localStorage.getItem("theme") === "dark") {
                    body.classList.add("dark-mode");
                    themeToggle.classList.replace("bi-moon-stars-fill", "bi-brightness-high-fill");
                }
                
                // Toggle dark mode and icon on click
                themeToggle.addEventListener("click", () => {
                    body.classList.toggle("dark-mode");
                    
                    if (body.classList.contains("dark-mode")) {
                        themeToggle.classList.replace("bi-moon-stars-fill", "bi-brightness-high-fill");
                        localStorage.setItem("theme", "dark");
                    } else {
                        themeToggle.classList.replace("bi-brightness-high-fill", "bi-moon-stars-fill");
                        localStorage.setItem("theme", "light");
                    }
                });
                
            }
        });
    </script>
        
    <script src="js/app.js"></script>
</body>
</html>
