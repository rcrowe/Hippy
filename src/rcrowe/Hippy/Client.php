<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy;

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
        $messages = (!$msg->isQueue()) ? array($msg) : $msg;

        foreach ($messages as $message) {
            $this->transport->send($message);
        }
    }
}
