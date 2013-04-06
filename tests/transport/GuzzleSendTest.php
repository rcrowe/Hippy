<?php

namespace rcrowe\Hippy\Tests\Transport;

use Mockery as m;
use rcrowe\Hippy\Transport\Guzzle;
use rcrowe\Hippy\Message;
use InvalidArgumentException;
use Exception;

class GuzzleSendTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testNoTokenSet()
    {
        $guzzle  = new Guzzle(null, null, null);
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
        $guzzle  = new Guzzle('123', null, null);
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
        $guzzle  = new Guzzle('123', 'cog', null);
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
        $guzzle  = new Guzzle('123', 'cog', 'vivalacrowe');
        $message = new Message(true, 'green');

        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->once();

        // Build up the data we are sending to Hipchat
        $data = array(
            'room_id'        => $guzzle->getRoom(),
            'from'           => $guzzle->getFrom(),
            'message'        => $message->getMessage(),
            'message_format' => $message->getMessageFormat(),
            'notify'         => $message->getNotification(),
            'color'          => $message->getBackgroundColor(),
            'format'         => 'json',
        );

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->with(
            'rooms/message?format=json&auth_token=123',
            array('Content-type' => 'application/x-www-form-urlencoded'),
            http_build_query($data)
        )->andReturn($entity)->once();

        $guzzle->setHttp($http);
        $guzzle->send($message);
    }
}
