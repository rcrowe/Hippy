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
class Hippy
{
    /**
     * HipChat API details
     */
    const HIPCHAT_TARGET  = 'http://api.hipchat.com';
    const HIPCHAT_VERSION = 'v1';
    const HIPCHAT_REQUEST = 'rooms/message';
    private $url;
    
    /**
     * Response codes from the HipChat API
     */
    const STATUS_BAD_RESPONSE          = -1;
    const STATUS_OK                    = 200;
    const STATUS_BAD_REQUEST           = 400;

    /**
     * Instance of Hippy
     */
    private static $instance;

    /**
     * Holds settings for send message to HipChat REST API
     */
    private $settings = array();
    
    /**
     * Settings that have to be set before a message can be sent
     */
    private $requiredKeys = array(
        'auth_token',
        'room_id',
        'from'
    );
    
    /**
     * Hippy constructor. Use either Hippy::speak or Hippy::getInstance if you want to do
     * anythin else.
     */
    private function __construct() {
    
        //Build URL to make request against
        $this->url = sprintf("%s/%s/%s", self::HIPCHAT_TARGET, self::HIPCHAT_VERSION, self::HIPCHAT_REQUEST);
    
        //Set any default settings
        $this->settings['notify'] = 1;
    }
    
    /**
     * Get an instance of Hippy
     */
    public static function getInstance() {
        
        if(!isset(self::$instance)) {
            self::$instance = new Hippy;
        }
        
        return self::$instance;
    }

    /**
     * Send a message to a HipChat room
     *
     * @param string       $msg    Message to send to the room. Text is UTF8 encoded.
     * @param array|string $config Either an array of settings or API token.
     *
     * @internal Uses Hippy_Room::speak() to send message. This is just a shortcut
     *
     * @throws HippyException 
     */
    public static function speak($msg, $config = NULL) {
    
        $hip = self::getInstance();
        $hip->settings($config);
        $hip->send($msg);
    }
    
    /**
     * Set any global settings instead of setting it each time you send a message
     */
    public static function config($config) {
    
        $hip = self::getInstance();
        $hip->settings($config);
    }
    
    /**
     * Sets settings for messages
     *
     * @param array $config Array of settings, see examples
     */
    private function settings($config) {
    
        if(is_array($config) && !empty($config)) {
        
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
     * Make sure settings are valid before sending message
     *
     * @throws HippyException
     */
    private function checkSettings() {
    
        foreach($this->requiredKeys as $key) {
            if(!array_key_exists($key, $this->settings)) {
                //Setting not set, throw exception
                throw new HippyException(self::STATUS_BAD_REQUEST, "Hippy error: info=Settings incorrect, setting=$key"); 
            }
        }
    }
    
    /**
     * Attempt to send message
     *
     * @param string $msg Message to send to HipChat room
     *
     * @throws HippyException
     */
    private function send($msg) {
    
        //Make sure neccessery settings are set and valid
        $this->checkSettings();
        
        //Build arguments to send to HipChat API
        $args = $this->settings;
        $args['format']  = 'json';
        $args['message'] = $msg;
        
        $this->url .= '?'.http_build_query($args);
        
        //Make request using cURL
        $response = $this->makeRequest($this->url);
        $response = json_decode($response, TRUE);
        
        if(!$response) {
            throw new HippyException(self::STATUS_BAD_RESPONSE, "Invalid JSON recieved: $response", $this->url);
        }
        
        //TODO: Check that the response says `sent`
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