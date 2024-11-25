<?php
header('Content-Type: application/json');

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "stap1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed: " . $conn->connect_error]));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Debugging: Print received data
    error_log("Received email: $email");
    error_log("Received password: $password");

    // Check if the email exists
    $sql = "SELECT * FROM student WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Successful login
            echo json_encode(["success" => true, "message" => "Login successful."]);
        } else {
            // Invalid password
            echo json_encode(["success" => false, "message" => "Invalid password."]);
        }
    } else {
        // Email not found
        echo json_encode(["success" => false, "message" => "No user found with this email."]);
    }

    $stmt->close();
}

$conn->close();
?>