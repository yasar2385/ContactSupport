<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


// Retrieve environment variables
$mUser = $_ENV['FROM_MAIL'];
$appPassword = $_ENV['APP_PASSWORD'];
$Receiver = $_ENV['TO_MAIL'];

// Now you can use $mUser and $appPassword in your application

echo "User: $mUser\n";
echo "Password: $appPassword\n";

$mail = new PHPMailer(true);


function sendEmail($from, $to, $name, $subject, $body) {
    try {
        $mail = new PHPMailer(true); // Set to true to enable exceptions

        // Server settings (adjust these as needed)
        $mail->SMTPDebug = 2; // Enable verbose debug output (optional)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;

        // Credentials (use app password, not your regular Gmail password)
        $mail->Username = 'yaztechinnovations@gmail.com';
        $mail->Password = 'your_app_password'; // Replace with your app password

        // Email content
        $mail->setFrom($from, $name);
        $mail->addAddress($to, 'RecipientName');
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(false); // Set to true if sending HTML content

        $mail->send();
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
        http_response_code(500);  // Set error status code
    }
}

	echo "<script>console.log('===PHP===');</script>";

// Check if request is POST and has necessary parameters
if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['subject'], $_POST['message'])) {
	
	// $_POST['from'], $_POST['to'],
    $from = '';
    $to = '';
    $subject = $_POST['subject'];
    $body = $_POST['message'].' '.$_POST['email'];
	$name = $_POST['name'];
    //sendEmail($from, $to, $name, $subject, $body);
	echo "<script>console.log('PHP: " . ($body) . "');</script>";
	echo "<script>console.log('PHP: " . ($_POST['message'].' \n '.$_POST['email']) . "');</script>";
} else {
    http_response_code(400);  // Set bad request status code
    echo 'Invalid request';
}
?>