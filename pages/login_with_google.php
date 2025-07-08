<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); 
    echo "Only POST method is allowed.";
    exit;
}

$email = $_POST['email'] ?? '';
$fullname = $_POST['fullname'] ?? '';
$provider = 'google';
$type_id = 3;

if (empty($email) || empty($fullname)) {
    http_response_code(400); 
    echo "empty";
    $conn->close();
    exit;
}

$check_sql = "SELECT provider FROM users WHERE USER_EMAIL = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $email);
$check_stmt->execute();
$check_stmt->store_result();

if ($check_stmt->num_rows > 0) {
    $check_stmt->bind_result($check_provider);
    $check_stmt->fetch();

    if ($check_provider === 'provider') {
        http_response_code(200); 
        echo $email;
    } else {
        http_response_code(401); 
    }

    $check_stmt->close();
    $conn->close();
    exit;
} else {
    http_response_code(404); 
    $check_stmt->close();
    $conn->close();
    exit;
}

$check_stmt->close();

$insert_sql = "INSERT INTO users (USER_EMAIL, TYPE_ID, FULL_NAME, provider) VALUES (?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("siss", $email, $type_id, $fullname, $provider);

if ($insert_stmt->execute()) {
    http_response_code(200);
    echo "SUCCESS";
} else {
    http_response_code(500); 
    echo "FAILED: " . $conn->error;
}

$insert_stmt->close();
$conn->close();
?>
