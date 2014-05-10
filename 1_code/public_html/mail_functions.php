<?php

include 'include/class.phpmailer.php';

function postmail($to, $subject = '', $body = '', $username = '')
{
	//error_reporting(E_STRICT);
	//date_default_timezone_set('Asia/Shanghai');
	date_default_timezone_set('UTC');

	//require_once('include/class.phpmailer.php');
	//include('class.smtp.php');
	$mail             = new PHPMailer(); 
	//$body            = eregi_replace("[\]",'',$body); 
	//$mail->CharSet ="GBK";//ISO-8859-1
	$mail->IsSMTP();  
	$mail->SMTPDebug  = 1;
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth   = true; 
	//$mail->SMTPSecure = "ssl";
	$mail->Host       = 'smtp.126.com';
	$mail->Port       = 25;
	$mail->Username   = 'ecetest@126.com';
	$mail->Password   = 'ece452';
	$mail->From = 'ecetest@126.com';
	$mail->FromName = 'Service';
	//$mail->SetFrom('ecetest@126.com', 'Service');
	//$mail->AddReplyTo('ecetest@126.com', 'Service');

	$mail->Subject    = $subject;
	$mail->AltBody    = 'To view the message, please use an HTML compatible email viewer!'; // optional, comment out and test
	$mail->MsgHTML($body);
	$address = $to;
	$mail->AddAddress($address, $username);

	if(!$mail->Send()) {
		echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	}
}

function mail_send($mailAddr, $userName, $mailContent)
{
	$mailContent = "Dear ". $userName .", <br>".$mailContent;

	postmail($mailAddr, "Alerts Reminder", $mailContent, $userName);
}

?>
