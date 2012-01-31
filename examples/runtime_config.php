<?php

// Include the Hippy library
// Make sure Hippy/config.php is set with your Hipchat details
require '../Hippy.php';

// When Hippy is initialised through a call to a static function
// that does any kind of message sending, then the config values
// from Hippy/config.php are automatically loaded

// Hippy gives you the option to override these in various ways

Hippy::config(array(
	'token'  => 'wysiwyg',
	'room'   => 'hippy',
	'from'   => 'Vivalacrowe',
	'notify' => false,
	'color'  => 'red',
));

// try
// {
// 	Hippy::speak('Hello world', array(
// 		'token'  => 'wysiwyg',
// 		'room'   => 'hippy',
// 		'from'   => 'Vivalacrowe',
// 		'notify' => false,
// 		'color'  => 'red',
// 	));
// }
// catch(HippyResponseException $ex)
// {
// 	echo $ex->getMessage();
// }

$instance = Hippy::instance(array(
	'token'  => 'wysiwyg',
	'room'   => 'hippy',
	'from'   => 'Vivalacrowe',
	'notify' => false,
	'color'  => 'red',
));

$instance = Hippy::clean(array(
	'token'  => 'wysiwyg',
	'room'   => 'hippy',
	'from'   => 'Vivalacrowe',
	'notify' => false,
	'color'  => 'red',
));