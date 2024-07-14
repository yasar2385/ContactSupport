<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

//use Dotenv\Dotenv;

// Load .env file
//$dotenv = Dotenv::createImmutable(__DIR__);
//$dotenv->load();

$envFile = __DIR__ . '/.env';

echo "<script>console.log('file loading');</script>";

if (file_exists($envFile)) {
    $envContents = file_get_contents($envFile);
    $lines = explode("\n", $envContents);
	echo "<script>console.log('file found');</script>";
    foreach ($lines as $line) {
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $value = trim($parts[1]);
            putenv("$key=$value");
        }
    }
} else {
	echo "<script>console.log('file not found');</script>";
}

// Retrieve environment variables
$mailUser = getenv('FROM_MAIL');
$appPassword = getenv('APP_PASSWORD');
$mailReceiver = getenv('TO_MAIL');

// Now you can use $mailUser and $appPassword in your application
//echo "<script>console.log('User: ". $mailUser ."');</script>";


$mail = new PHPMailer(true);


function sendEmail($from, $to, $name, $subject, $body) {	
	global $mailUser;
	global $appPassword;
	//echo "<script>console.log('User: ". $mailUser ."');</script>";
    try {
        $mail = new PHPMailer(true); // Set to true to enable exceptions
        // Server settings (adjust these as needed)
        $mail->SMTPDebug = 0; // Enable verbose debug output (optional)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;

        // Credentials (use app password, not your regular Gmail password)
        $mail->Username = $mailUser;
        $mail->Password = $appPassword; // Replace with your app password

        // Email content
        $mail->setFrom($from, $name);
        $mail->addAddress($to, 'RecipientName');
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->isHTML(true); // Set to true if sending HTML content

        $mail->send();
        echo 'Email sent successfully!';
    } catch (Exception $e) {
        echo "Error sending email: {$mail->ErrorInfo}";
        http_response_code(500);  // Set error status code
    }
}	


echo "<script>console.log('===REQUEST_METHOD===');</script>";

// Check if request is POST and has necessary parameters
if ($_SERVER['REQUEST_METHOD'] === 'POST'&& isset($_POST['subject'], $_POST['message'])) {	
    $from = $mailUser;
    $to = $mailReceiver;
    $subject = $_POST['subject'];
    $body = ($_POST['message'].'<br><br>User mail : '.$_POST['email'].'<br>User name : '.$_POST['name']);
	$name = $_POST['name'];
	sendEmail($from, $to, $name, $subject, $body);
	//echo "<script>console.log('".($body)."');</script>";
} else {
	echo "<script>console.log('Invalid request');</script>";
    http_response_code(400);  // Set bad request status code    
	echo 'Invalid request';
}
?>