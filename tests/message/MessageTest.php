<?php

namespace rcrowe\Hippy\Tests\Message;

use rcrowe\Hippy\Message;
use InvalidArgumentException;
use Exception;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultInstance()
    {
        $message = new Message;

        $this->assertTrue(is_a($message, 'rcrowe\Hippy\Message\MessageInterface'));
        $this->assertFalse($message->getNotification());
        $this->assertEquals($message->getBackgroundColor(), 'yellow');
    }

    public function testSetNotification()
    {
        $message = new Message(true);
        $this->assertTrue($message->getNotification());

        $message = new Message;
        $message->setNotification();
        $this->assertTrue($message->getNotification());
    }

    public function testRemoveNotification()
    {
        $message = new Message(true);
        $message->removeNotification();
        $this->assertFalse($message->getNotification());
    }

    public function testPlainMessageLength()
    {
        $msg = str_pad('', 9999, 'jnk3j1');
        $message = new Message;
        $message->setText($msg);

        $msg = str_pad('', 10000, 'jnk3j1');
        $message = new Message;
        $message->setText($msg);

        try {
            $msg = str_pad('', 10001, 'jnk3j1');
            $message = new Message;
            $message->setText($msg);
            $this->assertFalse(true);
        } catch (InvalidArgumentException $ex) {
            $this->assertEquals($ex->getMessage(), 'Message more than 10,000 characters');
        } catch (Exception $ex) {
            $this->assertFalse(true);
        }
    }

    public function testHtmlMessageLength()
    {
        $msg = str_pad('', 9999, 'jnk3j1');
        $message = new Message;
        $message->setHtml($msg);

        $msg = str_pad('', 10000, 'jnk3j1');
        $message = new Message;
        $message->setHtml($msg);

        try {
            $msg = str_pad('', 10001, 'jnk3j1');
            $message = new Message;
            $message->setHtml($msg);
            $this->assertFalse(true);
        } catch (InvalidArgumentException $ex) {
            $this->assertEquals($ex->getMessage(), 'Message more than 10,000 characters');
        } catch (Exception $ex) {
            $this->assertFalse(true);
        }
    }
}
