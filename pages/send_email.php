<?php
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
    
$mail = new PHPMailer(true);
$destination =  mysqli_real_escape_string($conn,$_POST['to']);
$subject = "Email Verification - Transportation App";
$newpassword = mysqli_real_escape_string($conn,$_POST['newpassword']);
$new_hash_password = mysqli_real_escape_string($conn,$_POST['newhashpassword']);


try {
    $check_sql = "SELECT * FROM users WHERE USER_EMAIL = ? ";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $destination);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

if ($result->num_rows != 1) {
    $check_stmt->close();
    $conn->close();
    http_response_code(401);
    return ;
    exit ;
   }
    $stmt = $conn->prepare("UPDATE users SET PASSWORD = ? WHERE USER_EMAIL = ?");
    $stmt->bind_param("ss", $new_hash_password, $destination);

    if ($stmt->execute()) {
        echo "Reset code updated successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

        $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // أو smtp.mailgun.org
    $mail->SMTPAuth   = true;
    $mail->Username   = 'mohamadghanem334@gmail.com';
    $mail->Password   = 'zdbi njfb kzyb ahmu'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port       = 587;

    $mail->setFrom('mohamadghanem334@gmail.com', 'Transportation');
    $mail->addAddress($destination);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    =  "<p>Hello dear </p>
  <p>You requested to reset your password</p>
  <p>Your verification code is:</p>
  <h2>$newpassword</h2>
  <p>don't shear this with anyone</p>
  <br>
  <p>— Transportation App Team</p>";

    $mail->send();
    echo "Email sent successfully";
} catch (Exception $e) {
    echo "Failed: {$mail->ErrorInfo}";
}
?>