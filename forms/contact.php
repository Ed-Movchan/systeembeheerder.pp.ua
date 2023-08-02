<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
require 'PHPMailer\src\PHPMailer.php';
require 'PHPMailer\src\SMTP.php';
require 'PHPMailer\src\Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Sanitize and validate the user input
  $from_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
  $from_email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
  $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
  $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

   // Initialize PHPMailer
   $mail = new PHPMailer(true);

  if (
     $from_name !== null && $from_name !== '' &&
    $from_email !== null && $from_email !== false &&
    $subject !== null && $subject !== '' &&
    $message !== null && $message !== ''
  ) {
    // Validate the email address
    if (!filter_var($from_email, FILTER_VALIDATE_EMAIL)) {
      echo "Invalid email format.";
      exit; // Halt further execution if the email is invalid
    }

    // Create the email headers
    $headers = "From: $from_name <$from_email>\r\n";
    $headers .= "Reply-To: $from_email\r\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\r\n";

    // Send the email using a try-catch block to handle errors gracefully
    try {

       // Server settings
       $mail->isSMTP(); // Set mailer to use SMTP
       $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
       $mail->SMTPAuth = true; // Enable SMTP authentication
       $mail->Username = 'eduard.movchanskiy@gmail.com'; // SMTP username
       $mail->Password = 'Xv1zN44%'; // SMTP password
       $mail->SMTPSecure = 'tls'; // Enable TLS encryption, `ssl` also accepted
       $mail->Port = 587; // TCP port to connect to

       // Sender info
       $mail->setFrom($from_email, $from_name);

       // Add a recipient
       $mail->addAddress('eduard.movchanskiy@gmail.com', 'Eduard');

       // Content
       $mail->isHTML(true); // Set email format to HTML
       $mail->Subject = $subject;
       $mail->Body = "<p>Name: $from_name</p><p>Email: $from_email</p><p>Message: $message</p>";

       // Send the email
       if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'Message sent!';
    }
}
?>

