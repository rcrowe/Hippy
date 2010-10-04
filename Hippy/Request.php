<?php

require_once 'Base.php';
require_once 'Exceptions.php';

/**
 * Hippy_Request deals with send the GET/POST request to HipChat. Makes use of CURL.
 */
class Hippy_Request extends Hippy_Base
{
    //API details
    const HIPCHAT_TARGET  = 'http://api.hipchat.com';
    const HIPCHAT_VERSION = 'v1';

    /**
     * Make a new request to the HipChat API
     *
     * @param string $url  API method, eg: rooms/message
     * @param array  $args key => val array of items to send to API
     * @param string $http_method HTTP method to use. Either GET or POST
     *
     * @throws HippyException
     *
     * @return Array JSON decoded array returned by the HipChat API
     */
    public static function make_request($url, $args = array(), $http_method = 'POST')
    {
        //Build arguments
        $args = (is_array($args) && !empty($args)) ? array_merge($args, parent::$config) : parent::$config;
        $args['format'] = 'json';
        
        //TODO remove debug element
        print_r($args);
        return;
        
        //Build URL if this is a GET request
        $url = self::HIPCHAT_TARGET.'/'.self::HIPCHAT_VERSION.'/'.$url;
        
        if($http_method === 'GET')
        {
            $url .= '?'.http_build_query($args);
        }
        else
        {
            $post_data = $args;
        }
        
        //Make request
        $response = self::curl_request($url, $post_data);
        
        $response = json_decode($response, TRUE);
        
        //Make sure response is valid
        if(!$response)
        {
            throw new HippyException(self::STATUS_BAD_RESPONSE, "Invalid JSON recieved: $response", $url);
        }
        
        return $response;
    }
    
    /**
     * Makes the cURL request.
     *
     * @param string $url URL to make request against
     * @param array  $post_data Array of data to POST
     *
     * @throws HippyException
     *
     * @return string JSON string returned from HipChat API
     */
    public static function curl_request($url, $post_data = NULL)
    {
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        
        if (is_array($post_data)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        }
        
        $response = curl_exec($ch);
        $code     = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        //Check we got a response
        if(strlen($response) == 0)
        {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
            
            throw new HippyException($code, self::STATUS_BAD_RESPONSE, "CURL error: $errno - $error", $url);
        }
        
        //Check we got the correct http code
        if($code !== self::STATUS_OK)
        {
            throw new HippyException($code, "HTTP status code: $code, response=$response", $url);
        }
        
        curl_close($ch);

        //Return JSON
        return $response;
    }
}

?>