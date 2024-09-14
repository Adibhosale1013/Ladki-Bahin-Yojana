<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || !$_SESSION['admin_logged_in']) {
    header("Location: admin_login.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$servername = "localhost";
$username = "root";
$password = "";
$database = "ladki_bahin_yojana";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = '';

// Function to send email using PHPMailer
function sendEmail($to, $subject, $message) {
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';  // Set the SMTP server to send through
        $mail->SMTPAuth   = true;
        $mail->Username   = 'adibhosale1013@gmail.com';  // SMTP username
        $mail->Password   = 'pijvgzphuklppiye';  // SMTP password
        $mail->SMTPSecure = 'ssl';
        $mail->Port       = 465;

        //Recipients
        $mail->setFrom('adibhosale1013@gmail.com', 'Admin');
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

// Handle approval or rejection
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['applicant_id']) && isset($_POST['action'])) {
        $applicantId = $_POST['applicant_id'];
        $action = $_POST['action'];
        $status = ($action == 'approve') ? 'Approved' : 'Rejected';
        $rejectionReason = '';

        // Fetch the applicant details from the database
        $stmt = $conn->prepare("SELECT full_name, email_id, scheme FROM applicants WHERE id = ?");
        $stmt->bind_param("i", $applicantId);
        $stmt->execute();
        $result = $stmt->get_result();
        $applicant = $result->fetch_assoc();
        $stmt->close();

        if ($action == 'approve') {
            $rejectionReason = 'Approved successfully'; // Set approval reason by default
        } elseif ($action == 'reject' && isset($_POST['rejection_reason'])) {
            $rejectionReason = $_POST['rejection_reason'];
        }

        // Update the application status and rejection reason in the database
        $stmt = $conn->prepare("UPDATE applicants SET situation = ?, rejection_reason = ? WHERE id = ?");
        $stmt->bind_param("ssi", $status, $rejectionReason, $applicantId);
        if ($stmt->execute()) {
            $message = "Application $status successfully";

            // Prepare email content
            $emailSubject = " Update";
            $emailBody = "Dear " . htmlspecialchars($applicant['full_name']) . ",<br><br>";
            $emailBody .= "Your application for the scheme \"माझी लाडकी बहीण योजना\" has been " . htmlspecialchars($status) . ".<br>";            $emailBody .= "Status: " . htmlspecialchars($status) . "<br>";
            $emailBody .= "Reason/Feedback: " . htmlspecialchars($rejectionReason) . "<br><br>";
            $emailBody .= "Best regards,<br>Your Team";

            // Send the email using PHPMailer
            $emailResult = sendEmail($applicant['email_id'], $emailSubject, $emailBody);
            if ($emailResult !== true) {
                $message .= " (Email not sent: $emailResult)";
            }
        } else {
            $message = "Error updating application: " . $stmt->error;
        }

        $stmt->close();
    }
}

// Handle filtering
$situationFilter = isset($_GET['situation']) ? $_GET['situation'] : '';
$query = "SELECT * FROM applicants";
if ($situationFilter) {
    $query .= " WHERE situation = ?";
}

