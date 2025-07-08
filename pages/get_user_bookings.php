<?php
require 'config.php';

header('Content-Type: application/json');

if (!isset($_POST['user_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'User ID is required']);
    exit;
}

$user_id = $_POST['user_id'];

$sql = "
    SELECT t.*
    FROM trip t
    INNER JOIN book b ON t.TRIP_ID = b.TRIP_ID
    WHERE b.USER_ID = ?
    ORDER BY t.TRIP_DATE DESC, t.DEPARTURE_TIME ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$trips = [];

while ($row = $result->fetch_assoc()) {
    $trips[] = [
        "TRIP_ID" => $row["TRIP_ID"],
        "TRIP_DATE" => $row["TRIP_DATE"],
        "DEPARTURE_TIME" => $row["DEPARTURE_TIME"],
        "RETURN_TIME" => $row["RETURN_TIME"],
        "SEAT_PRICE" => $row["SEAT_PRICE"],
        "STATUS_TRIP" => $row["STATUS_TRIP"],
        "MANAGER_ID" => $row["MANAGER_ID"],
        "DESTINATION_LOCATION" => $row["DESTINATION_LOCATION"],
        "DEPARTURE_LOCATION" => $row["DEPARTURE_LOCATION"]
    ];
}

echo json_encode($trips);

$stmt->close();
$conn->close();
