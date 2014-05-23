<?php
require_once('class.phpmailer.php');

$mail = new PHPMailer;

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'smtp.consignum.com.br';  // Specify main and backup server
$mail->Port = 25;                       //Port    
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'teste.testando';                            // SMTP username
$mail->Password = '';                           // SMTP password
//$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

$mail->From = 'teste.testando@teste.com.br';
$mail->FromName = 'Rodrigo Camargo';
$mail->addAddress('teste.testando@teste.com.br', 'Teste Testando');  // Add a recipient
//$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('teste.testando1@teste.com.br', 'Information');
$mail->addCC('teste.testando1@teste.com.br');
$mail->addBCC('teste.testando2@teste.com.br');

$mail->WordWrap = 50;                                 // Set word wrap to 50 characters
//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Titulo do Email';
$mail->Body    = 'Conteudo do email';
$mail->AltBody = 'Conteudo do email';

if(!$mail->send()) {
   echo 'Message could not be sent.';
   echo 'Mailer Error: ' . $mail->ErrorInfo;
   exit;
}

echo 'Message has been sent';