<?php
// Include the config.php file to use the database connection
include 'config.php';

// Initialize the variables
$totalApplications = 0;
$approvedApplications = 0;

// Query to get total number of applications
$sqlTotal = "SELECT COUNT(*) AS total FROM applicants";
$resultTotal = $conn->query($sqlTotal);

if ($resultTotal->num_rows > 0) {
    $rowTotal = $resultTotal->fetch_assoc();
    $totalApplications = $rowTotal['total'];
}

// Query to get total number of approved applications
$sqlApproved = "SELECT COUNT(*) AS approved FROM applicants WHERE situation = 'Approved'";
$resultApproved = $conn->query($sqlApproved);

if ($resultApproved->num_rows > 0) {
    $rowApproved = $resultApproved->fetch_assoc();
    $approvedApplications = $rowApproved['approved'];
}

// Now you can safely use $totalApplications and $approvedApplications
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/media.css">
    <title>माझी लाडकी बहीण योजना</title>
    <link rel="website icon" type="png" href="images/mainLogo.png" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- https://ladakibahin.maharashtra.gov.in/ -->
    <!-- Starting project  -->

    <!-- Splash screen animation for entering website -->

    <div class="intro">
        <div class="logo-header">
            <img src="images/mainLogo.png" alt="">
            <h4>माझी लाडकी बहीण योजना</h4>
        </div>
    </div>

    <!-- Main website home Page  -->

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

    <!-- Helpline number navbar  -->
     <section class="helpline">
        <h3><i class="bi bi-telephone-outbound"></i>हेल्पलाइन टोल फ्री संपर्क क्रमांक : <a href="tel:+919403827471">9403827471</a></h3>
        <a href="complaint.html">त्रुटी नोंदवा</a>
     </section>

     <!-- Navbar  -->
     
    <section class="navbar">
        <button class="menu-btn"><i class="bi bi-justify"></i></button>
        <div class="nav">
            <a href="index.html"><i class="bi bi-house-fill"></i>मुखपृष्ठ</a>
            <a href="application.php"><i class="bi bi-file-earmark-fill"></i>मुख्यमंत्री - माझी लाडकी बहीण योजनेचा अर्ज</a>  
            <!-- <a href="info.html"><i class="bi bi-info-circle-fill"></i>योजना</a>   -->
            <a href="view.php"><i class="bi bi-file-earmark"></i>यापूर्वी केलेले अर्ज</a> 
            <!-- <a href="impDoc.html"><i class="bi bi-file-earmark-text-fill"></i>आवश्यक कागदपत्रे</a>  -->
            <a href="profile.php"><i class="bi bi-person-check"></i>प्रोफाइल</a>
            <a href="index.html"><i class="bi bi-box-arrow-right"></i> Logout</a>  
            <a href="admin_login.php"><i class="bi bi-person-fill"></i>ॲडमिन लॉगिन</a>
        </div>
    </section>


    <!-- hero section -->
    <section class="hero">    
        <div class="hero-slider">
            <img src="images/hero.jpeg" alt="">
            <img src="images/scheme.jpg" alt="">
            <!-- for sliding effect add more images as you want  -->
            <!--  -->
        </div>
    </section>
    
    <!-- information section  -->
    
    <section class="container">
    <div class="stat">
        <p>पोर्टलवर प्राप्त अर्जाची एकूण संख्या</p>
        <p><?php echo $totalApplications; ?></p>
    </div>
    <div class="stat">
        <p>पोर्टलवर मंजूर अर्जाची एकूण संख्या</p>
        <p><?php echo $approvedApplications; ?></p>
    </div>
</section>

    
    <!-- Video message box  -->
    
    <section class="basket">
        <div class="data">
            <h1>मुख्यमंत्री - माझी लाडकी बहीण योजना</h1>
            <div class="spans">
                <span></span>
            </div>
            <div class="para">
                <p>राज्यातील महिलांच्या आर्थिक स्वातंत्र्यासाठी, त्यांच्या आरोग्य आणि पोषणामध्ये सुधारणा करणे आणि कुटुंबातील त्यांची निर्णायक भूमिका मजबूत करण्यासाठी महाराष्ट्र राज्याची "मुख्यमंत्री माझी लाडकी बहीण" योजना सुरु करण्यास महाराष्ट्र शासनाने २८ जून २०२४ रोजी मान्यता दिली. या योजनेमार्फत महाराष्ट्र राज्यातील २१ ते ६५ वयोगटातील पात्र महिलांना दर महिना रु. १,५००/- असा आर्थिक लाभ DBT द्वारे देण्यात येणार आहे.</p>
            </div>
            <a href="info.html">अधिक जाणून घ्या<i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="video-box">
            <video src="video/hero-video.mp4" controls muted></video>
        </div>
    </section>
    
    <!-- Questions and answers part -->
    
    <!-- <section class="queries">
        <h1>सामान्य प्रश्न</h1>
        <div class="spans">
            <span></span>
        </div>
        <div class="criteria">
            <div class="eligibility">
                <h3>पात्रता</h3>
                <i class="bi bi-box"></i>
            </div>
            <div class="eligibility">
                <h3>अपात्रता</h3>
                <i class="bi bi-box"></i>
            </div>
            <div class="eligibility">
                <h3>अर्जप्रक्रिया</h3>
                <i class="bi bi-box"></i>
            </div>
        </div>
        
        <div class="solution">
            <ul>
                <li>महाराष्ट्र राज्याचे रहिवाशी असणे आवश्यक.</li>
                <li>राज्यातील विवाहीत, विधवा, घटस्फोटीत, परित्यक्ता आणि निराधार महिला तसेच कुटुंबातील केवळ एक अविवाहित महिला.</li>
                <li>किमान वयाची २१ वर्षे पूर्ण व कमाल वयाची ६५ वर्ष पूर्ण होईपर्यंत.</li>
                <li>लाभार्थ्याचे स्वतःचे आधार लिंक असलेले बँक खाते असावे.</li>
                <li>लाभार्थी कुटुंबाचे वार्षिक उत्पन्न रु. २.५० लाखापेक्षा जास्त नसावे.</li>
            </ul>
        </div>
    </section> -->

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
    
</body>
</html>