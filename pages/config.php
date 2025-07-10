<?php
$host = "db4free.net";
$user = "mohamadev";          
$password = "database123";        
$dbname = "transportationdb";  
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("field " . $conn->connect_error);
}
?>
