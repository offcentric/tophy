<?php
$name = trim(utf8_decode($_POST["name"]));
$email =  trim(utf8_decode($_POST["email"]));
$message = trim(utf8_decode($_POST["message"]));
$GLOBALS['has_errs'] = false;

if($name == ""){
	$GLOBALS['errs']['name'] = $contact_form['error_no_name'];
	$GLOBALS['has_errs'] = true;
}
if($email == ""){
	$GLOBALS['errs']['email'] = $contact_form['error_no_email'];
	$GLOBALS['has_errs'] = true;
}elseif(!eregi("^[_a-z0-9-]+(.[_a-z0-9-]+)*@[a-z0-9-]+(.[a-z0-9-]+)*(.[a-z]{2,3})$", $email)){
	$GLOBALS['errs']['email'] = $contact_form['error_invalid_email'];
	$GLOBALS['has_errs'] = true;	
}
if($message == ""){
	$GLOBALS['errs']['message'] = $contact_form['error_no_message'];
	$GLOBALS['has_errs'] = true;
}

if(!$GLOBALS['has_errs']){
	$message = stripslashes($message) . "<br /><br />" . $name;
	$message =  wordwrap($message, 70);
	$message =  str_replace("\r", "<br />", $message);

	$to      = $_SESSION['admin_email'];
	$subject = $_SESSION['email_subject'];

	$headers = 'From: ' . $email . "\r\n" .
	'Content-Type: text/html; charset=iso-8859-1' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();

	if(!mail($to, $subject, $message, $headers)) $GLOBALS['errs']['message'] = 'ERROR WITH SENDING EMAIL!!';
}
?>