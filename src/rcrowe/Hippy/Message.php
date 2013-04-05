<?php

namespace rcrowe\Hippy;

use rcrowe\Hippy\Message\SenderInterface;
use rcrowe\Hippy\Message\MessageInterface;

class Message implements SenderInterface, MessageInterface
{
    protected $notification;
    protected $background_color;
    protected $message;
    protected $message_format;

    public function __construct($notify = false, $background_color = 'yellow')
    {
        $this->notification     = $notify;
        $this->background_color = $background_color;
    }

    /**
     * {@inheritdoc}
     */
    public function getNotification()
    {
        return $this->notification;
    }

    public function setNotification()
    {
        $this->notification = true;
    }

    public function removeNotification()
    {
        $this->notification = false;
    }

    public function getBackgroundColor()
    {
        return $this->background_color;
    }

    public function setBackgroundColor($color)
    {
        // throw exception here if not a valid color
        $this->background_color = $color;
    }

    public function setHtml($html)
    {
        // According to the docs I need to encode
        // but when the message is output in the room, HTML is escaped
        // Sending as text seems to work for now
        // $this->setMessage(htmlspecialchars($html));

        $this->setMessage($html, static::FORMAT_HTML);
    }

    public function setText($text)
    {
        $this->setMessage($text, static::FORMAT_TEXT);
    }

    protected function setMessage($text, $format)
    {
        // throw exception if longer than 10,000 chars
        $this->message        = $text;
        $this->message_format = $format;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getMessageFormat()
    {
        return $this->message_format;
    }
}
