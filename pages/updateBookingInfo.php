<?php
require 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$USER_ID = $data['USER_ID'] ;
$TRIP_ID = $data['TRIP_ID'] ;
$USER_LOCATION = $data['USER_LOCATION'] ;
$DESTINATION_LOCATION = $data['DESTINATION_LOCATION'];

$sqlQuery = " SELECT * FROM book WHERE USER_ID=? , TRIP_ID =? ";
$query = $conn->prepare($sqlQuery);
$query->bind_param('ss',$USER_ID, $TRIP_ID);
$query->execute();
$result = $query->get_result();

if($result->num_rows > 0){
    $updateQuery = "UPDATE book SET USER_LOCATION = ?, DESTINATION_LOCATION = ? WHERE USER_ID = ? AND TRIP_ID = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('ssss', $DEPARTURE_TIME, $RETURN_TIME, $USER_ID, $TRIP_ID);
    
    if ($updateStmt->execute()) {
        http_response_code(200); 
        echo json_encode(['status' => 'success']);
    } else {
        http_response_code(500); 
        echo json_encode(['status' => 'error']);
    }
    exit;
}



?>