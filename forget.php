<?php
$showPasswordForm = false; // Initialize the flag

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

    // Get Aadhar number from POST request
    $adharNo = isset($_POST['adharNo']) ? trim($_POST['adharNo']) : '';
    $captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

    // Google reCAPTCHA secret key
    $secretKey = '6LeDITAqAAAAAJdKDzZYJcxcprNLLQeW4tgIKqfa';

    // Verify reCAPTCHA
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
    $responseData = json_decode($verifyResponse);

    // Check if reCAPTCHA is successful
    if ($responseData->success) {
        // Validate Aadhar number (12 digits)
        if (!preg_match('/^\d{12}$/', $adharNo)) {
            echo "<script>alert('Aadhar number must be exactly 12 digits.');</script>";
        } else {
            // Check if Aadhar number exists in the database
            $stmt = $conn->prepare("SELECT * FROM users WHERE adharNo = ?");
            $stmt->bind_param("s", $adharNo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Set the flag to true to show the password form
                $showPasswordForm = true;
            } else {
                echo "<script>alert('Aadhar number not found.');</script>";
            }

            // Close statement
            $stmt->close();
        }
    } else {
        echo "<script>alert('reCAPTCHA validation failed. Please try again.');</script>";
    }

    // Close connection
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
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
        <img src="images/ladkibahin.png" id="logo1" alt="">
        <h2>Login</h2>

        <!-- HTML Form for Aadhar number input -->
        <form id="adharForm" action="forget.php" method="POST" <?php echo $showPasswordForm ? 'style="display:none;"' : ''; ?>>
            <label for="adharNo">Registered Aadhar Number :</label>
            <input type="text" name="adharNo" required>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="6LeDITAqAAAAAKnq3i2SoqcrI0qyRl9Y8g6-YMvz"></div>
            </div>
            <button type="submit">Submit</button>
        </form>

        <!-- Hidden form for setting a new password -->
        <form id="newPasswordForm" action="set_new_password.php" method="POST" <?php echo !$showPasswordForm ? 'style="display:none;"' : ''; ?>>
            <input type="hidden" name="adharNo" value="<?php echo htmlspecialchars($adharNo); ?>">
            <label for="newPassword">New Password:</label>
            <input type="password" name="newPassword" required>
            <label for="confirmNewPassword">Confirm New Password:</label>
            <input type="password" name="confirmNewPassword" required>
            <div class="g-recaptcha" data-sitekey="6LeDITAqAAAAAKnq3i2SoqcrI0qyRl9Y8g6-YMvz"></div>
            <button type="submit">Set New Password</button>
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

    <script src="js/app.js"></script>
    <script>
        // Night Mode Js : 
// Theme Toggle Functionality// Theme Toggle Functionality
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
</body>
</html>
