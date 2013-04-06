<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy;

use rcrowe\Hippy\Message\SenderInterface;
use rcrowe\Hippy\Message\MessageInterface;
use InvalidArgumentException;

/**
 * Holds the message and meta data about the message.
 */
class Message implements SenderInterface, MessageInterface
{
    /**
     * @var bool Does this message generate a notification. Default false.
     */
    protected $notification;

    /**
     * @var string Background color of the message. Default green.
     */
    protected $background_color;

    /**
     * @var string Actual message.
     */
    protected $message;

    /**
     * @var string Is the message plain text or html.
     */
    protected $message_format;

    /**
     * {@inheritdoc}
     */
    public function __construct($notify = false, $background_color = self::BACKGROUND_YELLOW)
    {
        // Use constant for the color
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

    /**
     * {@inheritdoc}
     */
    public function setNotification()
    {
        $this->notification = true;
    }

    /**
     * {@inheritdoc}
     */
    public function removeNotification()
    {
        $this->notification = false;
    }

    /**
     * {@inheritdoc}
     */
    public function getBackgroundColor()
    {
        return $this->background_color;
    }

    /**
     * {@inheritdoc}
     */
    public function setBackgroundColor($color)
    {
        // throw exception here if not a valid color
        $this->background_color = $color;
    }

    /**
     * {@inheritdoc}
     */
    public function setHtml($html)
    {
        // According to the docs I need to encode
        // but when the message is output in the room, HTML is escaped
        // Sending as text seems to work for now
        // $this->setMessage(htmlspecialchars($html));

        $this->setMessage($html, static::FORMAT_HTML);
    }

    /**
     * {@inheritdoc}
     */
    public function setText($text)
    {
        $this->setMessage($text, static::FORMAT_TEXT);
    }

    /**
     * @param string $text
     * @param string format
     * @return void
     */
    protected function setMessage($text, $format)
    {
        if (strlen($text) > 10000) {
            throw new InvalidArgumentException('Message more than 10,000 characters');
        }

        // throw exception if longer than 10,000 chars
        $this->message        = $text;
        $this->message_format = $format;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageFormat()
    {
        return $this->message_format;
    }
}
