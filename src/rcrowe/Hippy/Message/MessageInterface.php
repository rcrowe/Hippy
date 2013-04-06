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
 * All messages sent or added to a queue must implement this.
 */
interface MessageInterface
{
    /**
     * @var string Message format is plain text.
     */
    const FORMAT_TEXT = 'text';

    /**
     * @var string Message format is html.
     */
    const FORMAT_HTML = 'html';

    /**
     * @var string Message background color is yellow.
     */
    const BACKGROUND_YELLOW = 'yellow';

    /**
     * @var string Message background color is red.
     */
    const BACKGROUND_RED = 'red';

    /**
     * @var string Message background color is green.
     */
    const BACKGROUND_GREEN = 'green';

    /**
     * @var string Message background color is purple.
     */
    const BACKGROUND_PURPLE = 'purple';

    /**
     * @var string Message background color is gray.
     */
    const BACKGROUND_GRAY = 'gray';

    /**
     * @var string Message background color is random.
     */
    const BACKGROUND_RANDOM = 'random';

    /**
     * Get a new instance of message.
     *
     * @param bool   $notify           Should this message create a notification.
     * @param string $background_color Background color of message.
     */
    public function __construct($notify = false, $background_color = 'yellow');

    /**
     * Will this message generate a notification.
     *
     * @return bool
     */
    public function getNotification();

    /**
     * Set that the message will generate a notification.
     *
     * @return void
     */
    public function setNotification();

    /**
     * Set that the message will not generate a notification.
     *
     * @return void
     */
    public function removeNotification();

    /**
     * Get the background color.
     *
     * @return string
     */
    public function getBackgroundColor();

    /**
     * Set the background color
     *
     * @param string $color.
     * @return void
     */
    public function setBackgroundColor($color);

    /**
     * Set the message as HTML.
     *
     * @param string $html
     * @throws InvalidArgumentException If message length is greater than 10000 characters
     * @return void
     */
    public function setHtml($html);

    /**
     * Set the message as plain text.
     *
     * @param string $text
     * @throws InvalidArgumentException If message length is greater than 10000 characters
     * @return void
     */
    public function setText($text);

    /**
     * Get the message.
     *
     * @return string
     */
    public function getMessage();

    /**
     * Get which format the message is in.
     *
     * @return string
     */
    public function getMessageFormat();
}