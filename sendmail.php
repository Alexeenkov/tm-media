<?php 

// require_once('phpmailer/PHPMailerAutoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer;
$mail->CharSet = 'utf-8';

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = 'ssl://smtp.yandex.ru';  			      // Specify main and backup SMTP servers ( $mail->Host = 'smtp.mail.ru'; )
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = 'lids@target-master.org'; // Ваш логин от почты с которой будут отправляться письма
$mail->Password = '31052021'; // Ваш пароль от почты с которой будут отправляться письма
$mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = 465; // TCP port to connect to / этот порт может отличаться у других провайдеров

$mail->setFrom('lids@target-master.org'); // от кого будет уходить письмо?
$mail->addAddress('bkxxl@bk.ru'); // Кому будет уходить письмо
$mail->isHTML(true);   // Set email format to HTML

$name = $_POST['name'];
$tel = $_POST['tel'];

$mail->Subject = 'Пользователь с сайта "тм-медиа.рф" оставил заявку!';
$mail->Body = "<b>Имя:</b> {$name}<br><b>Телефон: </b>{$tel}";
$mail->AltBody = '';

//Отправляем
if(!$mail->send()) {
	echo 'Error';
} else {
	header('location: index.html');
}
?>


