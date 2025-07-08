<?php

require 'config.php';

header('Content-Type: application/json');

if (!isset($_POST['driver_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'driver_id is required']);
    exit;
}

$driverId = $_POST['driver_id'];

$sql = "
    SELECT
     t.TRIP_ID,
    t.MANAGER_ID,
    t.TRIP_DATE,
    t.DEPARTURE_LOCATION,
    t.DEPARTURE_COORDS,
    t.DESTINATION_LOCATION,
    t.DEPARTURE_TIME,
    t.RETURN_TIME,
    t.SEAT_PRICE,
    t.STATUS_TRIP,
    GROUP_CONCAT(tsl.SPECIFIC_LOCATION) AS SPECIFIC_LOCATIONS
    FROM trip t
    JOIN driver_captin_trip dct ON t.TRIP_ID = dct.TRIP_ID
    LEFT JOIN trip_specific_location tsl ON t.TRIP_ID = tsl.TRIP_ID
    WHERE dct.DRIVER_ID = ?
      AND t.STATUS_TRIP IN ('Active', 'Pending')
    GROUP BY 
        t.TRIP_ID,
        t.MANAGER_ID,
        t.TRIP_DATE,
        t.DEPARTURE_COORDS,
        t.DEPARTURE_LOCATION,
        t.DESTINATION_LOCATION,
        t.DEPARTURE_TIME,
        t.RETURN_TIME,
        t.SEAT_PRICE,
        t.STATUS_TRIP
    ORDER BY 
        FIELD(t.STATUS_TRIP, 'Active', 'Pending'),
        t.TRIP_DATE DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $driverId);
$stmt->execute();
$result = $stmt->get_result();

$trips = [];

while ($row = $result->fetch_assoc()) {
    $specificLocations = isset($row['SPECIFIC_LOCATIONS']) && $row['SPECIFIC_LOCATIONS'] !== null
        ? explode(',', $row['SPECIFIC_LOCATIONS'])
        : [];

    $trips[] = [
        'TRIP_ID' => $row['TRIP_ID'],
        'MANAGER_ID' => $row['MANAGER_ID'],
        'TRIP_DATE' => $row['TRIP_DATE'],
        'DEPARTURE_LOCATION' => $row['DEPARTURE_LOCATION'],
        'DEPARTURE_COORDS' => $row['DEPARTURE_COORDS'],
        'DESTINATION_LOCATION' => $row['DESTINATION_LOCATION'],
        'DEPARTURE_TIME' => $row['DEPARTURE_TIME'],
        'RETURN_TIME' => $row['RETURN_TIME'],
        'SEAT_PRICE' => $row['SEAT_PRICE'],
        'STATUS_TRIP' => $row['STATUS_TRIP'],
        'SPECIFIC_LOCATIONS' => $specificLocations,
    ];
}

echo json_encode($trips, JSON_UNESCAPED_UNICODE);
$stmt->close();
$conn->close();
