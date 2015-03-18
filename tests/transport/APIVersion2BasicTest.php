<?php

namespace rcrowe\Hippy\Tests\Transport;

use rcrowe\Hippy\Transport\APIVersion2;
use Guzzle\Http\Client as Http;
use ReflectionMethod;

class APIVersion2BasicTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultInstance()
    {
        $guzzle = new APIVersion2('123', 'egg', 'vivalacrowe');

        $this->assertTrue(is_a($guzzle, 'rcrowe\Hippy\Transport\TransportInterface'));
        $this->assertEquals($guzzle->getToken(), '123');
        $this->assertEquals($guzzle->getRoom(), 'egg');
        $this->assertEquals($guzzle->getFrom(), 'vivalacrowe');
        $this->assertEquals($guzzle->getEndpoint(), 'https://api.hipchat.com/v2/');
        $this->assertTrue(is_a($guzzle->getHttp(), 'Guzzle\Http\Client'));
        $this->assertEquals($guzzle->getHttp()->getBaseUrl(), 'https://api.hipchat.com/v2/');

        $headers = $guzzle->getHeaders();
        $this->assertEquals(count($headers), 1);
        $this->assertEquals($headers['Content-type'], 'application/json');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testBadEndpoint()
    {
        $guzzle = new APIVersion2('123', 'egg', 'vivalacrowe', 'hello');
    }

    public function testSetEndpoint()
    {
        $guzzle = new APIVersion2('123', 'egg', 'vivalacrowe');
        $guzzle->setEndpoint('https://api.hipchat.com/v23890490234/');

        $this->assertEquals($guzzle->getEndpoint(), 'https://api.hipchat.com/v23890490234/');
    }

    public function testSetHeaders()
    {
        $guzzle = new APIVersion2(null, null, null);
        $guzzle->setHeaders(array('egg' => 'spoon'));

        $headers = $guzzle->getHeaders();
        $this->assertEquals(count($headers), 1);
        $this->assertEquals($headers['egg'], 'spoon');
    }

    public function testSetHttp()
    {
        $guzzle = new APIVersion2(null, null, null);
        $guzzle->setHttp(new Http('https://api.cogpowered.com/v1/'));

        $this->assertEquals($guzzle->getHttp()->getBaseUrl(), 'https://api.cogpowered.com/v1/');
    }

    public function testGetUri()
    {
        $guzzle = new APIVersion2('51423', 'room123', null);

        $method = new ReflectionMethod('rcrowe\Hippy\Transport\APIVersion2', 'getUri');
        $method->setAccessible(true);

        $this->assertEquals($method->invoke($guzzle), 'room/room123/notification?auth_token=51423');
    }
}
