<?php
require 'config.php';
header("Content-Type: application/json");

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed"]);
    exit();
}
$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['trip_id'])) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "trip_id is required"]);
    exit();
}

$trip_id = intval($data['trip_id']);

$sql = "SELECT u.PHONE_NUMBER 
        FROM trip t
        JOIN users u ON t.MANAGER_ID = u.USER_ID
        WHERE t.TRIP_ID = ? 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $trip_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode([
        "status" => "success",
        "PHONE_NUMBER" => $row["PHONE_NUMBER"]
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Manager not found"
    ]);
}

$stmt->close();
$conn->close();
?>
