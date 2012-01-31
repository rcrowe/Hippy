<?php

// Include Hippy
include_once dirname(__FILE__).'/../Hippy.php';


class QueueMockDriver extends Hippy_Driver {
	public function request($url) {
		$response = array(
			'status' => 'sent'
		);
		
		return json_encode($response);
	}
}

class QueueTest extends PHPUnit_Framework_TestCase
{
	protected $config = array();
	
	public function __construct()
	{
		$this->config = include dirname(__FILE__).'/../Hippy/config.php';
	}
	
	public function testReturnQueue()
	{
		$hippy = Hippy::clean();
		
		$this->assertTrue(is_array($hippy->queue));
		$this->assertEquals(0, count($hippy->queue));
	}
	
	public function testAddToQueue()
	{
		\Hippy::add('Hello world');
		\Hippy::add('Push me on that array Blunder!');
		
		$hippy = Hippy::instance();
		
		$this->assertTrue(is_array($hippy->queue));
		$this->assertEquals(2, count($hippy->queue));
		
		$queue = $hippy->queue;
		
		$this->assertEquals('Hello world', $queue[0]);
		$this->assertEquals('Push me on that array Blunder!', $queue[1]);
	}
	
	public function testEmptyGoQueue()
	{
		Hippy::clean(array(
			'driver' => new QueueMockDriver
		));
		
		try
		{
			Hippy::go();
			$this->assertFalse(true);
		}
		catch(HippyEmptyQueueException $ex)
		{
			$this->assertEquals('Can not send queue. Queue is empty!', $ex->getMessage());
		}
	}
	
	public function testQueueOneGo()
	{
		Hippy::add('Hello world');
		
		$response = Hippy::go();
		
		$expected  = $this->config['api_endpoint'].'rooms/message?format=json&auth_token='.$this->config['token'];
		$expected .= '&room_id='.urlencode($this->config['room']).'&';
		$expected .= 'from='.$this->config['from'].'&notify='.(int)$this->config['notify'];
		$expected .= '&color='.$this->config['color'].'&message=Hello+world';
		
		$this->assertEquals($expected, $response['api_endpoint']);
		$this->assertEquals('Hello world', $response['msg']);
		$this->assertTrue(isset($response['response']['status']));
	}
	
	public function testMultipleJoin()
	{
		Hippy::clean(array(
			'driver' => new QueueMockDriver
		));
		
		for($i = 0; $i <= 10; $i++)
		{
			Hippy::add('Test '.$i);
		}
		
		$response = Hippy::go();
		
		$expected  = $this->config['api_endpoint'].'rooms/message?format=json&auth_token='.$this->config['token'];
		$expected .= '&room_id='.urlencode($this->config['room']).'&';
		$expected .= 'from='.$this->config['from'].'&notify='.(int)$this->config['notify'];
		$expected .= '&color='.$this->config['color'].'&message=';
		$expected .= 'Test+0%3Cbr+%2F%3ETest+1%3Cbr+%2F%3ETest+2%3Cbr+%2F%3ETest+3%3Cbr+%2F%3E';
		$expected .= 'Test+4%3Cbr+%2F%3ETest+5%3Cbr+%2F%3ETest+6%3Cbr+%2F%3ETest+7%3Cbr+%2F%3E';
		$expected .= 'Test+8%3Cbr+%2F%3ETest+9%3Cbr+%2F%3ETest+10';
		
		$this->assertEquals($expected, $response['api_endpoint']);
		
		$expected = '';
		
		for($i = 0; $i <= 10; $i++)
		{
			$expected .= 'Test '.$i.'<br />';
		}
		
		$expected = substr($expected, 0, -6);
		
		$this->assertEquals($expected, $response['msg']);
		$this->assertTrue(isset($response['response']['status']));
		
		
		
		Hippy::clean(array(
			'driver' => new QueueMockDriver
		));
		
		for($i = 0; $i <= 10; $i++)
		{
			Hippy::add('Test '.$i);
		}
		
		$response = Hippy::go(true);
		
		$expected  = $this->config['api_endpoint'].'rooms/message?format=json&auth_token='.$this->config['token'];
		$expected .= '&room_id='.urlencode($this->config['room']).'&';
		$expected .= 'from='.$this->config['from'].'&notify='.(int)$this->config['notify'];
		$expected .= '&color='.$this->config['color'].'&message=';
		$expected .= 'Test+0%3Cbr+%2F%3ETest+1%3Cbr+%2F%3ETest+2%3Cbr+%2F%3ETest+3%3Cbr+%2F%3E';
		$expected .= 'Test+4%3Cbr+%2F%3ETest+5%3Cbr+%2F%3ETest+6%3Cbr+%2F%3ETest+7%3Cbr+%2F%3E';
		$expected .= 'Test+8%3Cbr+%2F%3ETest+9%3Cbr+%2F%3ETest+10';
		
		$this->assertEquals($expected, $response['api_endpoint']);
		
		$expected = '';
		
		for($i = 0; $i <= 10; $i++)
		{
			$expected .= 'Test '.$i.'<br />';
		}
		
		$expected = substr($expected, 0, -6);
		
		$this->assertEquals($expected, $response['msg']);
		$this->assertTrue(isset($response['response']['status']));
		$this->assertEquals('sent', $response['response']['status']);
	}
	
