<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // เปิดการ Debug
    $mail->SMTPDebug = 2;
    $mail->Debugoutput = 'html';

    // ตั้งค่าการเชื่อมต่อ SMTP
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'harry.sgy24@gmail.com'; // ใส่อีเมลของคุณ
    $mail->Password = 'flue swzk aqjt hhgg';   // ใส่ App Password
    $mail->SMTPSecure = "tls";
    $mail->Port = 587;

    // ตั้งค่าผู้ส่งและผู้รับ
    $mail->setFrom('harry.sgy24@gmail.com', 'Sender Name');
    $mail->addAddress('jakkritu65@nu.ac.th', 'Receive');

    // ตั้งค่าอีเมล
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = '<h1>Hello, this is a test email! </h1>';
    $mail->AltBody = 'Hello, this is a test email!';

    // ส่งอีเมล
    $mail->send();
    echo 'Email has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
