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

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userId = $_SESSION['user_id'];

// Prepare and execute statement to get user application data
$stmt = $conn->prepare("SELECT * FROM applicants WHERE mobile_number = (SELECT mobileNo FROM users WHERE id = ?)");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $application = $result->fetch_assoc();
} else {
    echo "No application found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Status</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/view.css">
    <link rel="website icon" type="png" href="images/mainLogo.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

    <section class="navbar">
        <button class="menu-btn"><i class="bi bi-justify"></i></button>
        <div class="nav">
            <a href="index.html"><i class="bi bi-house-fill"></i> मुखपृष्ठ</a>
            <a href="application.php"><i class="bi bi-file-earmark-fill"></i>मुख्यमंत्री - माझी लाडकी बहीण योजनेचा अर्ज</a>  
            <!-- <a href="info.html"><i class="bi bi-info-circle-fill"></i>योजना</a>   -->
            <a href="view.php"><i class="bi bi-file-earmark"></i>यापूर्वी केलेले अर्ज</a> 
            <!-- <a href="impDoc.html"><i class="bi bi-file-earmark-text-fill"></i>आवश्यक कागदपत्रे</a>  -->
            <a href="profile.php"><i class="bi bi-person-check"></i>प्रोफाइल</a>
            <a href="index.html"><i class="bi bi-box-arrow-right"></i> Logout</a>  
            <a href="admin_login.php"><i class="bi bi-person-fill"></i>ॲडमिन लॉगिन</a>
        </div>
    </section>


    <main>
        <section class="application-status">
            <h2>Application Status</h2>
            <table>
                <tr>
                    <th>Full Name :</th>
                    <td><?php echo htmlspecialchars($application['full_name']); ?></td>
                </tr>
                <tr>
                    <th>Scheme :</th>
                    <td><?php echo $application['scheme'] === 'scheme1' ? 'माझी लाडकी बहीण योजना' : 'इतर योजना'; ?></td>
                </tr>
                <!-- <tr>
                    <th>Benefit Status :</th>
                    <td><?php echo htmlspecialchars($application['benefit_status']); ?></td>
                </tr> -->
                <tr>
                    <th>Marital Status : </th>
                    <td><?php echo htmlspecialchars($application['marital_status']); ?></td>
                </tr>
                <!-- <tr>
                    <th>Birth Place Status</th>
                    <td><?php echo htmlspecialchars($application['birth_place_status']); ?></td>
                </tr> -->
                <tr>
                    <th>Status :</th>
                    <td><?php echo htmlspecialchars($application['situation']); ?></td>
                </tr>
                <?php if ($application['situation'] === 'Rejected'): ?>
                <tr>
                    <th>Reason/ Feedback :</th>
                    <td><?php echo htmlspecialchars($application['rejection_reason']); ?></td>
                </tr>
                <?php elseif ($application['situation'] === 'Approved'): ?>
                <tr>
                    <th>Reason/ Feedback :</th>
                    <td><?php echo htmlspecialchars($application['rejection_reason']); ?></td>
                </tr>
                <?php else: ?>
                <tr>
                    <th>Reason/ Feedback : </th>
                    <td>Form is still being processed.</td>
                </tr>
                <?php endif; ?>
                <!-- Add more fields as needed -->
            </table>
        </section>
    </main>

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
