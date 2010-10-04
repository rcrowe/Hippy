<?php

//Include neccessery files
require_once 'Exceptions.php';

/**
 * Base to most Hippy classes. Mainly provides functionality related to setting
 * and checking settings. Also provides constants for raised HippyExceptions.
 */
class Hippy_Base
{
    //Array that holds all set settings
    //Contains defaults
    protected static $config = array(
        'notify' => 1
    );
    
    //Mandatory array_keys that need to be in settings {@link config}.
    private $required_keys = array('auth_token', 'room_id', 'from');
    
    //Hippy error codes
    const HIPPY_BAD_SETTINGS           = -2;
    
    //Response codes from the HipChat API
    const STATUS_BAD_RESPONSE          = -1;
    const STATUS_OK                    = 200;
    const STATUS_BAD_REQUEST           = 400;
    const STATUS_UNAUTHORIZED          = 401;
    const STATUS_FORBIDDEN             = 403;
    const STATUS_NOT_FOUND             = 404;
    const STATUS_NOT_ACCEPTABLE        = 406;
    const STATUS_INTERNAL_SERVER_ERROR = 500;
    const STATUS_SERVICE_UNAVAILABLE   = 503;

    /**
     * Sets Hippy settings for messages.
     *
     * @param string|array $fig Either a string, which is your API token. Or an array of settings, see examples
     */
    public static function settings($fig)
    {
        if(is_array($fig) && !empty($fig))
        {
            //Rename `token` key
            //Use `token` instead of `auth_token` as its easier :p
            if(isset($fig['token']))
            {
                self::$config['auth_token'] = $fig['token'];
                unset($fig['token']);
            }
            
            //Rename `room`
            //Use `room_id` instead of `room` as its easier
            if(isset($fig['room']))
            {
                self::$config['room_id'] = $fig['room'];
                unset($fig['room']);
            }
            
            //Convert boolean notify flag to integer
            if(isset($fig['notify']))
            {
                self::$config['notify'] = (int)$fig['notify'];
            }
        
            //Make sure we merge with existing settings
            self::$config = array_merge(self::$config, $fig);
        }
        else if(is_string($fig) && strlen($fig) > 0)
        {
            //Only passed in API token
            self::$config['token'] = $fig;
        }
    }
    
    /**
     * Check we have the neccessery settings for sending a message.
     *
     * @throws HippyException
     */
    protected function checkSettings()
    {
        //Check all settings are set
        foreach($this->required_keys as $key)
        {
            if(!array_key_exists($key, self::$config))
            {
                //Setting not set, throw exception
                throw new HippyException(self::HIPPY_BAD_SETTINGS, "Hippy error: info=Settings incorrect, setting=$key");
            }
        }
    }
}

?>