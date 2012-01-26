<?php

include_once dirname(__FILE__).'/Hippy/exceptions.php';
include_once dirname(__FILE__).'/Hippy/driver/driver.php';


class Hippy
{
	protected static $instance;
	protected $config = array();
	
	/**
	 * Instead of sending a message straight away queue it up with add() and send with go()
	 */
	protected $queue  = array();
	
	
	/**
	 * This method is deprecated, use instance() instead.
	 *
	 * @deprecated since 0.5
	 */
	public static function getInstance()
	{
		return static::instance();
	}
	
	/**
	 * Create Hippy object
	 *
	 * @param   array  Hippy config options
	 * @param   bool   Retrieve a clean instance of Hippy. See clean()
	 * @return  Hippy
	 */
	public static function instance(array $config = array(), $clean = false)
	{
		if(!isset(self::$instance) OR $clean)
        {
			$class = __CLASS__;
            static::$instance = new $class;
        }
		
		// If no config has been set, load the default
		if(count(static::$instance->config) === 0)
		{
			$default_config = include dirname(__FILE__).'/Hippy/config.php';
			
			static::$instance->settings($default_config);
		}
		
		// If a custom config is passed in when returning an instance
		if(count($config) > 0)
		{
			static::$instance->settings($config);
		}
		
        return static::$instance;
	}
	
	/**
	 * Get a clean instance of Hippy. Shortcut for calling instance with clear flag.
	 *
	 * @param  array  Hippy config options
	 */
	public static function clean(array $config = array())
	{
		return static::instance($config, true);
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
     * Deprecated as of 0.5, use $hippy->api_endpoint. Returns URL to API endpoint
     *
     * @deprecated since 0.5
     * @return String
     */
    public function endpoint()
    {
        return $this->api_endpoint;
    }

	/**
     * Deprecated as of 0.5, use Hippy::instance($config). Set configuration for all Hippy messages
     *
	 * @deprecated since 0.5
     * @param  Array $config Settings to set
     * @return Array Settings
     */
    public static function config($config = null)
    {
        $instance = static::instance();
        
        //Set any settings if passed in
        if(!is_null($config) AND is_array($config))
        {
            $instance->settings($config);
        }
        
        //Return new merged settings
        return $instance->config;
    }
	
	/**
     * Sets valid settings. Renames shorthand to full names to meet API requirements
     *
     * @param Array $config Settings to set
     */
	protected function settings(array $config)
	{
		//Rename `token` to use correct name `auth_token`
        if(isset($config['token']))
		{
            $this->config['auth_token'] = $config['token'];
            unset($config['token']);
        }
        
        //Rename `room` to use correct name `room_id`
        if(isset($config['room']))
		{
            $this->config['room_id'] = $config['room'];
            unset($config['room']);
        }
        
        //Convert boolean notify flag to integer
        if(isset($config['notify']))
		{
            $this->config['notify'] = (int)$config['notify'];
            unset($config['notify']);
        }

		// Merge any other settings
        $this->config = array_merge($this->config, $config);
		
		// Load the driver
		if(isset($config['driver']))
		{
			// Pass your own driver in at runtime
			if(is_object($config['driver']))
			{
				$this->config['driver'] = $config['driver'];
				$this->config['driver']->init($this->config);
			}
			else
			{
				$driver = $config['driver'];

				if(file_exists($path = dirname(__FILE__).'/Hippy/driver/'.strtolower($driver).'.php'))
				{
					include_once $path;
					$class = 'Hippy_'.ucfirst($driver);
					$this->config['driver'] = new $class;
					$this->config['driver']->init($this->config);
				}
			}
		}		
	}
	
	/**
     * Checks that neccessery settings are set before attempting to send a new message
     *
     * @deprecated since 0.5 - Use Hippy_Driver::valid_settings() to check this.
     * @throws HippyException
     */
    public function validSettings()
    {
        foreach(array('auth_token', 'room_id', 'from', 'api_endpoint') as $key)
        {
			if($this->$key === null OR strlen(trim($this->$key)) === 0)
            {
                //Setting not set, throw exception
				$driver = $this->driver;
                throw new HippyException($driver::STATUS_BAD_REQUEST, "Hippy error: info=Settings incorrect, setting=$key"); 
            }
        }

		return true;
    }


	public static function speak($msg, array $config = array())
	{
		$instance = static::instance();
		
		// Set any runtime settings
		$instance->settings($config);
		
		// Initalize driver with latest config and send message
		return $instance->driver->init($instance->config)->send($msg);
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
		$instance          = static::instance();
		$instance->queue[] = $msg; //Add message to queue
	}
	
	/**
	 * Joins all the messages in the queue together with a line break and sends it.
	 *
	 * @param bool Whether to join the queue of messages and send as one message, or seperate messages. Default TRUE.
	 * 
	 * @throws HippyException
	 */
	public static function go($join = true)
	{
		$instance = static::instance();
		
		if(count($instance->queue) == 0)
		{
			throw new HippyException('Can not send queue. Queue is empty!');
		}
		
		if($join)
		{
			$msg = implode('<br />', $instance->queue);
			
			$instance->send($msg);
		}
		else
		{
			foreach($instance->queue as $msg)
			{
				$instance->send($msg);
			}
		}
	}
}