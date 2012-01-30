<?php

// Include Hippy
include_once dirname(__FILE__).'/../Hippy.php';


class MockDriver extends Hippy_Driver {
}

class BadJSONDriver extends Hippy_Driver {
	public function request($url) {
		return 'i am not JSON!';
	}
}

class MissingStatusDriver extends Hippy_Driver {
	public function request($url) {
		return json_encode(array(
			'missing_field' => true
		));
	}
}

class NotSentDriver extends Hippy_Driver {
	public function request($url) {
		return json_encode(array(
			'status' => 'failed'
		));
	}
}

class DriverTest extends PHPUnit_Framework_TestCase
{	
	public function testDriverConstants()
	{
		$hippy = Hippy::clean(array(
			'driver' => new MockDriver
		));
		
		$driver = $hippy->driver;
		
		$this->assertEquals('MockDriver', get_class($driver));
		$this->assertEquals('rooms/message', $driver::HIPCHAT_REQUEST);
	}
	
	public function testDriverInit()
	{
		$hippy = Hippy::clean(array(
			'token'        => 'wysiwyg',
			'room'         => 'hippy',
			'from'         => 'Vivalacrowe',
			'notify'       => false,
			'api_endpoint' => 'http://company.com/api/',
			'driver'       => new MockDriver
		));
		
		$driver = $hippy->driver;
		
		$this->assertEquals('wysiwyg', $driver->auth_token);
		$this->assertEquals('hippy', $driver->room_id);
		$this->assertEquals('Vivalacrowe', $driver->from);
		$this->assertEquals(0, $driver->notify);
		$this->assertEquals('http://company.com/api/', $driver->api_endpoint);
	}
	
	public function testDriverInitChain()
	{
		$hippy = Hippy::clean(array(
			'driver' => new MockDriver
		));
		
		$this->assertEquals('MockDriver', get_class($hippy->driver->init(array())));
	}
	
	public function testLastMsg()
	{
		$hippy = Hippy::clean();
		
		$this->assertEquals(null, $hippy->driver->last_msg);
	}
	
	public function testBadJSONResponse()
	{
		$hippy = Hippy::clean(array(
			'driver' => new BadJSONDriver
		));
		
		try
		{
			$hippy->driver->send('Bad JSON test');
			$this->assertFalse(true);
		}
		catch(HippyResponseException $ex)
		{
			$this->assertTrue(true);
		}
	}
	
	public function testMissingStatusField()
	{
		$hippy = Hippy::clean(array(
			'driver' => new MissingStatusDriver
		));
		
		try
		{
			$hippy->driver->send('Bad JSON test');
			$this->assertFalse(true);
		}
		catch(HippyResponseException $ex)
		{
			$this->assertTrue(true);
		}
	}
	
	public function testNotSent()
	{
		$hippy = Hippy::clean(array(
			'driver' => new NotSentDriver
		));
		
		try
		{
			$hippy->driver->send('Bad JSON test');
			$this->assertFalse(true);
		}
		catch(HippyNotSentException $ex)
		{
			$this->assertTrue(true);
		}
	}
}