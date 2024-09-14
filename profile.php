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

// Fetch user details
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
    // Handle the profile picture upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["profile_pic"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["profile_pic"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    // if (file_exists($target_file)) {
    //     echo "Sorry, file already exists.";
    //     $uploadOk = 0;
    // }

    // Check file size
    // if ($_FILES["profile_pic"]["size"] > 5000000) {
    //     echo "Sorry, your file is too large.";
    //     $uploadOk = 0;
    // }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            // Update the database with the profile picture path
            $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
            $stmt->bind_param("si", $target_file, $userId);
            $stmt->execute();
            $stmt->close();
            echo "The Profile pic has been Updated.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Fetch user data
$stmt = $conn->prepare("SELECT fullName, email, adharNo, mobileNo, district, taluka, village, municipalCorporation, authorizedPerson, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/media.css">
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
    <div class="profile-container">
        <img src="images/ladkibahin.png" id="logo1" alt="logo" style="display:block; margin:auto;">
        <h2>User Profile</h2>
        <div id="profile-pic-container">
            <img id="profile-pic" src="<?php echo htmlspecialchars($user['profile_pic'] ?: 'images/default-profile.png'); ?>" alt="Profile Picture"><br>
            <form id="profile-pic-form" action="profile.php" method="POST" enctype="multipart/form-data">
                <label for="file-input" class="btn">Change Profile Picture</label>
                <input type="file" id="file-input" name="profile_pic" accept="image/*" onchange="document.getElementById('profile-pic-form').submit();">
            </form>
        </div>

        <table>
            <tr>
                <th>Full Name:</th>
                <td><?php echo htmlspecialchars($user['fullName']); ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
                <th>Aadhar Number:</th>
                <td><?php echo htmlspecialchars($user['adharNo']); ?></td>
            </tr>
            <tr>
                <th>Mobile Number:</th>
                <td><?php echo htmlspecialchars($user['mobileNo']); ?></td>
            </tr>
            <tr>
                <th>District:</th>
                <td><?php echo htmlspecialchars($user['district']); ?></td>
            </tr>
            <tr>
                <th>Taluka:</th>
                <td><?php echo htmlspecialchars($user['taluka']); ?></td>
            </tr>
            <tr>
                <th>Village:</th>
                <td><?php echo htmlspecialchars($user['village']); ?></td>
            </tr>
            <tr>
                <th>Municipal Corporation:</th>
                <td><?php echo htmlspecialchars($user['municipalCorporation']); ?></td>
            </tr>
            <tr>
                <th>Authorized Person:</th>
                <td><?php echo htmlspecialchars($user['authorizedPerson']); ?></td>
            </tr>
        </table>
        <div class="button-container">
            <a href="update_info.php" class="btn">Update Info</a>
        </div>
        <a href="dashboard.php" class="btn">Back to Dashboard</a>
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
