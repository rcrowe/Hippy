<?php

require_once 'Request.php';
require_once 'Exceptions.php';

class Room
{
    private $config = array();
    
    public function __construct($config)
    {
        $this->config = $config;
    }
    
    public function from($user)
    {
        //TODO Make sure the name fits the HipChat requirements
        //Must be less than 15 characters long. May contain letters, numbers, -, _, and spaces
        if(is_string($user) && strlen($user) > 0)
        {
            $this->config['from'] = $user;
        }
        
        return $this;
    }
    
    public function notify($notify)
    {
        if(is_bool($notify))
        {
            $this->config['notify'] = $notify;
        }
        
        return $this;
    }
    
    public function speak($msg, $config = NULL)
    {
        if(!empty($config) && is_array($config))
        {
            $this->config = array_merge($this->config, $config);
        }
        
        print_r($this->config);
        
        return $this;
    }
}

?>