<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Fetch user details for pre-filling the form
$userId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT fullName, email, adharNo, mobileNo, district, taluka, village, municipalCorporation, authorizedPerson FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close statement
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get updated user info
    $fullName = trim($_POST['fullName']);
    $email = trim($_POST['email']);
    $adharNo = trim($_POST['adharNo']);
    $mobileNo = trim($_POST['mobileNo']);
    $district = trim($_POST['district']);
    $taluka = trim($_POST['taluka']);
    $village = trim($_POST['village']);
    $municipalCorporation = trim($_POST['municipalCorporation']);
    $authorizedPerson = trim($_POST['authorizedPerson']);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    }
    // Validate Aadhar number (12 digits)
    else if (!preg_match('/^\d{12}$/', $adharNo)) {
        $message = "Aadhar number must be exactly 12 digits.";
    }
    // Validate Mobile number (10 digits)
    else if (!preg_match('/^\d{10}$/', $mobileNo)) {
        $message = "Mobile number must be exactly 10 digits.";
    } else {
        // Check for duplicate Email, Aadhar number, or Mobile number (excluding the current user)
        $stmt = $conn->prepare("SELECT * FROM users WHERE (email = ? OR adharNo = ? OR mobileNo = ?) AND id != ?");
        $stmt->bind_param("sssi", $email, $adharNo, $mobileNo, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "Duplicate email, Aadhar number, or Mobile number found.";
        } else {
            // Update user info
            $stmt = $conn->prepare("UPDATE users SET fullName = ?, email = ?, adharNo = ?, mobileNo = ?, district = ?, taluka = ?, village = ?, municipalCorporation = ?, authorizedPerson = ? WHERE id = ?");
            $stmt->bind_param("sssssssssi", $fullName, $email, $adharNo, $mobileNo, $district, $taluka, $village, $municipalCorporation, $authorizedPerson, $userId);

            if ($stmt->execute()) {
                $message = "Profile updated successfully!";
            } else {
                $message = "Error updating profile: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    }
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    <!-- <link rel="stylesheet" href="css/signup.css"> -->
    <link rel="stylesheet" href="css/media.css">
    <link rel="website icon" type="png" href="images/mainLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
</head>
<body>
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
    <div class="update-container">
        <h2>Update Profile</h2>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo htmlspecialchars($user['fullName']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="adharNo">Aadhar Number:</label>
            <input type="text" id="adharNo" name="adharNo" value="<?php echo htmlspecialchars($user['adharNo']); ?>" required>

            <label for="mobileNo">Mobile Number:</label>
            <input type="text" id="mobileNo" name="mobileNo" value="<?php echo htmlspecialchars($user['mobileNo']); ?>" required>

            <label for="district">District:</label>
            <input type="text" id="district" name="district" value="<?php echo htmlspecialchars($user['district']); ?>" required>

            <label for="taluka">Taluka:</label>
            <input type="text" id="taluka" name="taluka" value="<?php echo htmlspecialchars($user['taluka']); ?>" required>

            <label for="village">Village:</label>
            <input type="text" id="village" name="village" value="<?php echo htmlspecialchars($user['village']); ?>" required>

            <label for="municipalCorporation"><h4>ग्रामपंचायत/नगरपालिका/महानगरपालिका<span class="required-asterisk">*</span></h4></label>
                <select id="municipalCorporation" name="municipalCorporation" required>
                    <option value="" disabled selected>Select an option</option>
                    <option value="Village Council">ग्रामपंचायत</option>
                    <option value="Municipality">नगरपालिका</option>
                    <option value="Municipal corporation">महानगरपालिका</option>
                </select>

            <label for="authorizedPerson">Authorised Person*</label>
            <select id="authorizedPerson" name="authorizedPerson" required>
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

            <div class="button-container">
                <button type="submit" class="btn">Update Info</button>
            </div>
        </form>
        <div class="button-container">
            <a href="profile.php" class="btn">Back to Profile</a>
        </div>
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
  

  //navbar butn
document.addEventListener("DOMContentLoaded", function() {
    const menuBtns = document.querySelectorAll(".menu-btn");  // All buttons
    const navs = document.querySelectorAll(".nav");            // All navs
  
    if (menuBtns.length > 0 && navs.length > 0) {
      menuBtns.forEach((menuBtn, index) => {
        const nav = navs[index]; // Match each button with its nav
  
        menuBtn.addEventListener("click", function() {
          if (nav) {
            nav.classList.toggle("show");
          }
        });
      });
    }
  });
    </script>
    <script src="js/app.js"></script>

</body>
</html>
