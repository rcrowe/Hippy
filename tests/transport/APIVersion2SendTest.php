<?php

namespace rcrowe\Hippy\Tests\Transport;

use Mockery as m;
use rcrowe\Hippy\Transport\APIVersion2;
use rcrowe\Hippy\Message;
use InvalidArgumentException;
use Exception;

class APIVersion2SendTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testNoTokenSet()
    {
        $guzzle  = new APIVersion2(null, null, null);
        $message = new Message;

        try {
            $guzzle->send($message);
            $this->assertFalse(true);
        } catch (InvalidArgumentException $ex) {
            $this->assertEquals($ex->getMessage(), 'Invalid `token`');
        } catch (Exception $ex) {
            $this->assertFalse(true);
        }
    }

    public function testNoRoomSet()
    {
        $guzzle  = new APIVersion2('123', null, null);
        $message = new Message;

        try {
            $guzzle->send($message);
            $this->assertFalse(true);
        } catch (InvalidArgumentException $ex) {
            $this->assertEquals($ex->getMessage(), 'Invalid `room`');
        } catch (Exception $ex) {
            $this->assertFalse(true);
        }
    }

    public function testNoFromSet()
    {
        $guzzle  = new APIVersion2('123', 'cog', null);
        $message = new Message;

        try {
            $guzzle->send($message);
            $this->assertFalse(true);
        } catch (InvalidArgumentException $ex) {
            $this->assertEquals($ex->getMessage(), 'Invalid `from`');
        } catch (Exception $ex) {
            $this->assertFalse(true);
        }
    }

    public function testPost()
    {
        $guzzle  = new APIVersion2('123', 'cog', 'vivalacrowe');
        $message = new Message(true, 'green');

        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->once();

        // Build up the data we are sending to Hipchat
        $data = array(
            'message'        => $message->getMessage(),
            'message_format' => $message->getMessageFormat(),
            'notify'         => $message->getNotification(),
            'color'          => $message->getBackgroundColor()
        );

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->with(
            'room/cog/notification?auth_token=123',
            array('Content-type' => 'application/json'),
            json_encode($data)
        )->andReturn($entity)->once();

        $guzzle->setHttp($http);
        $guzzle->send($message);
    }
}
