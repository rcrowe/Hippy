<?php

class Hippy
{
	protected static $instance;
	protected $config = array();
	
	
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
        
        //Merge any other settings
        $this->config = array_merge($this->config, $config);		
	}
}