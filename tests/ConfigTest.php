<?php

// Include Hippy
include_once dirname(__FILE__).'/../Hippy.php';


class TestDriver extends Hippy_Driver {
	public function test() {
		return 'TestDriver_abc123';
	}
}

class ConfigTest extends PHPUnit_Framework_TestCase
{
	protected $config = array();
	
	public function __construct()
	{
		$this->config = include dirname(__FILE__).'/../Hippy/config.php';
	}
	
	public function testDefaultConfig()
	{
		$hippy = Hippy::instance();
		
		$this->assertEquals($this->config['token'], $hippy->auth_token);
		$this->assertEquals($this->config['room'], $hippy->room_id);
		$this->assertEquals($this->config['from'], $hippy->from);
		$this->assertEquals((int)$this->config['notify'], $hippy->notify);
		$this->assertEquals($this->config['api_endpoint'], $hippy->api_endpoint);
		$this->assertEquals('Hippy_Curl', get_class($hippy->driver));
	}
	
	public function testUnknownConfig()
	{
		$hippy = Hippy::instance();
		
		$this->assertEquals(null, $hippy->some_value);
	}
	
	public function testCompleteCustomConfig()
	{
		$hippy = Hippy::instance(array(
			'token'        => 'wysiwyg',
			'room'         => 'hippy',
			'from'         => 'Vivalacrowe',
			'notify'       => false,
			'api_endpoint' => 'http://company.com/api/',
			'driver'       => 'curl'
		));
		
		$this->assertEquals('wysiwyg', $hippy->auth_token);
		$this->assertEquals('hippy', $hippy->room_id);
		$this->assertEquals('Vivalacrowe', $hippy->from);
		$this->assertEquals(0, $hippy->notify);
		$this->assertEquals('http://company.com/api/', $hippy->api_endpoint);
		$this->assertEquals('Hippy_Curl', get_class($hippy->driver));
	}
	
	public function testUnknownDriver()
	{
		try
		{
			$hippy = Hippy::instance(array(
				'token'        => 'wysiwyg',
				'room'         => 'hippy',
				'from'         => 'Vivalacrowe',
				'notify'       => false,
				'api_endpoint' => 'http://company.com/api/',
				'driver'       => 'hello'
			));
			
			$this->assertFalse(true);
		}
		catch(HippyUnknownDriverException $ex)
		{
			$this->assertTrue(true);
		}
	}
	
	public function testPartialConfig()
	{
		$hippy = Hippy::clean();
		
		$this->assertEquals($this->config['token'], $hippy->auth_token);
		$this->assertEquals($this->config['room'], $hippy->room_id);
		$this->assertEquals($this->config['from'], $hippy->from);
		$this->assertEquals((int)$this->config['notify'], $hippy->notify);
		$this->assertEquals($this->config['api_endpoint'], $hippy->api_endpoint);
		$this->assertEquals('Hippy_Curl', get_class($hippy->driver));
		
		$hippy = Hippy::instance(array(
			'room' => 'Monkey'
		));
		
		$this->assertEquals($this->config['token'], $hippy->auth_token);
		$this->assertEquals('Monkey', $hippy->room_id);
		$this->assertEquals($this->config['from'], $hippy->from);
		$this->assertEquals((int)$this->config['notify'], $hippy->notify);
		$this->assertEquals($this->config['api_endpoint'], $hippy->api_endpoint);
		$this->assertEquals('Hippy_Curl', get_class($hippy->driver));
	}
	
	public function testCleanWithConfig()
	{
		$hippy = Hippy::instance(array(), true);
		
		$this->assertEquals($this->config['token'], $hippy->auth_token);
		$this->assertEquals($this->config['room'], $hippy->room_id);
		$this->assertEquals($this->config['from'], $hippy->from);
		$this->assertEquals((int)$this->config['notify'], $hippy->notify);
		$this->assertEquals($this->config['api_endpoint'], $hippy->api_endpoint);
		$this->assertEquals('Hippy_Curl', get_class($hippy->driver));
		
		$hippy = Hippy::instance();
		
		$this->assertEquals($this->config['token'], $hippy->auth_token);
		$this->assertEquals($this->config['room'], $hippy->room_id);
		$this->assertEquals($this->config['from'], $hippy->from);
		$this->assertEquals((int)$this->config['notify'], $hippy->notify);
		$this->assertEquals($this->config['api_endpoint'], $hippy->api_endpoint);
		$this->assertEquals('Hippy_Curl', get_class($hippy->driver));
		
		$hippy = Hippy::clean(array(
			'token'        => 'wysiwyg',
			'room'         => 'hippy',
			'from'         => 'Vivalacrowe',
			'notify'       => false,
			'api_endpoint' => 'http://company.com/api/',
			'driver'       => 'curl'
		));
		
		$this->assertEquals('wysiwyg', $hippy->auth_token);
		$this->assertEquals('hippy', $hippy->room_id);
		$this->assertEquals('Vivalacrowe', $hippy->from);
		$this->assertEquals(0, $hippy->notify);
		$this->assertEquals('http://company.com/api/', $hippy->api_endpoint);
		$this->assertEquals('Hippy_Curl', get_class($hippy->driver));
	}
	
	public function testRuntimeDriver()
	{
		$hippy = Hippy::instance(array(
			'driver' => new TestDriver
		));
		
		$driver = $hippy->driver;
		
		$this->assertEquals('TestDriver', get_class($driver));
		
		$this->assertEquals('TestDriver_abc123', $driver->test());
	}
}