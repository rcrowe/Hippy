<?php

// Include Hippy
include_once dirname(__FILE__).'/../Hippy.php';


class SpeakMockDriver extends Hippy_Driver {
	public function request($url) {
		$response = array(
			'status' => 'sent'
		);
		
		return json_encode($response);
	}
}

class SpeakTest extends PHPUnit_Framework_TestCase
{
	public function testSpeakValidSettings()
	{
		try
		{
			Hippy::speak('test', array('room' => ''));
			$this->assertFalse(true);
		}
		catch(HippyException $ex)
		{
			$this->assertTrue(true);
		}
	}
	
	public function testLastMsgFormat()
	{
		Hippy::clean(array(
			'driver' => new SpeakMockDriver
		));
		
		$details = Hippy::speak("Testing\\nline\\nbreaks");
		
		$this->assertEquals("Testing<br />line<br />breaks", $details['msg']);
	}
	
	public function testApiEndpoint()
	{
		Hippy::clean(array(
			'driver' => new SpeakMockDriver
		));
		
		$details = Hippy::speak("Testing API endpoint");
		
		$this->assertEquals('http://api.blunderapp.com/v1/?format=json&message=Testing+API+endpoint', 
							 $details['api_endpoint']);
	}
	
	public function testSpeak()
	{
		
	}
}