<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy;

/*

$transport = new rcrowe\Hippy\Transport\Guzzle($token, $room, $from);
$hippy = new rcrowe\Hippy\Client($transport);

$message = new rcrowe\Hippy\Message(true, 'yellow');
$message = new rcrowe\Hippy\Message;
$message->addNotification();
$message->removeNotification();
$message->setBackgroundColor('yellow');
$message->setHtml('<a href="#">test</a>');
$message->setText('test');

$queue = new rcrowe\Hippy\Queue;
$queue->add($message);

$hippy->send($message);
$hippy->send($queue);

*/

use rcrowe\Hippy\Transport\TransportInterface;
use rcrowe\Hippy\Transport\Guzzle as DefaultTransport;
use rcrowe\Hippy\Message\SenderInterface;

class Client
{
    /**
     * @var string Admin or notification token.
     */
    protected $token;

    /**
     * @var string|int ID or name of the room.
     */
    protected $room;

    /**
     * @var string Name the message will appear be sent from.
     */
    protected $from;

    /**
     * @var \rcrowe\Hippy\Transport\TransportInterface
     */
    protected $transport;


    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public function getToken()
    {
        return $this->transport->getToken();
    }

    public function setToken($token)
    {
        $this->transport->setToken($token);
    }

    public function getRoom()
    {
        return $this->transport->getRoom();
    }

    public function setRoom($room)
    {
        $this->transport->setRoom($room);
    }

    public function getFrom()
    {
        return $this->transport->getFrom();
    }

    public function setFrom($from)
    {
        $this->transport->setFrom($from);
    }

    public function getTransport()
    {
        return $this->transport;
    }

    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    public function send(SenderInterface $msg)
    {
        $messages = (!is_a($msg, 'rcrowe\Hippy\Message\Queue')) ? array($msg) : $msg;

        foreach ($messages as $message) {
            $this->transport->send($message);
        }
    }
}
