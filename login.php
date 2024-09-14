<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ladki_bahin_yojana";

    // Create database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data safely
    $mobileNo = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

    // Google reCAPTCHA secret key
    $secretKey = '6LeDITAqAAAAAJdKDzZYJcxcprNLLQeW4tgIKqfa';

    // Verify reCAPTCHA
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
    $responseData = json_decode($verifyResponse);

    // Check if reCAPTCHA is successful
    if ($responseData->success) {
        // Prepare and execute statement to find user
        $stmt = $conn->prepare("SELECT * FROM users WHERE mobileNo = ?");
        $stmt->bind_param("s", $mobileNo);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Debugging output
            echo '<pre>';
            echo 'Stored Hash: ' . $user['pass'] . "\n";
            echo 'Input Password: ' . $password . "\n";
            echo 'Password Verify Result: ' . (password_verify($password, $user['pass']) ? 'Valid' : 'Invalid') . "\n";
            echo '</pre>';

            // Verify password
            if (password_verify($password, $user['pass'])) {
                // Start session and store user info
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['fullName'] = $user['fullName'];

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                echo "<script>alert('Invalid password.'); window.location.href='login.php';</script>";
            }
        } else {
            echo "<script>alert('No user found with this mobile number.'); window.location.href='login.php';</script>";
        }

        // Close statement
        $stmt->close();
    } else {
        echo "<script>alert('reCAPTCHA validation failed. Please try again.'); window.location.href='login.php';</script>";
    }

    // Close connection
    $conn->close();
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
        <h2>Login</h2>
        <form action="login.php" method="POST">
            <label for="mobile">Mobile No</label>
            <input type="tel" id="mobile" name="mobile" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="captcha">Captcha</label>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="6LeDITAqAAAAAKnq3i2SoqcrI0qyRl9Y8g6-YMvz"></div>
            </div>
            <button type="submit" id="login">Login</button><br><br>

            <p>Do you have an account?</p><a href="signup.php">Create Account</a>
            <p>Forget Password?</p><a href="forget.php">Click here</a>
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
