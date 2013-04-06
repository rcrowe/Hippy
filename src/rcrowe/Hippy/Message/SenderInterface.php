<?php

/**
 * PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * @author Rob Crowe <hello@vivalacrowe.com>
 * @copyright Copyright (c) 2013, Rob Crowe.
 * @license MIT
 */

namespace rcrowe\Hippy\Message;

/**
 * Messages / Queues passed to \rcrowe\Hippy\Client::send() must implement this interface.
 */
interface SenderInterface
{
}