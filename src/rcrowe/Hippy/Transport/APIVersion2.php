<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Iñaki Abete <inakiabt@gmail.com>
 * @copyright Copyright (c) 2015, Iñaki Abete.
 * @license MIT
 */

namespace rcrowe\Hippy\Transport;

use InvalidArgumentException;
use rcrowe\Hippy\Message\MessageInterface;
use rcrowe\Hippy\Transport\Guzzle;
use Guzzle\Http\Client as Http;
use Guzzle\Http\ClientInterface as HttpInterface;

/**
 * Uses Guzzle to send the message to Hipchat. Uses cURL.
 */
class APIVersion2 extends Guzzle
{
    /**
     * {@inheritdoc}
     */
    public function __construct($token, $room, $from, $endpoint = 'https://api.hipchat.com/v2/')
    {
        parent::__construct($token, $room, $from, $endpoint);
    }

    /**
     * Uri of the request URL to the Hipchat API.
     *
     * @return string
     */
    protected function getUri()
    {
        return 'room/'.$this->getRoom().'/notification?auth_token='.$this->getToken();
    }

    /**
     * {@inheritdoc}
     */
    protected function buildData(MessageInterface $message)
    {
        // Build up the data we are sending to Hipchat
        return json_encode(array(
            'message'        => $message->getMessage(),
            'message_format' => $message->getMessageFormat(),
            'notify'         => $message->getNotification(),
            'color'          => $message->getBackgroundColor()
        ));
    }
}
