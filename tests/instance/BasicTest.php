<?php

namespace rcrowe\Hippy\Tests\Instance;

use rcrowe\Hippy\Client as Hippy;
use rcrowe\Hippy\Transport\Guzzle as Transport;

class BasicTest extends \PHPUnit_Framework_TestCase
{
    public function testToken()
    {
        $transport = new Transport(null, null, null);
        $hippy     = new Hippy($transport);
        $this->assertNull($hippy->getToken());
        $hippy->setToken('12345');
        $this->assertEquals($hippy->getToken(), '12345');

        $transport = new Transport('54321', null, null);
        $hippy     = new Hippy($transport);
        $this->assertEquals($hippy->getToken(), '54321');
    }

    public function testRoom()
    {
        $transport = new Transport(null, null, null);
        $hippy     = new Hippy($transport);
        $this->assertNull($hippy->getRoom());
        $hippy->setRoom('general');
        $this->assertEquals($hippy->getRoom(), 'general');

        $transport = new Transport(null, 'chilli', null);
        $hippy     = new Hippy($transport);
        $this->assertEquals($hippy->getRoom(), 'chilli');
    }

    public function testFrom()
    {
        $transport = new Transport(null, null, null);
        $hippy     = new Hippy($transport);
        $this->assertNull($hippy->getFrom());
        $hippy->setFrom('rcrowe');
        $this->assertEquals($hippy->getFrom(), 'rcrowe');

        $transport = new Transport(null, null, 'vivalacrowe');
        $hippy = new Hippy($transport);
        $this->assertEquals($hippy->getFrom(), 'vivalacrowe');
    }

    public function testTransport()
    {
        $transport = new Transport(null, null, null);
        $hippy = new Hippy($transport);
        $this->assertEquals(get_class($hippy->getTransport()), 'rcrowe\Hippy\Transport\Guzzle');

        $transport = new Transport(null, null, null);
        $transport->helloWorld = 'egg';
        $hippy = new Hippy($transport);
        $hippy->setTransport($transport);

        $this->assertEquals($hippy->getTransport()->helloWorld, 'egg');
    }
}