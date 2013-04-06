<?php

namespace rcrowe\Hippy\Tests\Client;

use Mockery as m;
use rcrowe\Hippy\Client as Hippy;
use rcrowe\Hippy\Transport\Guzzle;
use rcrowe\Hippy\Message;
use rcrowe\Hippy\Queue;

class SendTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testSingleMessage()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->once();

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->andReturn($entity)->once();

        $transport = new Guzzle('123', 'cog', 'vivalacrowe');
        $transport->setHttp($http);
        $guzzle = new Hippy($transport);

        $message = new Message(true, 'red');

        $guzzle->send($message);
    }

    public function testQueuedMessages()
    {
        $entity = m::mock('Guzzle\Http\Message\EntityEnclosingRequest');
        $entity->shouldReceive('send')->twice();

        $http = m::mock('Guzzle\Http\Client');
        $http->shouldReceive('post')->andReturn($entity)->twice();

        $transport = new Guzzle('123', 'cog', 'vivalacrowe');
        $transport->setHttp($http);
        $hippy = new Hippy($transport);

        $queue = new Queue;
        $queue->add(new Message(true, 'red'));
        $queue->add(new Message(false, 'random'));

        $hippy->send($queue);
    }
}
