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
if (empty($_POST['username']) || empty($_POST['address']) || empty($_POST['purpose']) || empty($_POST['contact_num']) || empty($_POST['docu'])) {
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

$username = trim($_POST['username']);
$address = trim($_POST['address']);
$purpose = trim($_POST['purpose']);
$contact_num = trim($_POST['contact_num']);
$docu = trim($_POST['docu']);

$stmt = $conn->prepare("INSERT INTO requests (username, address, purpose, contact_num, docu) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $username, $address, $purpose, $contact_num, $docu);

if ($stmt->execute()) {
    echo json_encode(["message" => "User requested successfully"]);
} else {
    echo json_encode(["error" => "Request failed", "sql_error" => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
