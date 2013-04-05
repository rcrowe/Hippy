<?php

namespace rcrowe\Hippy\Transport;

use rcrowe\Hippy\Message\MessageInterface;

interface TransportInterface
{
    public function __construct($token, $room, $from, $endpoint);
    public function getToken();
    public function setToken($token);
    public function getRoom();
    public function setRoom($room);
    public function getFrom();
    public function setFrom($from);
    public function getEndpoint();
    public function setEndpoint($endpoint);
    public function getHeaders();
    public function setHeaders(array $headers);
    public function send(MessageInterface $message);
}
