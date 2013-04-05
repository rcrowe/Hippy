<?php

namespace rcrowe\Hippy;

use rcrowe\Hippy\Message\SenderInterface;
use rcrowe\Hippy\Message\MessageInterface;

class Message implements SenderInterface, MessageInterface
{
    protected $notification;
    protected $background_color;
    protected $message;

    public function __construct($notify = false, $background_color = 'yellow')
    {
        $this->notification     = $notify;
        $this->background_color = $background_color;
    }

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
        $this->message = htmlentities($html);
    }

    public function setText($text)
    {
        $this->message = $text;
    }

    public function getMessage()
    {
        return $this->message;
    }
}
