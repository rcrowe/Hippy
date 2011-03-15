<?php

/**
 * Hippy - PHP client for HipChat. Designed for incidental notifications from an application.
 *
 * Example
 * -------
 *
 * <code>
 *
 *     $settings = array(
 *         'token'  => 'abc123',
 *         'room'   => 'General',
 *         'from'   => 'rcrowe',
 *         'notify' => true
 *     );
 *
 *     Hippy::settings($settings);
 *
 *     Hippy::speak('Did the build succedded');
 *     Hippy::speak('Yes, build succedded');
 *
 *     Hippy::speak('Or pass the settings in', $settings);
 *
 * </code>
 *
 * @author Rob "VivaLaCrowe" Crowe <nobby.crowe@gmail.com>
 * @license LGPL
 *
 */
class Hippy {

    /**
     * HipChat API hostname
     */
    const HIPCHAT_TARGET = 'http://api.hipchat.com';
    
    /**
     * Version of API Hippy targets
     */
    const HIPCHAT_VERSION = 'v1';
    
    /**
     * API request for new message
     */
    const HIPCHAT_REQUEST = 'rooms/message';
    
    /**
     * Bad response from API
     */
    const STATUS_BAD_RESPONSE = -1;
    
    /**
     * Response OK from API
     */
    const STATUS_OK           = 200;
    
    /**
     * Bad request from API
     */
    const STATUS_BAD_REQUEST  = 400;
    
    /**
     * Instance of Hippy
     *
     * @see Hippy::getInstance()
     */
    private static $instance;
    
    /**
     * Holds URL to API endpoint
     */
    private $endpoint_url;
    
    /**
     * Holds Hippy settings
     *
     * @see Hippy::config()
     */
    private $settings = array();
    
    /**
     * Settings that need to be set for a valid message
     */
    private $requiredKeys = array('auth_token', 'room_id', 'from');

	/**
	 * Holds queue of messages.
	 *
	 * @see Hippy::add()
	 */
	private $queue = array();
    
    /**
     * Hippy constructor. Use either Hippy::speak or Hippy::getInstance if you want to do
     * anythin else.
     */
    public function __construct()
    {
        //Set URL to endpoint
        $this->endpoint_url = sprintf("%s/%s/%s", self::HIPCHAT_TARGET, self::HIPCHAT_VERSION, self::HIPCHAT_REQUEST);
        
        //Set any default settings
        $this->settings['notify'] = 1;
    }
    
    /**
     * Get an instance of Hippy.
     */
    public static function getInstance()
    {
        if(!isset(self::$instance))
        {
            self::$instance = new Hippy;
        }
        
        return self::$instance;
    }
    
    /**
     * Returns URL to API endpoint
     *
     * @return String
     */
    public function endpoint()
    {
        return $this->endpoint_url;
    }
    
    /**
     * Clears static instance of Hippy. Mainly used for testing purposes.
     */
    public static function destroy()
    {
        self::$instance = new Hippy;
    }
    
    /**
     * Set configuration for all Hippy messages
     *
     * @param  Array $config Settings to set
     * @return Array Settings
     */
    public static function config($config = null)
    {
        $instance = self::getInstance();
        
        //Set any settings if passed in
        if(!is_null($config))
        {
            $instance->settings($config);
        }
        
        //Return new merged settings
        return $instance->settings;
    }
    
    /**
     * Sets valid settings. Renames shorthand to full names to meet API requirements
     *
     * @param Array $config Settings to set
     */
    private function settings($config)
    {
        if(is_array($config) && !empty($config))
        {
            //Remove any settings that dont have a key
            $keys = array_keys($config);
            
            foreach($keys as $key)
            {
                if(is_int($key))
                {
                    unset($config[$key]);
                }
            }
        
            //Rename `token` to use correct name `auth_token`
            if(isset($config['token'])) {
                $this->settings['auth_token'] = $config['token'];
                unset($config['token']);
            }
            
            //Rename `room` to use correct name `room_id`
            if(isset($config['room'])) {
                $this->settings['room_id'] = $config['room'];
                unset($config['room']);
            }
            
            //Convert boolean notify flag to integer
            if(isset($config['notify'])) {
                $this->settings['notify'] = (int)$config['notify'];
                unset($config['notify']);
            }
            
            //Merge any other settings
            $this->settings = array_merge($this->settings, $config);
        }
    }
    
