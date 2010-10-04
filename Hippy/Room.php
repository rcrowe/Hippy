<?php

//Include neccessery Hippy files
require_once 'Base.php';
require_once 'Request.php';

/**
 * Allows you to interact with a HipChat room. Use {@link Hippy::room()} to get an instance of Hippy_Room
 */
class Hippy_Room extends Hippy_Base
{
    /**
     * Class constructor. Currently does not do anythin.
     */
    public function __construct() { }
    
    /**
     * Send a message to a HipChat room using settings set with {@link Hippy::settings()} or passing them
     * in as parameter 2.
     *
     * @param string       $msg    Message to send to the room. Text is UTF8 encoded.
     * @param array|string $config Either an array of settings or API token.
     *
     * @throws HippyException
     *
     * @return Hippy_Room Returns a room instance so you can chain your calls
     */
    public function speak($msg, $config = NULL)
    {
        //Set any settings
        parent::settings($config);
        
        //Before we try sending a message, make sure all the mandatory settings are set
        parent::checkSettings();
        
        //Actually send the message
        //We arent concerned with the json returned
        Hippy_Request::make_request('rooms/message', array('message' => utf8_encode($msg)));
    
        //Return copy of self so we can chain calls
        return $this;
    }
    
    /**
     * Set who the message is from.
     *
     * @param string $from Name of user who is sending the message
     *
     * @return Hippy_Room Copy of self so we can chain calls
     */
    public function from($from)
    {
        //Set `from` setting to name of user
        parent::$config['from'] = $from;
    
        //Return copy of self so we can chain calls
        return $this;
    }
    
    /**
     * Set whether notify users in the room of your message.
     *
     * @param bool $notify True/False of whether to notify
     *
     * @return Hippy_Room Copy of self so we can chain calls
     */
    public function notify($notify)
    {
        //Set `notify` setting
        parent::$config['notify'] = $notify;
    
        //Return copy of self so we can chain calls
        return $this;
    }
}

?>