<?php
require 'config.php';
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$USER_ID = $data['USER_ID'] ;
$TRIP_ID = $data['TRIP_ID'] ;
$USER_LOCATION = $data['USER_LOCATION'] ;
$DESTINATION_LOCATION = $data['DESTINATION_LOCATION'];
$payment = $data['payment']; /// its double

if (!$USER_ID || !$TRIP_ID || !$USER_LOCATION || !$DESTINATION_LOCATION || !$payment) {
    echo json_encode([
        "status" => "fail",
        "message" => "Missing or invalid data",
        "data" => $data
    ]);
    exit;
}

$check_seat = "SELECT b.CAPACITY, COUNT(bk.BOOK_ID) AS booked_seats
FROM driver_captin_trip AS dct
JOIN bus AS b ON dct.BUS_ID = b.BUS_ID
JOIN book AS bk ON bk.TRIP_ID = dct.TRIP_ID
WHERE bk.TRIP_ID = ?
GROUP by b.CAPACITY;
";

$stmt = $conn->prepare($check_seat);
$stmt->bind_param("i", $TRIP_ID);
$stmt->execute();


$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    $capacity = (int)$row['CAPACITY'];
    $bookedSeats = (int)$row['booked_seats'];

    if ($bookedSeats >= $capacity) {
        echo json_encode([
            "status" => "full",
            "message" => "No seats available",
            "booked" => $bookedSeats,
            "capacity" => $capacity
        ]);
    } else {
        echo json_encode([
            "status" => "available",
            "message" => "Seats available",
            "booked" => $bookedSeats,
            "capacity" => $capacity
        ]);
    }
} else {
    echo json_encode([
        "status" => "not_found",
        "message" => "Trip not found or no bookings yet"
    ]);
}


$stmt = $conn->prepare("
    INSERT INTO book (
        USER_ID, TRIP_ID, USER_LOCATION, DESTINATION_LOCATION , payment
    ) VALUES (?, ?, ?, ? , ?)
");

$stmt->bind_param(
    "iissd",
    $USER_ID,
    $TRIP_ID,
    $USER_LOCATION,
    $DESTINATION_LOCATION ,
    $payment
);
echo $RETURN_DATE_TIME;
if ($stmt->execute()) {
    http_response_code(200);
    echo json_encode(["status" => "success"]);
} else {
    http_response_code(500);
    echo json_encode([
        "status" => "fail",
        "error" => $stmt->error,
        "data" => $data
    ]);
}

$stmt->close();
$conn->close();
?>
