<?php

class Hippy_Driver
{
	/**
	 * Request to append to the end of the Hipchat API endpoint
	 */
	const HIPCHAT_REQUEST = 'rooms/message';
	
	/**
     * Bad response from API
     */
    const STATUS_BAD_RESPONSE = -1;
    
    /**
     * Response OK from API
     */
    const STATUS_OK = 200;
    
    /**
     * Bad request from API
     */
    const STATUS_BAD_REQUEST = 400;
	
	
	protected $config = array();
	
	
	public function init(array $config)
	{
		$this->config = array_merge($this->config, $config);
		
		// Return self so we can chain
		return $this;
	}
	
	/**
	 * Get the value of a config variable
	 *
	 * @param   string  Name of the config variable
	 * @return  mixed
	 */
	public function __get($name)
	{
		return (isset($this->config[$name])) ? $this->config[$name] : null;
	}
	
	/**
     * Checks that necessary settings are set before attempting to send a new message
     *
     * @internal Visibility changed to public to aid testing
     * @throws HippyException
     */
    public function valid_settings()
    {
		$required = array(
			'auth_token',
			'room_id',
			'from',
			'api_endpoint',
		);
		
		foreach($required as $key)
		{
			if($this->$key === null OR strlen(trim($this->$key)) === 0)
			{
				throw new HippyException(static::STATUS_BAD_REQUEST, "Hippy error: info=Settings incorrect, setting=$key");
			}
		}
		
		return true;
    }

	public function send($msg)
	{
		// Validate settings before we try sending
		$this->valid_settings();
		
		//Hippy allows HTML in the message
		//Replace line breaks with <br />
		$msg = str_replace("\\n", "<br />", $msg);
		
		//Build arguments to send to HipChat API
		$args = array(
			'format'  => 'json',
			'message' => $msg,
		);

		$api_endpoint = $this->api_endpoint . '?' . http_build_query($args, '', '&');
		
		
		// Now make the request to the api to the API using the selected driver
		$response = $this->request($api_endpoint);
		$response = json_decode($response, TRUE);
		
		
		// Make sure the message was sent to Blunder
		if(!$response)
		{
            throw new HippyException(static::STATUS_BAD_RESPONSE, "Invalid JSON recieved: $response", $api_endpoint);
        }
        
        if($response['status'] !== "sent")
        {
            throw new HippyException(static::STATUS_BAD_RESPONSE, 
									 "Response states message wasn\'t sent. Response: ".$response['status'], $api_endpoint);
        }
		
		
		// Return request details
		// This is mainly used for testing purposes
		return array(
			'api_endpoint' => $api_endpoint,
			'msg'          => $msg,
			'response'     => $response,
		);
	}
}