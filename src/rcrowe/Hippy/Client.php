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

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 */
class Client
{
    /**
     * @var string Admin or notification token.
     */
    protected $token;

    /**
     * @var string|int Name or id of the room.
     */
    protected $room;

    /**
     * @var string Name the message will be sent from.
     */
    protected $from;

    /**
     * @var \rcrowe\Hippy\Transport\TransportInterface
     */
    protected $transport;

    /**
     * Create a new instance on the client
     *
     * @param \rcrowe\Hippy\Transport\TransportInterface $transport
     */
    public function __construct(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Get the token used to authenticate with.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->transport->getToken();
    }

    /**
     * Set the token used to authenticate with.
     *
     * @param string $token API token. See https://{domain}.hipchat.com/admin/api.
     * @return void
     */
    public function setToken($token)
    {
        $this->transport->setToken($token);
    }

    /**
     * Get the room the message will be sent to.
     *
     * @return string|int
     */
    public function getRoom()
    {
        return $this->transport->getRoom();
    }

    /**
     * Set the room that the message will be sent to.
     *
     * @param string $room Room name or id.
     * @return void
     */
    public function setRoom($room)
    {
        $this->transport->setRoom($room);
    }

    /**
     * Get who the message will be sent from.
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->transport->getFrom();
    }

    /**
     * Set who the message will be sent from.
     *
     * @param string $from Message will be sent by this user.
     * @return void
     */
    public function setFrom($from)
    {
        $this->transport->setFrom($from);
    }

    /**
     * Return the TransportInterface that will actually send the message.
     *
     * @return \rcrowe\Hippy\Transport\TransportInterface
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set the instance of TransportInterface that will actually send the message.
     *
     * @param \rcrowe\Hippy\Transport\TransportInterface $transport
     * @return void
     */
    public function setTransport(TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Send a single message or a queue of messages. A queue must implement \Iterator in order to work.
     *
     * @param \rcrowe\Hippy\Message\SenderInterface $msg
     * @return void
     */
    public function send(SenderInterface $msg)
    {
        // $messages = (!$msg->isQueue()) ? array($msg) : $msg;
        $messages = (!is_a($msg, 'Iterator')) ? array($msg) : $msg;

        foreach ($messages as $message) {
            $this->transport->send($message);
        }
    }
}
