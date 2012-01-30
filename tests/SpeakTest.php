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
	protected $config = array();
	
	public function __construct()
	{
		$this->config = include dirname(__FILE__).'/../Hippy/config.php';
	}
	
	public function testSpeakValidSettings()
	{
		try
		{
			Hippy::speak('test', array('room' => ''));
			$this->assertFalse(true);
		}
		catch(HippyMissingSettingException $ex)
		{
			$this->assertEquals('Missing setting: room', $ex->getMessage());
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
		
		$response = Hippy::speak("Testing API endpoint");
		
		$expected  = $this->config['api_endpoint'].'rooms/message?format=json&auth_token='.$this->config['token'];
		$expected .= '&room_id='.urlencode($this->config['room']).'&';
		$expected .= 'from='.$this->config['from'].'&notify='.(int)$this->config['notify'];
		$expected .= '&color='.$this->config['color'].'&message=Testing+API+endpoint';
		
		$this->assertEquals($expected, $response['api_endpoint']);
	}
}