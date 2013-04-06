<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy\Transport;

use InvalidArgumentException;
use rcrowe\Hippy\Message\MessageInterface;
use Guzzle\Http\Client as Http;
use Guzzle\Http\ClientInterface as HttpInterface;

/**
 * Uses Guzzle to send the message to Hipchat. Uses cURL.
 */
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * {@inheritdoc}
     */
    public function getRoom()
    {
        return $this->room;
    }

    /**
     * {@inheritdoc}
     */
    public function setRoom($room)
    {
        $this->room = $room;
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;
    }

    /**
     * Get the instance of Guzzle used to send the message.
     *
     * @return \Guzzle\Http\ClientInterface
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * Set the instance of Guzzle used to send the message.
     *
     * @param \Guzzle\Http\ClientInterface $http
     * @return void
     */
    public function setHttp(HttpInterface $http)
    {
        $this->http = $http;
    }

    /**
     * Uri of the request URL to the Hipchat API.
     *
     * @return string
     */
    protected function getUri()
    {
        return 'rooms/message?format=json&auth_token='.$this->getToken();
    }

    /**
     * {@inheritdoc}
     */
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
        $data = http_build_query($data, '', '&');

        return $this->http->post($this->getUri(), $this->getHeaders(), $data)->send();
    }
}
