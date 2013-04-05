<?php

namespace rcrowe\Hippy\Transport;

use InvalidArgumentException;
use rcrowe\Hippy\Message\MessageInterface;
use Guzzle\Http\Client as Http;
use Guzzle\Http\ClientInterface as HttpInterface;

class Guzzle implements TransportInterface
{
    protected $token;
    protected $room;
    protected $from;
    protected $endpoint;
    protected $http;
    protected $headers = array(
        'Content-type' => 'application/x-www-form-urlencoded'
    );

    public function __construct($token, $room, $from, $endpoint = 'https://api.hipchat.com/v1/')
    {
        $this->token = $token;
        $this->room  = $room;
        $this->from  = $from;

        // Make sure is actually a URL
        if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException('Endpoint is not a URL');
        }

        $this->endpoint = $endpoint;
        $this->http     = new Http($this->endpoint);
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

    public function getHeaders()
    {
        return $this->headers;
    }

    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    public function getHttp()
    {
        return $this->http;
    }

    public function setHttp(HttpInterface $http)
    {
        $this->http = $http;
    }

    protected function getUri()
    {
        return 'rooms/message?format=json&auth_token='.$this->getToken();
    }

    public function send(MessageInterface $message)
    {
        // Validate we have everything we need
        foreach (array('token', 'room', 'from') as $variable) {
            if (empty($this->$variable)) {
                throw new InvalidArgumentException("Invalid `$variable`");
            }
        }

        // Build up the data we are sending to Hipchat
        $data = array(
            'room_id'        => $this->getRoom(),
            'from'           => $this->getFrom(),
            'message'        => $message->getMessage(),
            'message_format' => $message->getMessageFormat(),
            'notify'         => $message->getNotification(),
            'color'          => $message->getBackgroundColor(),
            'format'         => 'json',
        );

        return $this->http->post($this->getUri(), $this->getHeaders(), http_build_query($data))->send();
    }
}
