<?php

// Include the Hippy library
// Make sure Hippy/config.php is set with your Hipchat details
require '../Hippy.php';

// Hippy allows you to set custom drivers to handle sending the data
// to Hipchat. Maybe you dont have cURL and want to use file sockets.
// Hippy::config(array(
// 	'driver' => 'socket'
// ));

// You can even pass in the driver at runtime
class MockDriver extends Hippy_Driver {
	public function request($url) {
		$response = array(
			'status' => 'sent'
		);
		
		return json_encode($response);
	}
}

Hippy::clean(array(
	'driver' => new MockDriver
));

$response = Hippy::speak('Test message using mocked driver');

// print_r($response)


// Send a message
Hippy::clean()->driver->send('[Hippy Driver Example] - Message 1');