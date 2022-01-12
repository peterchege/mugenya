<?php
/*******************************************************************************
This file demostrates very basic notification handling. To receive notifications
you need to set your notification URL address in the SVP panel and handle
notifications with a handler like this one modified for your needs.
*******************************************************************************/

//SMTP server settings for mail notification!!!
$SMTP_SETTINGS["USE_SMTP"] = true;
$SMTP_SETTINGS["SMTP_SERVER"] = ''; 
$SMTP_SETTINGS["SMTP_PORT"] = 25;
$SMTP_SETTINGS["SMTP_USERNAME"] = '';
$SMTP_SETTINGS["SMTP_PASSWORD"] = '';

//Mail Settings
$mail_subject = 'API Notification Test Application';
$mail_to = '';
$mail_from = '';

//Mail function
function html_mail($from, $to, $subject, $html_message) {
	global $SMTP_SETTINGS;
	
	$mail_headers  = "From: {$from} \r\n";
	$mail_headers .= "MIME-Version: 1.0 \r\n";
	$mail_headers .= "Content-type: text/html; charset=UTF-8 \r\n";
	$mail_subject = $subject;
	
	if(isset($SMTP_SETTINGS["USE_SMTP"]) && $SMTP_SETTINGS["USE_SMTP"] == true) {
		$res = smtp_mail($to, $subject, $html_message, $mail_headers);
		return $res === true;
	}
	else {
		return (bool) mail($to, $subject, $html_message, $mail_headers);
	}
}

//Prepare mail body data
function prepare_email_data($data) {
	if(function_exists("preg_replace")) {
		return (preg_replace(array("/\n\n|\r\r/", "/(^|[^\r])\n/", "/\r([^\n]|\$)/D", "/(^|\n)\\./"), array("\r\n\r\n", "\\1\r\n", "\r\n\\1", "\\1.."), $data));
	}
	else {
		return (ereg_replace("(^|\n)\\.", "\\1..", ereg_replace("\r([^\n]|\$)", "\r\n\\1", ereg_replace("(^|[^\r])\n", "\\1\r\n", ereg_replace("\n\n|\r\r", "\r\n\r\n", $data)))));
	}
}

//SMTP mail function...modified to provide authenticated logins
function smtp_mail($to, $subject, $message, $headers) {
	//Set as global variable
	global $SMTP_SETTINGS;
	if(!isset($SMTP_SETTINGS["SMTP_SERVER"])) $SMTP_SETTINGS["SMTP_SERVER"] = 'localhost';
	if(!isset($SMTP_SETTINGS["SMTP_PORT"])) $SMTP_SETTINGS["SMTP_PORT"] = 25;

	//Get From address
	if(preg_match("/From:.*?[A-Za-z0-9\._%\-]+\@[A-Za-z0-9\._%\-]+.*/", $headers, $froms)) {
		preg_match("/[A-Za-z0-9\._%\-]+\@[A-Za-z0-9\._%\-]+/", $froms[0], $fromarr);
		$from = $fromarr[0];
	}

	//Open an SMTP connection
	$cp = fsockopen($SMTP_SETTINGS["SMTP_SERVER"], $SMTP_SETTINGS["SMTP_PORT"], $errno, $errstr, 1);
	if(!$cp) return "Failed to even make a connection";
	$res = fgets($cp, 256);
	if(substr($res,0 , 3) != "220") return "Failed to connect";

	//Say hello...
	fputs($cp, "HELO ".$SMTP_SETTINGS["SMTP_SERVER"]."\r\n");
	$res = fgets($cp, 256);
	if(substr($res, 0, 3) != "250") return "Failed to Introduce";
	
	//Perform authentication
	if(isset($SMTP_SETTINGS["SMTP_USERNAME"])) {
		fputs($cp, "auth login\r\n");
		$res = fgets($cp, 256);
		if(substr($res, 0, 3) != "334") return "Failed to Initiate Authentication";

		fputs($cp, base64_encode($SMTP_SETTINGS["SMTP_USERNAME"])."\r\n");
		$res = fgets($cp, 256);
		if(substr($res, 0, 3) != "334") return "Failed to Provide Username for Authentication";

		if(isset($SMTP_SETTINGS["SMTP_PASSWORD"])) {
			fputs($cp, base64_encode($SMTP_SETTINGS["SMTP_PASSWORD"])."\r\n");
			$res = fgets($cp, 256);
			if(substr($res, 0, 3) != "235") return "Failed to Authenticate";
		}
	}
	//Mail from...
	fputs($cp, "MAIL FROM: <$from>\r\n");
	$res = fgets($cp, 256);
	if(substr($res, 0, 3) != "250") return "MAIL FROM failed";

	//Rcpt to...
	fputs($cp, "RCPT TO: <$to>\r\n");
	$res = fgets($cp, 256);
	if(substr($res, 0, 3) != "250") return "RCPT TO failed";

	//Data...
	fputs($cp, "DATA\r\n");
	$res = fgets($cp, 256);
	if(substr($res,0,3) != "354") return "DATA failed";

	//Send To:, From:, Subject:, other headers, blank line, message, and finish
	//with a period on its own line (for end of message)
	$message = prepare_email_data($message);
	fputs($cp, "To: $to\r\n$headers"."Subject: $subject\r\n\r\n$message\r\n.\r\n");
	$res = fgets($cp, 256);
	if(substr($res, 0, 3) != "250") return "Message Body Failed";

	//...And time to quit...
	fputs($cp, "QUIT\r\n");
	$res = fgets($cp, 256);
	if(substr($res, 0, 3) != "221") return "QUIT failed";

	return true;
}

