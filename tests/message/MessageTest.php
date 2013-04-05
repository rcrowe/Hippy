<?php

namespace rcrowe\Hippy\Tests\Message;

use rcrowe\Hippy\Message;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    public function testDefaultInstance()
    {
        $message = new Message;

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

    public function testSetBackgroundColor()
    {
        $message = new Message(false, 'green');
        $this->assertEquals($message->getBackgroundColor(), 'green');

        $message = new Message(false, 'red');
        $message->setBackgroundColor('random');
        $this->assertEquals($message->getBackgroundColor(), 'random');
    }

    public function testSetHtml()
    {
        $message = new Message;
        $message->setHtml('<a href="#">hello</a>');
        $this->assertEquals($message->getMessage(), '&lt;a href=&quot;#&quot;&gt;hello&lt;/a&gt;');
    }

    public function testSetText()
    {
        $message = new Message;

        $message->setText('egg and spoon race');
        $this->assertEquals($message->getMessage(), 'egg and spoon race');

        $message->setText('<a href="#">hello</a>');
        $this->assertEquals($message->getMessage(), '<a href="#">hello</a>');
    }
}
