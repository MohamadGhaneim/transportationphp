<?php
require 'config.php'; // Connect to database

$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];
$fullname = $_POST['fullname'];
$type_id = 3;

$check_sql = "SELECT * FROM users WHERE USER_EMAIL = ? OR PHONE_NUMBER = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("ss", $email, $phone);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    echo "Email or phone number is already exist"; 
    $check_stmt->close();
    $conn->close();
    exit;
}

$insert_sql = "INSERT INTO users (USER_EMAIL, PHONE_NUMBER, TYPE_ID, PASSWORD, FULL_NAME) VALUES (?, ?, ?, ?, ?)";
$insert_stmt = $conn->prepare($insert_sql);
$insert_stmt->bind_param("ssiss", $email, $phone, $type_id, $password, $fullname);

if ($insert_stmt->execute()) {
    echo "SUCCESS";
} else {
    echo "FAILED: " . $conn->error;
}

$insert_stmt->close();
$conn->close();
?>
