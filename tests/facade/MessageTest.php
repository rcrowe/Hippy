<?php

namespace rcrowe\Hippy\Tests\Facade;

use rcrowe\Hippy\Facade as Hippy;
use rcrowe\Hippy\Transport\APIVersion1;
use Mockery as m;
use ReflectionClass;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testTextNotInit()
    {
        $this->resetClient();

        Hippy::text('test');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testHtmlNotInit()
    {
        $this->resetClient();

        Hippy::html('test');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testAddNotInit()
    {
        $this->resetClient();

        Hippy::add('test');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testAddHtmlNotInit()
    {
        $this->resetClient();

        Hippy::addHtml('test');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testGoNotInit()
    {
        $this->resetClient();

        Hippy::go();
    }

    public function testSendTextMessage()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->once();

        $data = array(
            'room_id'        => 'egg',
            'from'           => 'spoon',
            'message'        => 'hello world',
            'message_format' => 'text',
            'notify'         => false,
            'color'          => 'yellow',
            'format'         => 'json',
        );

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->with(
            'rooms/message?format=json&auth_token=123',
            array('Content-type' => 'application/x-www-form-urlencoded'),
            http_build_query($data)
        )->andReturn($entity)->once();

        $transport = new APIVersion1('123', 'egg', 'spoon');
        $transport->setHttp($http);

        Hippy::init(null, null, null, $transport);

        Hippy::text('hello world');
    }

    public function testSendHtmlMessage()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->once();

        $data = array(
            'room_id'        => 'egg',
            'from'           => 'spoon',
            'message'        => 'hello world',
            'message_format' => 'html',
            'notify'         => false,
            'color'          => 'yellow',
            'format'         => 'json',
        );

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->with(
            'rooms/message?format=json&auth_token=123',
            array('Content-type' => 'application/x-www-form-urlencoded'),
            http_build_query($data)
        )->andReturn($entity)->once();

        $transport = new APIVersion1('123', 'egg', 'spoon');
        $transport->setHttp($http);

        Hippy::init(null, null, null, $transport);

        Hippy::html('hello world');
    }

    public function testAddToQueue()
    {
        $queue = m::mock('rcrowe\Hippy\Queue');
        $queue->shouldReceive('add')->once();
        $this->setQueue($queue);

        Hippy::add('test');
    }

    public function testAddHtmlToQueue()
    {
        $queue = m::mock('rcrowe\Hippy\Queue');
        $queue->shouldReceive('add')->once();
        $this->setQueue($queue);

        Hippy::addHtml('test');
    }

    public function testSendQueue()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->times(3);

        $data = array(
            'room_id'        => 'egg',
            'from'           => 'spoon',
            'message'        => 'hello world',
            'message_format' => 'html',
            'notify'         => false,
            'color'          => 'yellow',
            'format'         => 'json',
        );

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->andReturn($entity)->times(3);

        $transport = new APIVersion1('123', 'egg', 'spoon');
        $transport->setHttp($http);

        Hippy::init(null, null, null, $transport);

        Hippy::add('test 1');
        Hippy::addHtml('test 2');
        Hippy::add('test 3');

        Hippy::go();
    }

    public function resetClient()
    {
        $class = new ReflectionClass('rcrowe\Hippy\Facade');
        $property = $class->getProperty('client');
        $property->setAccessible(true);
        $property->setValue(null);
    }

    public function setQueue($queue)
    {
        $class = new ReflectionClass('rcrowe\Hippy\Facade');
        $property = $class->getProperty('queue');
        $property->setAccessible(true);
        $property->setValue($queue);
    }
}
