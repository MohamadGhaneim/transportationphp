<?php
// receive_token.php
require 'config.php';
header("Content-Type: application/json");



$email = $_POST['USER_EMAIL'] ?? null;
$token = $_POST['fcm_token'] ?? null;

if (!$email || !$token) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Missing email or token"]);
    exit();
}

$sql = "UPDATE users SET FCM_TOKEN = ? WHERE USER_EMAIL = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $token, $email);
$success = $stmt->execute();

if ($success && $stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Token updated"]);
} else {
    echo json_encode(["status" => "error", "message" => "User not found or token not updated"]);
}

$conn->close();
?>
