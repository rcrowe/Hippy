<?php

require_once 'simpletest/autorun.php';
require_once '../Hippy.php';
require_once 'settings.php';

class ConfigTests extends UnitTestCase
{
    public function __construct()
    {
        //Make sure we start a fresh
        Hippy::destroy();
    }

    function testConfigReturnDefault()
    {
        $config = Hippy::config();
        
        $this->assertIsA($config, 'Array');
        $this->assertTrue((count($config) === 1));
        $this->assertIdentical($config['notify'], 1);
    }
    
    function testSetAnySetting()
    {
        $config = Hippy::config(array('test' => 'tset'));
        
        $this->assertEqual($config['test'], 'tset');
    }
    
    //Can only pass in an array
    //Anything else does not effect settings
    function testOnlyArray()
    {
        //Clear any previously set settings
        Hippy::destroy();
                
        $config = Hippy::config('test');
        $this->assertTrue((count($config) === 1));
        
        $config = Hippy::config(2);
        $this->assertTrue((count($config) === 1));
        
        $config = Hippy::config(array());
        $this->assertTrue((count($config) === 1));
        
        $config = Hippy::config(array('test'));
        $this->assertTrue((count($config) === 1));
        
        $config = Hippy::config(array('test', 'hippy'));
        $this->assertTrue((count($config) === 1));
    }
    
    //Check key settings = token, room, notify
    //are changed
    
    function testSettingsRenamed()
    {
        //token = auth_token
        Hippy::config(array('token' => 'abc123'));
        
        $config = Hippy::config();
        
        $this->assertFalse(isset($config['token']));
        $this->assertEqual($config['auth_token'], 'abc123');
        
        //room = room_id
        Hippy::config(array('room' => 'test'));
        
        $config = Hippy::config();
        
        $this->assertFalse(isset($config['room']));
        $this->assertEqual($config['room_id'], 'test');
        
        //notify = (int)notify
        Hippy::config(array('notify' => true));
        
        $config = Hippy::config();
        
        $this->assertIdentical($config['notify'], 1);
        
        Hippy::config(array('notify' => false));
        
        $config = Hippy::config();
        
        $this->assertIdentical($config['notify'], 0);
    }
    
    function testSettingsValidThrowsException()
    {
        Hippy::destroy();
        
        $instance = Hippy::getInstance();
        
        //Check exception is thrown with correct error code
        try
        {
            $instance->validSettings();
            $this->assertFalse(true, 'Was expecting a HippyException to have been thrown');
        }
        catch(HippyException $e)
        {
            $this->assertEqual($e->getCode(), Hippy::STATUS_BAD_REQUEST);
        }
    }
}

?>