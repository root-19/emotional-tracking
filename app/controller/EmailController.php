<?php

namespace root_dev\Controller;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use root_dev\Models\User;

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../../vendor/autoload.php';

class EmailController {

    // Function to send a general email
    private function sendMail($toEmail, $toName, $subject, $body) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'hperformanceexhaust@gmail.com';
            $mail->Password = 'wolv wvyy chhl rvvm';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            //Recipients
            $mail->setFrom('your-email@gmail.com', 'QUASAR');
            $mail->addAddress($toEmail, $toName);  

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    // Function to send daily meditation reminder email
    public function sendDailyReminderEmails() {
        $user = new User();
        $allUsers = $user->getAllUsers(); 
        
        foreach ($allUsers as $singleUser) {
            $toEmail = $singleUser['email'];
            $username = $singleUser['username'];
            
            $subject = "Daily Meditation Reminder";
            $body = "
                <h2>Hi $username,</h2>
                <p>It's time for your daily meditation session! 🌱</p>
                <p>Stay calm and mindful.</p>
                <br>
                <strong>- Meditation Team</strong>
            ";

            if ($this->sendMail($toEmail, $username, $subject, $body)) {
                echo "Reminder email sent to: $username ($toEmail)<br>";
                $user->updateNotificationStatus($singleUser['id']); 
            } else {
                echo "Failed to send reminder email to: $username ($toEmail)<br>";
            }
        }
    }

    // Function to send registration confirmation email
    public function sendRegistrationConfirmation($toEmail, $toName) {
        $subject = "Welcome to the Meditation System!";
        $body = "
            <h2>Hi $toName,</h2>
            <p>Welcome to our Meditation System. We're excited to have you on board!</p>
            <p>Stay tuned for daily meditation reminders. 🌱</p>
            <br>
            <strong>- Meditation Team</strong>
        ";

        return $this->sendMail($toEmail, $toName, $subject, $body);
    }
}

?>
