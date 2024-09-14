<!-- Php Form validation Script for Signup Process -->

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
    $fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $adharNo = isset($_POST['adharNo']) ? trim($_POST['adharNo']) : '';
    $mobileNo = isset($_POST['mobileNo']) ? trim($_POST['mobileNo']) : '';
    $password = isset($_POST['pass']) ? trim($_POST['pass']) : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? trim($_POST['confirmPassword']) : '';
    $district = isset($_POST['district']) ? trim($_POST['district']) : '';
    $taluka = isset($_POST['taluka']) ? trim($_POST['taluka']) : '';
    $village = isset($_POST['village']) ? trim($_POST['village']) : '';
    $municipalCorporation = isset($_POST['municipalCorporation']) ? trim($_POST['municipalCorporation']) : '';
    $authorizedPerson = isset($_POST['authorizedPerson']) ? trim($_POST['authorizedPerson']) : '';
    $captcha = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : '';

    // Google reCAPTCHA secret key
    $secretKey = '6LeDITAqAAAAAJdKDzZYJcxcprNLLQeW4tgIKqfa';

    // Verify reCAPTCHA
    $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha}");
    $responseData = json_decode($verifyResponse);

    // Check if reCAPTCHA is successful
    if ($responseData->success) {
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Invalid email format.');</script>";
        }
        // Validate Aadhar number (12 digits)
        else if(!preg_match('/^\d{12}$/', $adharNo)){
            echo "<script>alert('Aadhar number must be exactly 12 digits.');</script>";
        }
        // Validate Mobile number (10 digits)
        else if (!preg_match('/^\d{10}$/', $mobileNo)) {
            echo "<script>alert('Mobile number must be exactly 10 digits.');</script>";
        }
        // Check if passwords match
        else if ($password !== $confirmPassword) {
            echo "<script>alert('Passwords do not match.');</script>";
        } else {
            // Check for duplicate Email, Aadhar number, or Mobile number
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR adharNo = ? OR mobileNo = ?");
            $stmt->bind_param("sss", $email, $adharNo, $mobileNo);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<script>alert('Duplicate email, Aadhar number, or Mobile number found.');</script>";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Prepare and bind
                $stmt = $conn->prepare("INSERT INTO users (fullName, email, adharNo, mobileNo, pass, district, taluka, village, municipalCorporation, authorizedPerson) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $fullName, $email, $adharNo, $mobileNo, $hashedPassword, $district, $taluka, $village, $municipalCorporation, $authorizedPerson);

                // Execute the statement
                if ($stmt->execute()) {
                    echo "<script>alert('Registered data successfully..!');</script>";
                    header("Location: login.php");
                    exit(); // Ensure no further code is executed
                } else {
                    echo "Error: " . $stmt->error;
                }
            }

            // Close statement
            $stmt->close();
        }
    } else {
        echo "<script>alert('reCAPTCHA validation failed. Please try again.');</script>";
    }

    // Close connection
    $conn->close();
} else {
    // echo "Invalid request method.";
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ladki Bahin Yojana</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="css/media.css">
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

    <div class="div1">
        <img src="images/ladkibahin.png" id="logo1" alt="logo" srcset="">
        <h2 class="heading">Sign up</h2>
        <form method="POST" action="signup.php">
            <div class="mb-4">
                <label for="fullName">Full Name As Per Aadhar (In English)<span class="required-asterisk">*</span></label>
                <input type="text" id="fullName" name="fullName" placeholder="Full Name as per Aadhar" required />
            </div>
            
            <div class="mb-4">
                <label for="email">Email Id<span class="required-asterisk">*</span></label>
                <input type="email" id="email" name="email" placeholder="Enter your Email" required />
            </div>

            <div class="mb-4">
                <label for="adharNo">Aadhar No<span class="required-asterisk">*</span></label>
                <input type="tel" id="adharNo" name="adharNo" placeholder="Aadhar No." required />
            </div>
            <div class="mb-4">
                <label for="mobileNo">Mobile No<span class="required-asterisk">*</span></label>
                <input type="tel" id="mobileNo" name="mobileNo" placeholder="Mobile No." required />
            </div>
            <div class="mb-4">
                <label for="password">Password<span class="required-asterisk">*</span></label>
                <input type="password" id="password" name="pass" placeholder="Password" required />
            </div>
            <div class="mb-4">
                <label for="confirmPassword">Confirm Password<span class="required-asterisk">*</span></label>
                <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password" required />
            </div>
            <div class="mb-4">
                <label for="district">Enter District<span class="required-asterisk">*</span></label>
                <input type="text" id="district" name="district" placeholder="Enter District (Pune)" required />
            </div>
            <div class="mb-4">
                <label for="taluka">Enter Taluka<span class="required-asterisk">*</span></label>
                <input type="text" id="taluka" name="taluka" placeholder="Enter Taluka" required />
            </div>
            <div class="mb-4">
                <label for="village">Enter village<span class="required-asterisk">*</span></label>
                <input type="text" id="village" name="village" placeholder="Enter Village" required />
            </div>
            <div class="mb-4">
                <label for="municipalCorporation"><h4>ग्रामपंचायत/नगरपंचायत/महानगरपालिका<span class="required-asterisk">*</span></h4></label>
                <select id="municipalCorporation" name="municipalCorporation" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="Village Council">ग्रामपंचायत</option>
                    <option value="Municipality">नगरपालिका</option>
                    <option value="Municipal corporation">महानगरपालिका</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="authorizedPerson">Authorised Person<span class="required-asterisk">*</span></label>
                <select id="authorizedPerson" name="authorizedPerson" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="Anganwadi-Sevika">अंगणवाडी सेविका</option>
                    <option value="Anganwadi-Paryavekshika">अंगणवाडी पर्यवेक्षिका</option>
                    <option value="Mukhya-Sevika">मुख्यसेविका</option>
                    <option value="Setu-Sahitya-Kendra">सेतु सुविधा केंद्र</option>
                    <option value="Gramsevak">ग्रामसेवक</option>
                    <option value="CRP">समुह संसाधन व्यक्ती (CRP)</option>
                    <option value="Asha-Sevika">आशा सेविका</option>
                    <option value="Ward-Manager">वार्ड अधिकारी</option>
                    <option value="Aple-Sarkar-Seva-Kendra">आपले सरकार सेवा केंद्र</option>
                </select>
            </div>
            
        
        <!-- Additional fields here -->
            <label for="captcha">Captcha<span class="required-asterisk">*</span></label>
            <div class="recaptcha-container">
                <div class="g-recaptcha" data-sitekey="6LeDITAqAAAAAKnq3i2SoqcrI0qyRl9Y8g6-YMvz"></div>
            </div>
        
        <button type="submit" class="signup">Signup</button>
    </form>
    <p>Already Have Account? <a href="login.php">Login</a></p>    
    <p>Back to Home>> <a href="index.html">Home</a></p>    
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

