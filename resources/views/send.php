<?php

$url = 'http://api.silverstreet.com/send.php';

$data = array(
	'sender'		=> 'queuemart',
	'destination'	=> '',
	'body'			=> 'hello testing',
	'username'		=> 'QUEUE1',
	'password'		=> 'JnqMd1qk',

	);

$data_string = http_build_query($data); 

$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, count($data));;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

	$result = curl_exec($ch);

	curl_close($ch);
?>

<form>

	</form>