$stmt = $conn->prepare($query);
if ($situationFilter) {
    $stmt->bind_param("s", $situationFilter);
}
$stmt->execute();
$result = $stmt->get_result();

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/media.css">
    <link rel="stylesheet" href="css/admin.css">
    <link rel="icon" type="image/png" href="images/mainLogo.png">
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

    <section class="admin">
        <h2>Admin Dashboard</h2>
        
        <!-- Display message if present -->
        <?php if ($message): ?>
            <script>
                alert('<?php echo htmlspecialchars($message); ?>');
            </script>
        <?php endif; ?>

        <!-- Filter Form -->
        <form method="GET" action="">
            <label for="situation">Filter by Status:</label>
            <select name="situation" id="situation">
                <option value="">All</option>
                <option value="Pending" <?php echo ($situationFilter == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="Approved" <?php echo ($situationFilter == 'Approved') ? 'selected' : ''; ?>>Approved</option>
                <option value="Rejected" <?php echo ($situationFilter == 'Rejected') ? 'selected' : ''; ?>>Rejected</option>
            </select>
            <button type="submit">Filter</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Husband/Parent Name</th>
                    <th>Birth Date</th>
                    <th>Aadhaar Number</th>
                    <th>Mobile Number</th>
                    <th>Email Id</th>
                    <th>District</th>
                    <th>Village/City</th>
                    <th>Panchayat</th>
                    <th>Benefit Status</th>
                    <th>Scheme</th>
                    <th>Marital Status</th>
                    <th>Birth Place Status</th>
                    <th>Bank Name</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>IFSC Code</th>
                    <th>Aadhaar Linked to Bank</th>
                    <th>Aadhaar Card</th>
                    <th>ID Card</th>
                    <th>Income Card</th>
                    <th>Acceptance Card</th>
                    <th>Passbook</th>
                    <th>Other State Document</th>
                    <th>Photo</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['husband_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['birth_date']); ?></td>
                        <td><?php echo htmlspecialchars($row['aadhaar_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['mobile_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['email_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['district']); ?></td>
                        <td><?php echo htmlspecialchars($row['village']); ?></td>
                        <td><?php echo htmlspecialchars($row['panchayat']); ?></td>
                        <td><?php echo htmlspecialchars($row['benefit_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['scheme']); ?></td>
                        <td><?php echo htmlspecialchars($row['marital_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['birth_place_status']); ?></td>
                        <td><?php echo htmlspecialchars($row['bank_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['account_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['ifsc_code']); ?></td>
                        <td><?php echo htmlspecialchars($row['aadhaar_linked_to_bank']); ?></td>
                        <td><a href="<?php echo htmlspecialchars($row['aadhar_card_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['id_card_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['income_card_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['acceptance_card_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['passbook_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['other_state_path']); ?>" target="_blank">View</a></td>
                        <td><a href="<?php echo htmlspecialchars($row['photo_path']); ?>" target="_blank">View</a></td>
                        <td><?php echo htmlspecialchars($row['situation']); ?></td>
                        <td>
                            <form method="POST" action="">
                                <input type="hidden" name="applicant_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                <button type="submit" name="action" value="approve">Approve</button>
                                <button type="button" onclick="openRejectionModal(<?php echo htmlspecialchars($row['id']); ?>)">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="dashboard.php" class="btn">Logout</a>
    </section>

<br>
<br>
<br>
<br>
    <footer>
    <section class="footer">
        <div class="mainAdd">
            <h3>Created By RAY TEAM(<a>Rohit</a>,<a href="https://www.linkedin.com/in/aditya-bhosale-624335288/" target="_blank">Aditya</a>,<a>Yojana</a>)</h3>
            <p>@Fortune Cloud Technology (Project Fest Competition 2024)</p>
        </div>

        <div class="copyrights">
            <p>Copyright © 2024 Aditya, Rohit, Yojana. All rights reserved.</p>
        </div>
    </section>
    </footer>
    
    <!-- Rejection Modal -->
    <div id="rejectionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRejectionModal()">&times;</span>
            <h2>Reject Application</h2>
            <form id="rejectionForm" method="POST" action="">
                <input type="hidden" name="applicant_id" id="applicant_id">
                <input type="hidden" name="action" value="reject">
                <textarea name="rejection_reason" required placeholder="Enter the reason for rejection"></textarea>
                <button type="submit">Submit</button>
            </form>
        </div>
    </div>

    <script>
        function openRejectionModal(applicantId) {
            document.getElementById('applicant_id').value = applicantId;
            document.getElementById('rejectionModal').style.display = 'block';
        }

        function closeRejectionModal() {
            document.getElementById('rejectionModal').style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('rejectionModal')) {
                document.getElementById('rejectionModal').style.display = 'none';
            }
        }


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

<?php
$stmt->close();
$conn->close();
?>
