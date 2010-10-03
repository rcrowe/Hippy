<?php

require_once 'Request.php';
require_once 'Exceptions.php';

class Room
{
    public function __construct($config)
    {
        
    }
    
    public function from($user)
    {
        return $this;
    }
    
    public function notify($notify)
    {
        return $this;
    }
    
    public function speak($msg, $config = NULL)
    {
        return $this;
    }
}

?>