$operation = urldecode($_GET['operation_name']);
$result = urldecode($_GET['result']);
$clip_ref = (int) urldecode($_GET['clip_ref']);
$clip_key = urldecode($_GET['clip_key']);
$error = false;

switch($operation) {
	case 'video_transcode': {
		if($result == 'OK') {
			$mail_text = 'The clip with Ref. code '.$clip_ref.' and key '.$clip_key.' was successfully transcoded!';
		}
		else {
			$err_msg = urldecode($_GET['err_msg']);
			$mail_text = 'The clip with Ref. code '.$clip_ref.' and key '.$clip_key.' was not successfully transcoded! Error: '.$err_msg;
		}
		break;
	}
	case 'delete_video': {
		$mail_text = 'The clip with Ref. code '.$clip_ref.' and key '.$clip_key.' was successfully deleted!';
		break;
	}
	case 'edit_video': {
		$mail_text = 'The clip with Ref. code '.$clip_ref.' and key '.$clip_key.' was successfully edited!';
		break;
	}
	case 'registered_user': {
		$user_ref = (int) urldecode($_GET['ref_no']);
		$user_name = urldecode($_GET['user_name']);
		$login_name = urldecode($_GET['login_name']);
		$mail_text = 'The User with Ref. code '.$user_ref.', name: '.$user_name.', login name: '.$login_name.' was successfully registered!';
		break;
	}
	case 'upload_video': {
		if($result == 'OK') {
			$mail_text = 'The clip with Ref. code '.$clip_ref.' and key '.$clip_key.' was successfully uploaded!';
		}
		else {
			$err_msg = urldecode($_GET['err_msg']);
			$mail_text = 'The clip with Ref. code '.$clip_ref.' was not successfully uploaded! Error: '.$err_msg;
		}
		break;
	}
	case 'notice': {
		$mail_text = 'Subject: '.urldecode($_GET['subject']).'<br /> Type: '.urldecode($_GET['type']).'<br /> Ref. No: '.urldecode($_GET['ref_no']).'<br /> Message: '.urldecode($_GET['message']);
		break;
	}
	default: {
		echo 'No such notification operation';
		$error = true;
	}
}

if(!$error) {
	html_mail($mail_from, $mail_to, $mail_subject, $mail_text);
	echo 'Successfull notification';
}
?>