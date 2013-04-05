<?php

namespace rcrowe\Hippy\Transport;

use rcrowe\Hippy\Message\MessageInterface;

class Guzzle implements TransportInterface
{
    protected $token;
    protected $room;
    protected $from;
    protected $endpoint;

    public function __construct($token, $room, $from, $endpoint = '')
    {
        $this->token    = $token;
        $this->room     = $room;
        $this->from     = $from;
        $this->endpoint = $endpoint;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getRoom()
    {
        return $this->room;
    }

    public function setRoom($room)
    {
        $this->room = $room;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function send(MessageInterface $message)
    {

    }
}
