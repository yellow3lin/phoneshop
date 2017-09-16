<?php
namespace Common\Util\phpmailer;
use think\Exception;
/**
 * 发送邮件类库
 */
class Email {
	public static function send($to,$title,$content){
		if (empty($to)) {
			return false;
		}

		try{
			//require 'PHPMailerAutoload.php';

			$mail = new PHPMailer;

			//$mail->SMTPDebug = 3;                               // Enable verbose debug output

			$mail->isSMTP();                                      // Set mailer to use SMTP.class
			$mail->Host = C('email.Host');  // Specify main and backup SMTP.class servers
			$mail->SMTPAuth = true;                               // Enable SMTP.class authentication
			$mail->Username = C('email.Username');                 // SMTP.class username
			$mail->Password = C('email.Password');                           // SMTP.class password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = C('email.Port');                                    // TCP port to connect to

			$mail->setFrom(C('email.setFrom'));
			//$mail->addAddress('joe@example.net', 'Joe User');     // Add a recipient
			$mail->addAddress($to);               // Name is optional
			$mail->addReplyTo(C('email.addReplyTo'));
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = $title;
			$mail->Body    = $content;
			//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

			if(!$mail->send()) {
				return false;
			    //echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
				return true;
			    //echo 'Message has been sent';
			}		
		}catch(phpmailerException $e){
			return false;
		}
	}
}
