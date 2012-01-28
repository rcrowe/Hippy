<?php

// Include Hippy
include_once dirname(__FILE__).'/../Hippy.php';


class InstanceTest extends PHPUnit_Framework_TestCase
{
	public function testGetInstane()
	{
		// Test v0.3 way of retrieving an instance
		$hippy = Hippy::getInstance();
		$this->assertEquals('Hippy', get_class($hippy));
	}
	
	public function testInstance()
	{
		$hippy = Hippy::instance();
		$this->assertEquals('Hippy', get_class($hippy));
	}
	
	public function testCleanInstance()
	{
		// Set some data on Hippy instance
		$hippy = Hippy::instance();
		$hippy->some_data = 'abc123';
		
		$this->assertEquals('abc123', $hippy->some_data);
		
		// Make sure we get that data back when we call instance() again
		$hippy = Hippy::instance();
		$this->assertEquals('abc123', $hippy->some_data);
		
		// Now test calling instance with the clear flag
		$hippy = Hippy::instance(array(), true);
		
		$this->assertEquals(null, $hippy->some_data);
	}
	
	public function testCleanInstanceShortcut()
	{
		// Like the last test we will get a new instance of Hippy back
		// but doing it via a call to clean()
		
		// Set some data on Hippy instance
		$hippy = Hippy::instance();
		$hippy->some_data = 'abc123';
		
		$this->assertEquals('abc123', $hippy->some_data);
		
		// Make sure we get that data back when we call instance() again
		$hippy = Hippy::instance();
		$this->assertEquals('abc123', $hippy->some_data);
		
		// Now test calling instance with the clear flag
		$hippy = Hippy::clean();
		
		$this->assertEquals(null, $hippy->some_data);
	}
}