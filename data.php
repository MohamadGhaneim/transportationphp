<?php
$host = "localhost";
$user = "root";
$password = ""; 
$dbname = "transportation";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("failed" . $conn->connect_error);
}

$sql = "SELECT * FROM user_type";
$result = $conn->query($sql);

$data = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($data);

$conn->close();
?>
