<?php

namespace app\traits;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Trait EmailVerificationTrait
 *
 * Provides the functionality to send verification emails.
 */
trait EmailVerification
{
    /**
     * Sends a verification email to the user.
     *
     * @param string $email The email address of the user.
     * @param string $token The verification token to include in the email.
     *
     * @throws Exception If there is an error sending the email.
     */
    private function sendVerificationEmail(string $email, string $token): void
    {
        try {
            $verificationUrl = 'http://api.fewo-heider-ruegen.local/auth/verify?token=' . $token;
            $subject = "Please confirm your registration";
            $body = "Click the following link to verify your email address: " . $verificationUrl;

            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'localhost';
            $mail->SMTPAuth = false;
            $mail->Port = 1025;
            $mail->setFrom('innovastay@fewo-heider-ruegen.de', 'Innovastay');

            $mail->addAddress($email);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
        } catch (Exception $e) {
            throw new Exception("Error sending email: " . $e->getMessage());
        }
    }
}
