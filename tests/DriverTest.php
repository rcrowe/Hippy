<?php

// Include Hippy
include_once dirname(__FILE__).'/../Hippy.php';


class MockDriver extends Hippy_Driver {
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
		$this->assertEquals(-1, $driver::STATUS_BAD_RESPONSE);
		$this->assertEquals(200, $driver::STATUS_OK);
		$this->assertEquals(400, $driver::STATUS_BAD_REQUEST);
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
}