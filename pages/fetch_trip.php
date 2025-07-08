<?php
require 'config.php';

$sql = "SELECT * FROM trip WHERE STATUS_TRIP in ('Pending','Active')";
$result = $conn->query($sql);

$trips = [];

while ($row = $result->fetch_assoc()) {
    $tripId = $row['TRIP_ID'];

    $location_sql = "SELECT SPECIFIC_LOCATION FROM trip_specific_location WHERE TRIP_ID = ?";
    $stmt = $conn->prepare($location_sql);
    $stmt->bind_param("i", $tripId);
    $stmt->execute();
    $location_result = $stmt->get_result();

    $locations = [];
    while ($loc_row = $location_result->fetch_assoc()) {
        $locations[] = $loc_row['SPECIFIC_LOCATION'];
    }

    $row['SPECIFIC_LOCATIONS'] = $locations;
    $trips[] = $row;

    $stmt->close();
}

echo json_encode($trips, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
