<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require 'db.php';

$user_id = $_GET['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode(["error" => "User ID is required"]);
    exit;
}

$stmt = $conn->prepare("SELECT id, username, email, contact_num FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode([
        "id" => $user["id"],
        "username" => $user["username"],
        "email" => $user["email"],
        "contact_num" => $user["contact_num"]
    ]);
} else {
    echo json_encode(["error" => "User not found"]);
}
?>
