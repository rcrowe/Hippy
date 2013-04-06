<?php

namespace rcrowe\Hippy\Tests\Facade;

use rcrowe\Hippy\Facade as Hippy;
use rcrowe\Hippy\Transport\Guzzle;
use ReflectionClass;

class InitTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultInit()
    {
        Hippy::init('123', 'hippy', 'Rob');

        list($client, $queue) = $this->getObjects();

        $this->assertEquals(get_class($client), 'rcrowe\Hippy\Client');
        $this->assertEquals(get_class($queue), 'rcrowe\Hippy\Queue');

        $this->assertEquals($client->getToken(), '123');
        $this->assertEquals($client->getRoom(), 'hippy');
        $this->assertEquals($client->getFrom(), 'Rob');
    }

    public function testCustomTransportInit()
    {
        $guzzle = new Guzzle('54321', 'cog', 'vivalacrowe');
        Hippy::init('123', 'hippy', 'Rob', $guzzle);

        list($client, $queue) = $this->getObjects();

        $this->assertEquals(get_class($client), 'rcrowe\Hippy\Client');
        $this->assertEquals(get_class($queue), 'rcrowe\Hippy\Queue');

        $this->assertEquals($client->getToken(), '54321');
        $this->assertEquals($client->getRoom(), 'cog');
        $this->assertEquals($client->getFrom(), 'vivalacrowe');
    }

    public function testMultipleInit()
    {
        Hippy::init('321', 'hippy', 'Rob');
        Hippy::init('123', 'hippy', 'Rob');

        list($client, $queue) = $this->getObjects();

        $this->assertEquals($client->getToken(), '123');
    }

    public function getObjects()
    {
        $class = new ReflectionClass('rcrowe\Hippy\Facade');

        $property = $class->getProperty('client');
        $property->setAccessible(true);
        $client = $property->getValue($class);

        $property = $class->getProperty('queue');
        $property->setAccessible(true);
        $queue = $property->getValue($class);

        return array($client, $queue);
    }
}
