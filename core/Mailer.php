<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../vendor/PHPMailer/SMTP.php';
require_once __DIR__ . '/../vendor/PHPMailer/Exception.php';

class Mailer {

    public static function send($to, $subject, $body) {

        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'mail.rpufu.daw.ssmr.ro';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'rpufussm@rpufu.daw.ssmr.ro';
            $mail->Password   = 'parola';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('rpufussm@rpufu.daw.ssmr.ro', 'CinemaBibanu');
            $mail->addAddress($to);

            $mail->isHTML(false);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true;

        } catch (Exception $e) {
            return false;
        }
    }
}