	public function testMultipleDontJoin()
	{
		Hippy::clean(array(
			'driver' => new QueueMockDriver
		));
		
		for($i = 0; $i <= 10; $i++)
		{
			Hippy::add('Test '.$i);
		}
		
		$responses = Hippy::go(false);
		
		
		$this->assertEquals(11, count(array_keys($responses)));
		
		for($i = 0; $i <= 10; $i++)
		{
			$expected  = $this->config['api_endpoint'].'rooms/message?format=json&auth_token='.$this->config['token'];
			$expected .= '&room_id='.urlencode($this->config['room']).'&';
			$expected .= 'from='.$this->config['from'].'&notify='.(int)$this->config['notify'];
			$expected .= '&color='.$this->config['color'].'&message=Test+'.$i;
			
			$this->assertEquals($expected, $responses[$i]['api_endpoint']);
			$this->assertEquals('Test '.$i, $responses[$i]['msg']);
			$this->assertTrue(isset($responses[$i]['response']['status']));
			$this->assertEquals('sent', $responses[$i]['response']['status']);
		}
	}
	
	public function testFlushEmptyQueue()
	{
		Hippy::clean(array(
			'driver' => new QueueMockDriver
		));
		
		// Check that the when the queue is empty NULL is returned
		$response = Hippy::flush_queue();
		
		$this->assertEquals(null, $response);
	}
	
	public function testFlushQueue()
	{
		Hippy::clean(array(
			'driver' => new QueueMockDriver
		));
		
		Hippy::add('Hello world');
		
		// Check that the when the queue is empty NULL is returned
		$response = Hippy::flush_queue();
		
		$this->assertEquals('Hello world', $response['msg']);
	}
	
	public function testEmptyQueue()
	{
		$instance = Hippy::clean();
		
		Hippy::add('Message 1');
		Hippy::add('Message 2');
		
		$this->assertEquals(2, count($instance->queue));
		
		Hippy::clear_queue();
		
		$this->assertEquals(0, count($instance->queue));
	}
	
	public function testGoEmptyQueue()
	{
		$instance = Hippy::clean(array(
			'driver' => new QueueMockDriver
		));
		
		Hippy::add('Message 1');
		
		$this->assertEquals(1, count($instance->queue));
		
		Hippy::go();
		
		$this->assertEquals(0, count($instance->queue));
		
		
		Hippy::add('Message 1');
		Hippy::add('Message 2');
		Hippy::add('Message 3');
		
		$this->assertEquals(3, count($instance->queue));
		
		Hippy::go();
		
		$this->assertEquals(0, count($instance->queue));
	}
}