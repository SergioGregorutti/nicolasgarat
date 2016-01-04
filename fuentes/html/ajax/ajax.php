<?php
	if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')) {
	   die();
	}

	$from = 'your@email.com';

	$name = isset($_POST["name"]) ? trim($_POST["name"]) : "";
	$email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
	$subject = isset($_POST["subject"]) ? trim($_POST["subject"]) : "";
	$message = isset($_POST["message"]) ? trim($_POST["message"]) : "";

	$response = '';
	$error = false;
	$error_fields = array();

	
	if (!$subject){
		$error = true;
		$error_fields[] = 'subject';
	}

	if (!$message){
		$error = true;
		$response = 'Please enter a message.';
		$error_fields[] = 'message';
	}


	$pattern = "^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$^";
	if(!preg_match_all($pattern, $email, $out)) {
		$error = true;
		$response = 'Please enter a valid email.';
		$error_fields[] = 'email';
	}
	if (!$email){
		$error = true;
		$response = 'Please enter an email.';
		if (!in_array("email", $error_fields))
			$error_fields[] = "email";
	}

	if (!$name){
		$error = true;
		$response = 'Please enter a name.';
		$error_fields[] = "name";
	}

	if (!$error){

 		$headers = "From: ".$name." <".$email.">\r\nReply-To: ".$from."";

		//send the email
		$sent = mail($from,$subject,$message,$headers); 
		
		if ($sent){
		 	$response = 'Message sent.';
		}else{
			$response = 'Message not sent. An error has occurred.';
		}
	}


	$data = array(
		'response' => $response,
		'error' => $error,
		'error_fields' => $error_fields
	);

	header('Content-Type: application/json');

	echo json_encode($data);
?>