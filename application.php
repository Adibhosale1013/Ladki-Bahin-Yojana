<?php
session_start();


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$secretKey = '6LeDITAqAAAAAJdKDzZYJcxcprNLLQeW4tgIKqfa';

$servername = "localhost";
$username = "root";
$password = "";
$database = "ladki_bahin_yojana";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$responseMessages = [
    'aadhar-card' => '',
    'id-card' => '',
    'income-card' => '',
    'acceptance-card' => '',
    'passbook' => '',
    'photo' => '',
    'other-state' => ''
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$recaptchaResponse");
    $responseKeys = json_decode($response, true);

    if (intval($responseKeys["success"]) !== 1) {
        echo "<script>alert('Please complete the reCAPTCHA'); window.location.href = 'application.php';</script>";
        exit;
    }

    $errors = [];

    if (empty($_POST['full-name'])) $errors[] = 'Full name is required.';
    if (empty($_POST['husband-name'])) $errors[] = 'Husband/Parent name is required.';
    if (empty($_POST['birth-date'])) $errors[] = 'Birth date is required.';
    if (empty($_POST['aadhaar-number']) || !preg_match('/^\d{12}$/', $_POST['aadhaar-number'])) $errors[] = 'Aadhaar Number must be exactly 12 digits.';
    if (empty($_POST['mobile-number']) || !preg_match('/^\d{10}$/', $_POST['mobile-number'])) $errors[] = 'Mobile number must be exactly 10 digits.';
    if (empty($_POST['email-id'])) $errors[] = 'Email ID is required.';  // Added Email validation (no filter_var)
    if (empty($_POST['aadhaar-address'])) $errors[] = 'Aadhaar Address is required.';
    if (empty($_POST['district'])) $errors[] = 'District is required.';
    if (empty($_POST['village'])) $errors[] = 'Village/City is required.';
    if (empty($_POST['panchayat'])) $errors[] = 'Panchayat is required.';
    if (empty($_POST['benefit'])) $errors[] = 'Benefit status is required.';
    if (empty($_POST['scheme'])) $errors[] = 'Scheme is required.';
    if (empty($_POST['location'])) $errors[] = 'Marital status is required.';
    if (empty($_POST['birth'])) $errors[] = 'Birth place status is required.';

    // Date of Birth Validation
    if (!empty($_POST['birth-date'])) {
        $birthDate = new DateTime($_POST['birth-date']);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
        if ($age < 21 || $age > 65) {
            $errors[] = 'Age must be between 21 and 65 years old.';
        }
    }

    if (!isset($_POST['terms'])) {
        $errors[] = 'You must accept the terms and conditions.';
    }

    // Check if 'other-state' file is required and if uploaded
    if ($_POST['birth'] === 'yes' && !isset($_FILES['other-state'])) {
        $errors[] = 'Other state document is required.';
    }

    // Check for duplicate Aadhaar number
    $aadhaarNumber = $_POST['aadhaar-number'];
    // $mobileNumber = $_POST['mobile-number'];
    $stmt = $conn->prepare("SELECT COUNT(*) FROM applicants WHERE aadhaar_number = ?");
    $stmt->bind_param("s", $aadhaarNumber);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    
    if ($count > 0) {
        $errors[] = 'Aadhaar Number already exists.';
    }

    if (!empty($errors)) {
        echo "<script>alert('".implode("\\n", $errors)."'); window.location.href = 'application.php';</script>";
    } else {
        $uploadDir = 'uploads/';
        $uploadedFiles = [];
        $fileFields = ['aadhar-card', 'id-card', 'income-card', 'acceptance-card', 'passbook', 'photo'];

        foreach ($fileFields as $fileField) {
            if (isset($_FILES[$fileField]) && $_FILES[$fileField]['error'] === UPLOAD_ERR_OK) {
                $fileTmpPath = $_FILES[$fileField]['tmp_name'];
                $fileName = $_FILES[$fileField]['name'];
                $filePath = $uploadDir . basename($fileName);
                move_uploaded_file($fileTmpPath, $filePath);
                $uploadedFiles[$fileField] = $filePath;
                $responseMessages[$fileField] = 'Document uploaded';
            } else {
                $responseMessages[$fileField] = 'Failed to upload document';
            }
        }

        // Handling 'other-state' file
        if ($_POST['birth'] === 'yes' && isset($_FILES['other-state']) && $_FILES['other-state']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['other-state']['tmp_name'];
            $fileName = $_FILES['other-state']['name'];
            $filePath = $uploadDir . basename($fileName);
            move_uploaded_file($fileTmpPath, $filePath);
            $uploadedFiles['other-state'] = $filePath;
            $responseMessages['other-state'] = 'Document uploaded';
        } else {
            $uploadedFiles['other-state'] = null;
        }

        // Assigning the values to variables
        $aadharCardPath = $uploadedFiles['aadhar-card'] ?? null;
        $idCardPath = $uploadedFiles['id-card'] ?? null;
        $incomeCardPath = $uploadedFiles['income-card'] ?? null;
        $acceptanceCardPath = $uploadedFiles['acceptance-card'] ?? null;
        $passbookPath = $uploadedFiles['passbook'] ?? null;
        $otherStatePath = $uploadedFiles['other-state'] ?? null;
        $photoPath = $uploadedFiles['photo'] ?? null;

        $stmt = $conn->prepare("INSERT INTO applicants (
            full_name, husband_name, birth_date, aadhaar_number, mobile_number, email_id, aadhaar_address, district, village, panchayat, benefit_status, 
            scheme, marital_status, birth_place_status, bank_name, account_name, account_number, ifsc_code, aadhaar_linked_to_bank, 
            aadhar_card_path, id_card_path, income_card_path, acceptance_card_path, passbook_path, other_state_path, photo_path, situation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'Pending')");
                
        $stmt->bind_param("ssssssssssssssssssssssssss", 
            $_POST['full-name'], $_POST['husband-name'], $_POST['birth-date'], $_POST['aadhaar-number'], $_POST['mobile-number'], $_POST['email-id'], $_POST['aadhaar-address'], $_POST['district'],
            $_POST['village'], $_POST['panchayat'], $_POST['benefit'], $_POST['scheme'],
            $_POST['location'], $_POST['birth'], $_POST['bank'], $_POST['ac-name'], $_POST['ac-no'], $_POST['ifsc'],
            $_POST['bank-aadhaar'], 
            $aadharCardPath, $idCardPath, $incomeCardPath, $acceptanceCardPath, $passbookPath, 
            $otherStatePath, $photoPath
        );
        
        // Response Handling
        if ($stmt->execute()) {
            // Send Confirmation Email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'adibhosale1013@gmail.com';
                $mail->Password = 'pijvgzphuklppiye';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                $mail->setFrom('adibhosale1013@gmail.com', 'Ladki Bahin Yojana');
                $mail->addAddress($_POST['email-id']);
                $mail->isHTML(true);
                $mail->Subject = '=?UTF-8?B?'.base64_encode('माझी लाडकी बहीण योजना').'?=';
                $mail->Body = "Dear " . $_POST['full-name'] . ",<br><br>Thank you for submitting your application. We have received your form and will review it shortly.<br><br>Best regards,<br>Ladki Bahin Yojana Team";

                $mail->send();
                
                echo "<script>
                    alert('Form submitted successfully and email sent!');
                    window.location.href = 'dashboard.php';
                </script>";
            } catch (Exception $e) {
                echo "<script>
                    alert('Form submitted successfully but email could not be sent. Error: " . $mail->ErrorInfo . "');
                    window.location.href = 'dashboard.php';
                </script>";
            }
        } else {
            echo "<script>
                alert('Error: " . $stmt->error . "');
                window.location.href = 'application.php';
            </script>";
        }

        

        $stmt->close();
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/signup.css">
    <link rel="stylesheet" href="css/application.css">
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
    
    
    <form method="POST" enctype="multipart/form-data" action="application.php">
        <img src="images/ladkibahin.png" id="logo1" alt="logo">
        <label for="full-name"><h4>महिलेचे संपूर्ण नाव (आधार कार्ड प्रमाणे)<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="full-name" name="full-name" placeholder="महिलेचे संपूर्ण नाव" required />
        
        <label for="husband-name"><h4>पति/वडीलाचे नाव<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="husband-name" name="husband-name" placeholder ="पति/वडीलाचे नाव">
        
        <label for="birth-date"><h4>जन्म दिनांक: दिनांक/महिना/वर्ष<span class="required-asterisk">*</span></h4></label>
        <input type="date" id="birth-date" name="birth-date" required />
        
        <label for="aadhaar-number"><h4>आधार क्रमांक<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="aadhaar-number" name="aadhaar-number" placeholder="आधार क्रमांक" required />

        <label for="mobile-number"><h4>मोबाईल क्रमांक<span class="required-asterisk">*</span></h4></label> 
        <input type="text" id="mobile-number" name="mobile-number" placeholder="Registered Mobile No:" maxlength="10" required />

        <label for="email-id">Email ID:</label>
        <input type="email" id="email-id" name="email-id" placeholder="Registered Email Id: " required>


        <label for="aadhaar-address"><h4>अर्जदाराचा संपूर्ण पत्‍ता (आधार कार्ड प्रमाणे)<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="aadhaar-address" name="aadhaar-address" placeholder="अर्जदाराचा संपूर्ण पत्‍ता" required />
        
        <label for="district"><h4>जिल्हा<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="district" name="district" placeholder="जिल्हा*" required />
        
        <label for="village"><h4>गाव/शहर<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="village" name="village" placeholder="गाव/शहर*" required />
        
        <label for="panchayat"><h4>ग्रामपंचायत/नगरपंचायत/महानगरपालिका<span class="required-asterisk">*</span></h4></label>
        <select id="panchayat" name="panchayat" required>
            <option value="" disabled selected>Select an option</option>
            <option value="Village Council">ग्रामपंचायत</option>
            <option value="Municipality">नगरपंचायत</option>
            <option value="Municipal corporation">महानगरपालिका</option>
        </select>
                
        <label for="benefit"><h4>शासनाच्या इतर विभागामार्फत राबवण्यात येणाऱ्या आर्थिक योजनांचा लाभ घेत आहात का?<span class="required-asterisk">*</span></h4></label>
        <select id="benefit" name="benefit">
            <option value="">कृपया निवडा</option>
            <option value="yes">होय</option>
            <option value="no">नाही</option>
        </select>
        
        <label for="scheme"><h4>विभाग निहाय योजना<span class="required-asterisk">*</span></h4></label>
        <select id="scheme" name="scheme">
            <option value="">कृपया निवडा</option>
            <option value="scheme1">मुख्यमंत्री लाडकी बहीण योजना</option>
            <option value="scheme2">इतर योजना</option>
        </select>
        
        <label for="location"><h4>वैवाहिक स्थिती<span class="required-asterisk">*</span></h4></label>
        <select id="location" name="location">
            <option value="">कृपया निवडा</option>
            <option value="married">विवाहित</option>
            <option value="unmarried">अविवाहित</option>
        </select>
        
        <label for="birth"><h4>महिलेचा जन्म परराज्यात झाला आहे का ?<span class="required-asterisk">*</span></h4></label>
        <select id="birth" name="birth">
            <option value="">कृपया निवडा</option>
            <option value="yes">होय</option>
            <option value="no">नाही</option>
        </select>
        
        <h4>अर्जदाराचे खाते असलेल्या बँकेचा तपशील</h4>
        <label for="bank"><h4>बँकेचे नाव<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="bank" name="bank" placeholder="बँकेचे पूर्ण नाव" required />
        
        <label for="ac-name"><h4>बँक धारकाचे नाव<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="ac-name" name="ac-name" placeholder="बँकेचे धारकाचे नाव" required />
        
        <label for="ac-no"><h4>बँक खाते क्रमांक<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="ac-no" name="ac-no" placeholder="बँक खाते क्रमांक" required />
        
        <label for="ifsc"><h4>IFSC कोड<span class="required-asterisk">*</span></h4></label>
        <input type="text" id="ifsc" name="ifsc" placeholder="IFSC कोड" required />
        
        <label for="bank-aadhaar"><h4>आपल्या आधार क्रमांक बँकेत जोडला आहे का?<span class="required-asterisk">*</span></h4></label>
        <select id="bank-aadhaar" name="bank-aadhaar">
            <option value="yes">होय</option>
            <option value="no">नाही</option>
        </select>
        
        <h4>खालील सर्व कागदपत्रे Upload करण्यासाठी यावी<span class="required-asterisk">*</span></h4>
        <!-- Aadhaar Card Upload -->
        <div id="aadhar-card-section" class="upload-section">
            <label for="aadhar-card"><h4>Aadhaar Card<span class="required-asterisk">*</span></h4></label>
            <input type="file" id="aadhar-card" name="aadhar-card" accept="image/*" />
            <div id="aadhar-card-feedback"></div>
        </div>
        
        <!-- ID Card Upload -->
        <div id="id-card-section" class="upload-section">
            <label for="id-card"><h4>Domicile/ ID Certificate<span class="required-asterisk">*</span></h4></label>
            <input type="file" id="id-card" name="id-card" accept="image/*" />
            <div id="id-card-feedback"></div>
        </div>
        
        <!-- Income Card Upload -->
        <div id="income-card-section" class="upload-section">
            <label for="income-card"><h4>Income Certificate<span class="required-asterisk">*</span></h4></label>
            <input type="file" id="income-card" name="income-card" accept="image/*" />
            <div id="income-card-feedback"></div>
        </div>
        
        <!-- Acceptance Card Upload -->
        <div id="acceptance-card-section" class="upload-section">
            <label for="acceptance-card"><h4>Acceptance Card<span class="required-asterisk">*</span></h4></label>
            <input type="file" id="acceptance-card" name="acceptance-card" accept="image/*" />
            <div id="acceptance-card-feedback"></div>
        </div>
        
        <!-- Passbook Upload -->
        <div id="passbook-section" class="upload-section">
            <label for="passbook"><h4>Passbook<span class="required-asterisk">*</span></h4></label>
            <input type="file" id="passbook" name="passbook" accept="image/*" />
            <div id="passbook-feedback"></div>
        </div>
        
        <!-- Photo Upload -->
        <div id="photo-section" class="upload-section">
            <label for="photo"><h4>Photo<span class="required-asterisk">*</span></h4></label>
            <input type="file" id="photo" name="photo" accept="image/*" />
            <div id="photo-feedback"></div>
        </div>
        
        <!-- Other State Document Upload (conditional) -->
        <div id="other-state-section" style="display: none;">
            <label for="other-state"><h4>Other State Document<span class="required-asterisk">*</span></h4></label>
            <input type="file" id="other-state" name="other-state" accept="image/*" />
            <div id="other-state-feedback"></div>
        </div>
        
        <label for="terms"><input type="checkbox" id="terms" name="terms" required>I accept the terms and conditions</label>
        
        <!-- Add reCAPTCHA field here -->
        <div class="recaptcha-container">
            <div class="g-recaptcha" data-sitekey="6LeDITAqAAAAAKnq3i2SoqcrI0qyRl9Y8g6-YMvz"></div>
        </div>

        
        <button type="button" id="open-popup">Submit</button>
        
        <div class="popup-overlay" id="popup-overlay"></div>
        <div class="popup" id="popup">
            <h5>Confirm Your Information</h5>
            <p>All provided information is correct. Do you want to proceed?</p>
            <button type="submit">Accept</button>
            <button type="button" id="close-popup">Deny</button>
        </div>
    </form>
    
    
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
        document.getElementById('open-popup').addEventListener('click', function() {
            document.getElementById('popup').classList.add('active');
            document.getElementById('popup-overlay').classList.add('active');
        });
        
        document.getElementById('close-popup').addEventListener('click', function() {
            document.getElementById('popup').classList.remove('active');
            document.getElementById('popup-overlay').classList.remove('active');
        });
        
        document.getElementById('birth').addEventListener('change', function() {
            var otherStateSection = document.getElementById('other-state-section');
            if (this.value === 'yes') {
                otherStateSection.style.display = 'block';
            } else {
                otherStateSection.style.display = 'none';
            }
        });
        
        document.querySelectorAll('input[type="file"]').forEach(function(input) {
            input.addEventListener('change', function() {
                var feedbackId = this.id + '-feedback';
                var feedbackDiv = document.getElementById(feedbackId);
                if (this.files.length > 0) {
                    feedbackDiv.textContent = 'Document selected';
                    feedbackDiv.style.color = 'green';
                } else {
                    feedbackDiv.textContent = 'No file selected';
                    feedbackDiv.style.color = 'red';
                }
            });
        });

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
<script src="js/app.js"></script>
</body>
</html>


