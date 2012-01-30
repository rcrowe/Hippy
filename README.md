Hippy
=====

Hippy is a simple PHP client for sending messages to a HipChat room. It is designed for incidental notifications from an application.

Requirements
------------

* [mandatory] PHP version 5.x (developed using 5.3.2)
* [mandatory] PHP's cURL module
* [optional]  PHPUnit 3.6.2

Example
-------

	<?php
	require 'Hippy/Hippy.php';
	Hippy::speak('Build successful');
	?>
	
This example uses `Hippy/config.php` and sends the message straight away to your Hipchat room. For more examples such as runtime config, message queueing & connection drivers then checkout <http://github.com/rcrowe/Hippy/tree/master/examples>

Tests
-----

To run all tests:

	$> phpunit tests
	
Open Source License
-------------------

Hippy is released under the MIT public license.

<http://www.slimframework.com/license>