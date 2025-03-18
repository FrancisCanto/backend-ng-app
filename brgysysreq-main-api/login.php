<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json"); // Ensures JSON response

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($email) || empty($password)) {
    echo json_encode(["error" => "Email and password are required"]);
    exit;
}

// Prepare statement to fetch user ID and password
$stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 0) {
    echo json_encode(["error" => "Invalid credentials"]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->bind_result($user_id, $hashed_password);
$stmt->fetch();

// Verify password
if (password_verify($password, $hashed_password)) {
    echo json_encode(["user_id" => (string)$user_id]); // Ensures user_id is a string
} else {
    echo json_encode(["error" => "Invalid credentials"]);
}

// Close statement and database connection
$stmt->close();
$conn->close();
?>
