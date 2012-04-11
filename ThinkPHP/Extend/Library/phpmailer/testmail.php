<?php
require 'class.phpmailer.php';

$mail = new PHPMailer();

$mail->IsSMTP();
$mail->Host = 'mail.1singlestep.com';
$mail->Port = 2626;
$mail->SMTPAuth = true;
$mail->Username = 'register@1singlestep.com';
$mail->Password = 'BaoChangJi';
$mail->CharSet='UTF-8';

$mail->From = 'register@1singlestep.com';
$mail->FromName = 'Richard';

$mail->AddAddress('wu.chen@chinapay.com');

$mail->WordWrap = 50;
$mail->IsHTML(true);

$mail->Subject = 'Test Mail';
$mail->Body    = 'Just Test';
$mail->AltBody = 'Just Test';

$mail->Send();
?>