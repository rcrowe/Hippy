<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy;

use rcrowe\Hippy\Transport\Guzzle;
use rcrowe\Hippy\Client;
use rcrowe\Hippy\Queue;
use rcrowe\Hippy\Message;
use RuntimeException;

/**
 * Static interface for the Hippy library.
 */
class Facade
{
    /**
     * @var \rcrowe\Hippy\Client
     */
    protected static $client;

    /**
     * @var \rcrowe\Hippy\Queue
     */
    protected static $queue;

    /**
     * Initialise the facade. Must be called first.
     *
     * @param string                                     $token API token.
     * @param string|int                                 $room  Room to send message to.
     * @param string                                     $from  Who the message is from.
     * @param \rcrowe\Hippy\Transport\TransportInterface $transport
     */
    public static function init($token, $room, $from, $transport = null)
    {
        if ($transport === null) {
            $transport = new Guzzle($token, $room, $from);
        }

        static::$client = new Client($transport);
        static::$queue  = new Queue;
    }

    /**
     * Send a plain text message.
     *
     * @param string $msg
     * @param bool   $notify
     * @param string $background
     * @throws RuntimeException When Facade::init() has not been called.
     * @return void
     */
    public static function text($msg, $notify = false, $background = Message::BACKGROUND_YELLOW)
    {
        if (static::$client === null) {
            throw new RuntimeException('Must call init first');
        }

        $message = new Message($notify, $background);
        $message->setText($msg);

        static::$client->send($message);
    }

    /**
     * Send a html message.
     *
     * @param string $msg
     * @param bool   $notify
     * @param string $background
     * @throws RuntimeException When Facade::init() has not been called.
     * @return void
     */
    public static function html($msg, $notify = false, $background = Message::BACKGROUND_YELLOW)
    {
        if (static::$client === null) {
            throw new RuntimeException('Must call init first');
        }

        $message = new Message($notify, $background);
        $message->setHtml($msg);

        static::$client->send($message);
    }

    /**
     * Add a plain text message to the queue.
     *
     * @param string $msg
     * @param bool   $notify
     * @param string $background
     * @throws RuntimeException When Facade::init() has not been called.
     * @return void
     */
    public static function add($msg, $notify = false, $background = Message::BACKGROUND_YELLOW)
    {
        if (static::$client === null) {
            throw new RuntimeException('Must call init first');
        }

        $message = new Message($notify, $background);
        $message->setText($msg);

        static::$queue->add($message);
    }

    /**
     * Add a html message to the queue.
     *
     * @param string $msg
     * @param bool   $notify
     * @param string $background
     * @throws RuntimeException When Facade::init() has not been called.
     * @return void
     */
    public static function addHtml($msg, $notify = false, $background = Message::BACKGROUND_YELLOW)
    {
        if (static::$client === null) {
            throw new RuntimeException('Must call init first');
        }

        $message = new Message($notify, $background);
        $message->setHtml($msg);

        static::$queue->add($message);
    }

    /**
     * Send all messages in the queue.
     *
     * @throws RuntimeException When Facade::init() has not been called.
     * @return void
     */
    public static function go()
    {
        if (static::$client === null) {
            throw new RuntimeException('Must call init first');
        }

        static::$client->send(static::$queue);
    }
}
