<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require 'db.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["error" => "Invalid request method"]);
    exit;
}

// Check if required fields are provided
if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['password'])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$contact_num = $_POST['contact_num'] ?? '';
$address = $_POST['address'] ?? '';

$stmt = $conn->prepare("INSERT INTO users (username, email, password, contact_num, address) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $email, $password, $contact_num, $address);

if ($stmt->execute()) {
    echo json_encode(["message" => "User registered successfully"]);
} else {
    echo json_encode(["error" => "Registration failed"]);
}
?>
