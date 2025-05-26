<?php
$host = "localhost";
$user = "root";          
$password = "";        
$dbname = "transportation";  
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("field " . $conn->connect_error);
}
?>
