<?php

namespace rcrowe\Hippy\Tests\Message;

use rcrowe\Hippy\Message;

class BackgroundColorTest extends \PHPUnit_Framework_TestCase
{
    public function testSet()
    {
        $message = new Message(false, 'random');
        $this->assertEquals($message->getBackgroundColor(), 'random');

        $message = new Message(false, 'red');
        $message->setBackgroundColor('random');
        $this->assertEquals($message->getBackgroundColor(), 'random');
    }

    public function testYellow()
    {
        $message = new Message(false, Message::BACKGROUND_YELLOW);
        $this->assertEquals($message->getBackgroundColor(), 'yellow');
    }

    public function testRed()
    {
        $message = new Message(false, Message::BACKGROUND_RED);
        $this->assertEquals($message->getBackgroundColor(), 'red');
    }

    public function testGreen()
    {
        $message = new Message(false, Message::BACKGROUND_GREEN);
        $this->assertEquals($message->getBackgroundColor(), 'green');
    }

    public function testPurple()
    {
        $message = new Message(false, Message::BACKGROUND_PURPLE);
        $this->assertEquals($message->getBackgroundColor(), 'purple');
    }

    public function testGray()
    {
        $message = new Message(false, Message::BACKGROUND_GRAY);
        $this->assertEquals($message->getBackgroundColor(), 'gray');
    }
}
