<?php
require 'config.php';

$USER_ID = $data['USER_ID'];
$TRIP_ID = $data['TRIP_ID'];

$checkQuery = "SELECT * FROM book WHERE USER_ID = ? AND TRIP_ID = ?";
$checkStmt = $conn->prepare($checkQuery);
$checkStmt->bind_param('ss', $USER_ID, $TRIP_ID);
$checkStmt->execute();
$result = $checkStmt->get_result();

if ($result->num_rows > 0) {
    $deleteQuery = "DELETE FROM book WHERE USER_ID = ? AND TRIP_ID = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param('ss', $USER_ID, $TRIP_ID);

    if ($deleteStmt->execute()) {
        http_response_code(200); 
        echo json_encode(['status' => 'deleted']);
    } else {
        http_response_code(500); 
        echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
    }
} else {

    http_response_code(404);
    echo json_encode(['status' => 'not_found']);
}
?>
