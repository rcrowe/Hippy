<?php

require 'Hippy.php';

/**
 * Phing class for sending messages to a HipChat room
 */
class PhingHippy extends Task
{
    /**
     * Authentication token from HipChat
     */
    private $token;
    
    /**
     * Room to send message too
     */
    private $room;
    
    /**
     * Who the message is from
     */
    private $from;
    
    /**
     * Whether to notify users of new message
     */
    private $notify = true;
    
    /**
     * Individual message to send
     */
    private $message;
    
    /**
     * Holds multiple messages to send from <message>
     */
    private $messages = array();
    
    /**
     * Set authenticated token
     */
    public function setToken($token) {
    
        if(empty($token)) {
            throw new BuildException("Make sure you set your authentication token", $this->location);
        }
        
        //Hippy takes care of checking settings
        $this->token = $token;
    }
    
    /**
     * Set room name or id to send message too
     */
    public function setRoom($room) {
    
        if(empty($room)) {
            throw new BuildException("Make sure you set the room to send messages too", $this->location);
        }
    
        //Hippy takes care of checking settings
        $this->room = $room;
    }
    
    /**
     * Set who the message is sent from
     */
    public function setFrom($from) {
    
        if(empty($from)) {
            throw new BuildException("Make sure you set who the message is from", $this->location);
        }
    
        //Hippy takes care of checking settings
        $this->from = $from;
    }
    
    /**
     * Set whether to notify end users of message
     */
    public function setNotify($notify) {
    
        //Hippy takes care of checking settings
        $this->notify = $notify;
    }
    
    /**
     * Actual message to send
     */
    public function setMessage($message) {
    
        //Hippy takes care of checking settings
        $this->message = $message;
    }
    
    /**
     * Handle <speak> tag for multiple messages
     */
    public function createSpeak() {
    
        $num = array_push($this->messages, new Speak());
        return $this->messages[$num-1];
    }
    
    public function main() {
    
        //Set Hippy settings
        $config = array(
            'token'  => $this->token,
            'room'   => $this->room,
            'from'   => $this->from
        );
        
        if(!empty($this->notify)) $config['notify'] = $this->notify;
        
        Hippy::config($config);
        
        //Check we've set at least one message to send
        if(empty($this->message) && empty($this->messages)) {
            throw new BuildException("Make sure you set a message", $this->location);
        }
        
        if(!empty($this->message)) {
            //Send single message
            Hippy::speak($this->message);
        }
        
        if(!empty($this->messages)) {
            //Send multiple messages
            foreach($this->messages as $message) {
                Hippy::speak($message->message);
            }
        }
    }
}

/**
 * Handles sending multiple messages
 */
class Speak extends DataType
{
    /**
     * Message to send
     */
    public $message;
    
    /**
     * Set message to send
     */
    public function setMessage($msg) {
    
        $this->message = $msg;
    }
}

?>