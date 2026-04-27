<?php

// test api key
// a692a3511ebfbd784df1d019a0e23f4f

// qm api key
// ee94e3d7c5895ec66bf6e48e7a708f22

function weather_check () {
	$zip = $_POST['zip'];

	$url = "https://api.openweathermap.org/data/2.5/weather?zip=" . $zip . "&appid=ee94e3d7c5895ec66bf6e48e7a708f22&units=imperial";

	$options = array(
    'http' => array(
        'method'  => 'POST'
    )
	);
	$context  = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	if ($result === FALSE) {
		wp_send_json_error('Didnt work');
	}

	wp_send_json_success($result);
}