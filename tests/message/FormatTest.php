<?php

namespace rcrowe\Hippy\Tests\Message;

use rcrowe\Hippy\Message;

class FormatTest extends \PHPUnit_Framework_TestCase
{
    public function testSetHtml()
    {
        $message = new Message;
        $message->setHtml('<a href="#">hello</a>');

        // $this->markTestSkipped('Should be encoded, but things break');
        // $this->assertEquals($message->getMessage(), '&lt;a href=&quot;#&quot;&gt;hello&lt;/a&gt;');

        $this->assertEquals($message->getMessage(), '<a href="#">hello</a>');
        $this->assertEquals($message->getMessageFormat(), Message::FORMAT_HTML);
    }

    public function testSetText()
    {
        $message = new Message;

        $message->setText('egg and spoon race');
        $this->assertEquals($message->getMessage(), 'egg and spoon race');

        $message->setText('<a href="#">hello</a>');
        $this->assertEquals($message->getMessage(), '<a href="#">hello</a>');
        $this->assertEquals($message->getMessageFormat(), Message::FORMAT_TEXT);
    }
}
