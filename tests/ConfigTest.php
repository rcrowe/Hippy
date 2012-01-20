<?php

// Include Hippy
include_once dirname(__FILE__).'/../Hippy.php';


class ConfigTest extends PHPUnit_Framework_TestCase
{
	public function testDefaultConfig()
	{
		$hippy = Hippy::instance();
		
		$this->assertEquals('abc123', $hippy->auth_token);
		$this->assertEquals('test', $hippy->room_id);
		$this->assertEquals('Hippy', $hippy->from);
		$this->assertEquals(1, $hippy->notify);
		$this->assertEquals('http://api.blunderapp.com/v1/', $hippy->api_endpoint);
		$this->assertEquals('curl', $hippy->driver);
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
			'driver'       => 'socket'
		));
		
		$this->assertEquals('wysiwyg', $hippy->auth_token);
		$this->assertEquals('hippy', $hippy->room_id);
		$this->assertEquals('Vivalacrowe', $hippy->from);
		$this->assertEquals(0, $hippy->notify);
		$this->assertEquals('http://company.com/api/', $hippy->api_endpoint);
		$this->assertEquals('socket', $hippy->driver);
	}
	
	public function testPartialConfig()
	{
		$hippy = Hippy::clean();
		
		$this->assertEquals('abc123', $hippy->auth_token);
		$this->assertEquals('test', $hippy->room_id);
		$this->assertEquals('Hippy', $hippy->from);
		$this->assertEquals(1, $hippy->notify);
		$this->assertEquals('http://api.blunderapp.com/v1/', $hippy->api_endpoint);
		$this->assertEquals('curl', $hippy->driver);
		
		$hippy = Hippy::instance(array(
			'room' => 'Monkey'
		));
		
		$this->assertEquals('abc123', $hippy->auth_token);
		$this->assertEquals('Monkey', $hippy->room_id);
		$this->assertEquals('Hippy', $hippy->from);
		$this->assertEquals(1, $hippy->notify);
		$this->assertEquals('http://api.blunderapp.com/v1/', $hippy->api_endpoint);
		$this->assertEquals('curl', $hippy->driver);
	}
	
	public function testCleanWithConfig()
	{
		$hippy = Hippy::instance(array(), true);
		
		$this->assertEquals('abc123', $hippy->auth_token);
		$this->assertEquals('test', $hippy->room_id);
		$this->assertEquals('Hippy', $hippy->from);
		$this->assertEquals(1, $hippy->notify);
		$this->assertEquals('http://api.blunderapp.com/v1/', $hippy->api_endpoint);
		$this->assertEquals('curl', $hippy->driver);
		
		$hippy = Hippy::instance();
		
		$this->assertEquals('abc123', $hippy->auth_token);
		$this->assertEquals('test', $hippy->room_id);
		$this->assertEquals('Hippy', $hippy->from);
		$this->assertEquals(1, $hippy->notify);
		$this->assertEquals('http://api.blunderapp.com/v1/', $hippy->api_endpoint);
		$this->assertEquals('curl', $hippy->driver);
		
		$hippy = Hippy::clean(array(
			'token'        => 'wysiwyg',
			'room'         => 'hippy',
			'from'         => 'Vivalacrowe',
			'notify'       => false,
			'api_endpoint' => 'http://company.com/api/',
			'driver'       => 'socket'
		));
		
		$this->assertEquals('wysiwyg', $hippy->auth_token);
		$this->assertEquals('hippy', $hippy->room_id);
		$this->assertEquals('Vivalacrowe', $hippy->from);
		$this->assertEquals(0, $hippy->notify);
		$this->assertEquals('http://company.com/api/', $hippy->api_endpoint);
		$this->assertEquals('socket', $hippy->driver);
	}
}