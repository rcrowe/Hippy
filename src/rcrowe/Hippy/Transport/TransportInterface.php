<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy\Transport;

use rcrowe\Hippy\Message\MessageInterface;

/**
 * Does the actual sending of the message to Hipchat.
 */
interface TransportInterface
{
    /**
     * Create a new instance.
     *
     * @param string     $token    API token.
     * @param string|int $room     Room to send message to.
     * @param string     $from     Who the message is from.
     * @param string     $endpoint API host.
     * @throws InvalidArgumentException When endpoint is not a valid URL.
     */
    public function __construct($token, $room, $from, $endpoint);

    /**
     * Get the token used to authenticate with.
     *
     * @return string
     */
    public function getToken();

    /**
     * Set the token used to authenticate with.
     *
     * @param string $token API token. See https://{domain}.hipchat.com/admin/api.
     * @return void
     */
    public function setToken($token);

    /**
     * Get the room the message will be sent to.
     *
     * @return string|int
     */
    public function getRoom();

    /**
     * Set the room that the message will be sent to.
     *
     * @param string $room Room name or id.
     * @return void
     */
    public function setRoom($room);

    /**
     * Get who the message will be sent from.
     *
     * @return string
     */
    public function getFrom();

    /**
     * Set who the message will be sent from.
     *
     * @param string $from Message will be sent by this user.
     * @return void
     */
    public function setFrom($from);

    /**
     * Get the API host we will send the message to.
     *
     * @return string
     */
    public function getEndpoint();

    /**
     * Set the API host to send the message to.
     *
     * @param string $endpoint API host.
     * @return void
     */
    public function setEndpoint($endpoint);

    /**
     * Get the headers that will be sent with the message.
     *
     * @return array
     */
    public function getHeaders();

    /**
     * Set the headers that will be sent with the message.
     *
     * @param array $headers Key value array
     * @return void
     */
    public function setHeaders(array $headers);

    /**
     * Send the message.
     *
     * @param \rcrowe\Hippy\Message\MessageInterface $message
     * @throws InvalidArgumentException When required params not set
     * @throws \Guzzle\Common\Exception\GuzzleException
     * @return void
     */
    public function send(MessageInterface $message);
}