    /**
     * Checks that neccessery settings are set before attempting to send a new message
     *
     * @internal Visibility changed to public to aid testing
     * @throws HippyException
     */
    public function validSettings()
    {
        foreach($this->requiredKeys as $key)
        {
            if(!array_key_exists($key, $this->settings)) 
            {
                //Setting not set, throw exception
                throw new HippyException(self::STATUS_BAD_REQUEST, "Hippy error: info=Settings incorrect, setting=$key"); 
            }
        }
    }
    
    /**
     * Send a message to a HipChat room
     *
     * @param string       $msg    Message to send to the room.
     * @param array|string $config Either an array of settings or API token.
     *
     * @throws HippyException
     */
    public static function speak($msg, $config = null)
    {
        $instance = self::getInstance();
        $instance->config($config);
        $instance->send($msg);
    }

	/**
	 * Add a message to the queue. Message will be bundled as one message with line breaks, then sent with Hippy::go().
	 *
	 * @param string $msg Message you want to add the queue and send.
	 *
	 * @throws HippyException
	 */
	public static function add($msg)
	{
		$instance = self::getInstance();
		$instance->queue[] = $msg; //Add message to queue
	}
	
	/**
	 * Joins all the messages in the queue together with a line break and sends it.
	 *
	 * @throws HippyException
	 */
	public static function go()
	{
		$instance = self::getInstance();
		
		if(count($instance->queue) == 0)
		{
			throw new HippyException('Can not send queue. Queue is empty!');
		}
		
		$msg = implode('\\n', $instance->queue);
		
		$instance->send($msg);
	}
    
    /**
     * Attempt to send message
     *
     * @param string $msg Message to send to HipChat room
     *
     * @throws HippyException
     */
    private function send($msg) {
	
		//Hippy allows HTML in the message
		//Replace line breaks with <br />
		$msg = str_replace("\\n", "<br />", $msg);
    
        //Make sure neccessery settings are set and valid
        $this->validSettings();
        
        //Build arguments to send to HipChat API
        $args = $this->settings;
        $args['format']  = 'json';
        $args['message'] = $msg;
        
        $this->endpoint_url .= '?'.http_build_query($args);
        
        //Make request using cURL
        $response = $this->makeRequest($this->endpoint_url);
        $response = json_decode($response, TRUE);
        
        if(!$response) {
            throw new HippyException(self::STATUS_BAD_RESPONSE, "Invalid JSON recieved: $response", $this->endpoint_url);
        }
        
        if($response['status'] !== "sent")
        {
            throw new HippyException(self::STATUS_BAD_RESPONSE, "Response states message wasn\'t sent. Response: ".$response['status'], $this->endpoint_url);
        }
    }
    
    /**
     * Makes a new GET request to the HipChat API using cURL
     */
    private function makeRequest($url) {
    
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $response = curl_exec($ch);
        $code     = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        //Check we got a response
        if(strlen($response) == 0) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            throw new HippyException($code, self::STATUS_BAD_RESPONSE, "CURL error: $errno - $error", $url);
        }
        
        //Check we got the correct http code
        if($code !== self::STATUS_OK) {
            throw new HippyException($code, "HTTP status code: $code, response=$response", $url);
        }
        
        curl_close($ch);

        //Return JSON
        return $response;
    }
}

/**
 * Hippy Exception class. Make sure you try catch this
 */
class HippyException extends Exception
{
    /**
     * Exception constructor. Use is to set the error message format.
     */
    public function __construct($code, $info, $url = NULL) {
    
        $message = "Hippy error: code=$code, info=$info";
        
        //Include URL in message if supplied
        if(!empty($url)) $message .= " url=$url";
        
        parent::__construct($message, (int)$code);
    }
}

?>