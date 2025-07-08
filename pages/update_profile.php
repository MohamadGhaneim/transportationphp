<?php
require 'config.php';

header("Content-Type: application/json; charset=UTF-8");

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['userId']) ||
    !isset($data['email']) ||
    !isset($data['phoneNumber']) ||
    !isset($data['fullName']) ||
    !isset($data['path_photo'])  
) {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit();
}

$userId = intval($data['userId']);
$email = $conn->real_escape_string(trim($data['email']));
$phone = $conn->real_escape_string(trim($data['phoneNumber']));
$fullName = $conn->real_escape_string(trim($data['fullName']));
$photoPath = $conn->real_escape_string(trim($data['path_photo'])); 

$check_sql = "SELECT * FROM users WHERE (USER_EMAIL = ? OR PHONE_NUMBER = ?) AND USER_ID != ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ssi", $email, $phone, $userId);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(["error" => "Email or phone number is already in use"]);
    $check_stmt->close();
    $conn->close();
    exit;
}

$update_sql = "UPDATE users SET 
  USER_EMAIL = ?, 
  PHONE_NUMBER = ?, 
  FULL_NAME = ?, 
  USER_PHOTO = ?
  WHERE USER_ID = ?";

$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("ssssi", $email, $phone, $fullName, $photoPath, $userId);

if ($update_stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
} else {
    http_response_code(500);
    echo json_encode(["error" => "Update failed: " . $conn->error]);
}

$update_stmt->close();
$conn->close();
?>
