<?php
// echo "high this is me";
// echo $fullname . " | ". $email . " | " . $body . " | " .$subject;
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer/src/Exception.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();                                    //Send using SMTP
    $mail->Host       = 'smtp.gmail.com';               //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                           //Enable SMTP authentication
    $mail->Username   = 'mastersushifs@gmail.com';  //SMTP email
    
    // New Password
    $mail->Password   = 'ytbk vqkx clwi fllv';             //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;    //Enable implicit TLS encryption
    $mail->Port       = 465;                            //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($headers,'New Order');
    $mail->addAddress($to_receiver);   // sent to the admin email address (admin@thriivetank.com).

    //Content
    $mail->isHTML(true);                                //Set email format to HTML
    $mail->Subject = $subject;
    $mail->Body    = $message;

    $mail->send();
    
    $notif = 'Email sent to Admin Email Address';
    // $notif = 'Email sent to Admin Email Address';
} catch (Exception $e) {
    $errors_captcha = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>