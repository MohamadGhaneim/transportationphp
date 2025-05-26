<?php
require 'config.php'; 

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE USER_EMAIL = ? AND PASSWORD = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Success";
} else {
    echo "wrong Pass or Email";
}

$stmt->close();
$conn->close();
?>
