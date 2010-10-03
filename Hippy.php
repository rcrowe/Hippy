<?php

require_once 'Hippy/Room.php';
require_once 'Hippy/Request.php';
require_once 'Hippy/Exceptions.php';

class Hippy
{
    private static $config = array();
    
    public static function settings($config)
    {
        if(is_array($config) && !empty($config))
        {
            //Passed in array of settings
            Hippy::$config = $config;
        }
        else if(is_string($config) && strlen($config) > 0)
        {
            //Only passed in API token
            Hippy::$config = array(
                'token' => $config
            );
        }
        else
        {
            //TODO: throw exception
        }
    }
    
    public static function speak($msg, $config = NULL)
    {
        if(!empty($config) && is_array($config))
        {
            array_merge(Hippy::$config, $config);
        }
        
        $room = new Room(Hippy::$config);
        $room->speak($msg, $config);
    }
    
    public static function room($room = NULL)
    {
        return new Room(Hippy::$config);
    }
}

?>