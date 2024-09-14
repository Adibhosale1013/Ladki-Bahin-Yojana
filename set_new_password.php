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

    // Get form data
    $adharNo = isset($_POST['adharNo']) ? trim($_POST['adharNo']) : '';
    $newPassword = isset($_POST['newPassword']) ? trim($_POST['newPassword']) : '';
    $confirmNewPassword = isset($_POST['confirmNewPassword']) ? trim($_POST['confirmNewPassword']) : '';

    // Validate new passwords
    if ($newPassword !== $confirmNewPassword) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Hash the new password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $conn->prepare("UPDATE users SET pass = ? WHERE adharNo = ?");
        $stmt->bind_param("ss", $hashedPassword, $adharNo);

        if ($stmt->execute()) {
            echo "<script>alert('Password updated successfully.');</script>";
            header("Location: login.php");
            exit(); // Ensure no further code is executed
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>
