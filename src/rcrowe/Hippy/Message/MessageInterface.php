<?php

namespace rcrowe\Hippy\Message;

interface MessageInterface
{
    const FORMAT_TEXT = 'text';
    const FORMAT_HTML = 'html';

    public function __construct($notify = false, $background_color = 'yellow');
    public function getNotification();
    public function setNotification();
    public function removeNotification();
    public function getBackgroundColor();
    public function setBackgroundColor($color);
    public function setHtml($html);
    public function setText($text);
    public function getMessage();
    public function getMessageFormat();
}