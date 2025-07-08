<?php
require 'config.php'; 
//header('Content-Type: application/json');
$email = $_POST['email'];
$password = $_POST['password'];

if (!isset($_POST['email'], $_POST['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing email or password']);
    exit();
} 

$sql = "SELECT * FROM users WHERE USER_EMAIL = ? AND PASSWORD = ? AND provider = 'local'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $response = [
        'USER_ID' => $user['USER_ID'],
        'USER_EMAIL' => $user['USER_EMAIL'],
        'PHONE_NUMBER' => $user['PHONE_NUMBER'],
        'TYPE_ID' => $user['TYPE_ID'],
        'FULL_NAME' => $user['FULL_NAME'],
        'provider' => $user['provider'] ,
        'USER_PHOTO' => $user['USER_PHOTO'] 
    ];

    header('Content-Type: application/json');
    echo json_encode($response);
    http_response_code(200);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid email or password']);
}

$stmt->close();
$conn->close();
?>
