<?php

/**
 * Validates and hands of the actual sending of the message to the driver
 *
 * @author Rob "VivaLaCrowe" Crowe <hello@vivalacrowe.com>
 * @license MIT 
 */
abstract class Hippy_Driver
{
	/**
	 * Request to append to the end of the Hipchat API endpoint
	 */
	const HIPCHAT_REQUEST = 'rooms/message';
	
	/**
	 * Hold the final set of config options were using to send the message with
	 */
	protected $config = array();
	
	/**
	 * Initialise the driver with the final set of config options
	 *
	 * @param  array  Config
	 */
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
			array(
				'setting' => 'auth_token',
				'map'     => 'token',
			),
			array(
				'setting' => 'room_id',
				'map'     => 'room',
			),
			array(
				'setting' => 'from',
				'map'     => 'from',
			),
			array(
				'setting' => 'api_endpoint',
				'map'     => 'api_endpoint',
			),
		);
	
        foreach($required as $setting)
        {
			if($this->$setting['setting'] === null OR strlen(trim($this->$setting['setting'])) === 0)
            {
                //Setting not set, throw exception
				throw new HippyMissingSettingException('Missing setting: '.$setting['map']);
            }
        }
		
		return true;
    }

	/**
	 * Format the message and pass the message of to the driver to be sent
	 * 
	 * @param  string  Message to send to Hipchat
	 */
	public function send($msg)
	{
		// Validate settings before we try sending
		$this->valid_settings();
		
		//Hippy allows HTML in the message
		//Replace line breaks with <br />
		$msg = str_replace("\\n", "<br />", $msg);
		
		//Build arguments to send to HipChat API
		$args = array(
			'format'     => 'json',
			'auth_token' => $this->auth_token,
			'room_id'    => $this->room_id,
			'from'       => $this->from,
			'notify'     => $this->notify,
			'color'      => $this->color,
			'message'    => $msg,
		);
		
		$api_endpoint = $this->api_endpoint . static::HIPCHAT_REQUEST . '?' . http_build_query($args, '', '&');
		
		
		// Now make the request to the api to the API using the selected driver
		$response = $this->request($api_endpoint);
		$response = json_decode($response, TRUE);
		
		// Make sure the message was sent to Blunder
		if(!is_array($response))
		{
            throw new HippyResponseException("Invalid JSON recieved", $api_endpoint);
        }

		if(!isset($response['status']))
		{
			$msg = 'Response does not contain field `status`';
			throw new HippyResponseException($msg, $api_endpoint);
		}
        
        if($response['status'] !== "sent")
        {
            throw new HippyNotSentException("Response states message wasn\'t sent. Response: ".$response['status'], $api_endpoint);
        }
		
		
		// Return request details
		// This is mainly used for testing purposes
		return array(
			'api_endpoint' => $api_endpoint,
			'msg'          => $msg,
			'response'     => $response,
		);
	}
	
	/**
	 * Driver must extend request.
	 *
	 * @param  string  URL of the API endpoint
	 */
	public function request($url)
	{
		throw new HippyNotSentException('Driver must extend `request`', $url);
	}
}