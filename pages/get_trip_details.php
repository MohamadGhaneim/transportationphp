<?php

require 'config.php';
header('Content-Type: application/json');

if (!isset($_POST['trip_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'trip_id is required']);
    exit;
}

$tripId = $_POST['trip_id'];

$sql = "
    SELECT 
        b.BOOK_ID,
        b.USER_ID,
        b.USER_LOCATION,
        b.DESTINATION_LOCATION,
        b.PAYMENT,
        b.CHECK_PAY,
        u.USER_EMAIL,
        u.FULL_NAME,
        u.PHONE_NUMBER,
        u.USER_PHOTO
    FROM book b
    JOIN users u ON b.USER_ID = u.USER_ID
    WHERE b.TRIP_ID = ?
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $tripId);
$stmt->execute();
$result = $stmt->get_result();

$passengers = [];

while ($row = $result->fetch_assoc()) {
    $passengers[] = $row;
}

echo json_encode($passengers, JSON_UNESCAPED_UNICODE);
$stmt->close();
$conn->close();
