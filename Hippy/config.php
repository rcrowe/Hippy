<?php

/**
 * Hipchat settings. By default these are loaded when Hippy is called.
 *
 * Set them here or at runtime.
 */
return array(
	
	/**
	 * API token to access the Hipchat with. This can be found at
	 * https://#account#.hipchat.com/admmin/api
	 */
	'token'  => 'abc123',
	
	/**
	 * Name of the room you want the message sent too
	 */
	'room'   => 'Hippy Test',
	
	/**
	 * Name of the user who the message will appear to be from
	 */
	'from'   => 'Hippy',
	
	/**
	 * Should a notification to let others know that a new message has been recieved
	 */
	'notify' => true,
	
	/**
	 * Color of the message in Hipchat. Supported: yellow, red, green, purple or random
	 */
	'color'  => 'yellow',
	
	/**
	 * Which connection driver should we use. Maybe socket if your having a problem?
	 * Currently only support curl
	 */
	'driver' => 'curl',
	
	/**
	 * URL to the Hipchat API endpoint. Shouldn't need to change this, mainly for testing purposes.
	 * If you see errors about SSL certificate, you should probably fix it, or change https:// to http://
	 */
	'api_endpoint' => 'https://api.hipchat.com/v1/',
);