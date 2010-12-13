<?php

require_once 'simpletest/autorun.php';
require_once '../Hippy.php';
require_once 'settings.php';

/**
 * Hippy makes use of the singleton pattern so we can use static access to functions. These tests make sure
 * that an instance of Hippy is created and then re-used correctly.
 */
class InstanceTests extends UnitTestCase
{
    public function __construct()
    {
        //Make sure we start a fresh
        Hippy::destroy();
    }
    
    function testGetInstance()
    {
        $instance = Hippy::getInstance();
        
        $this->assertIsA($instance, 'Hippy');
    }
    
    function testInstanceStatic()
    {
        $instance = Hippy::getInstance();
        $instance->someRandomVal = 'yppih';
        
        $this->assertEqual($instance->someRandomVal, 'yppih');
        
        $instance = null;
        
        $new_instance = Hippy::getInstance();
        
        $this->assertEqual($new_instance->someRandomVal, 'yppih');
    }
    
    //Check URL had been built from constants when an instance is created
    function testURLSet()
    {
        $instance = Hippy::getInstance();

        $this->assertEqual(sprintf("%s/%s/%s", Hippy::HIPCHAT_TARGET, Hippy::HIPCHAT_VERSION, Hippy::HIPCHAT_REQUEST), $instance->endpoint());
        
        $this->assertEqual("http://api.hipchat.com/v1/rooms/message", $instance->endpoint());
    }
    
    
    function testForceCreationWithDestroy()
    {
        //Check we already have an instance of Hippy
        $instance = Hippy::getInstance();
        
        $this->assertIsA($instance, 'Hippy');
        
        //Set some settings
        $config = Hippy::config(array('name' => 'rcrowe'));
        
        $this->assertTrue((count($config) === 2));
        
        //Lets get a clean instance of Hippy
        Hippy::destroy();
        
        $new_instance = Hippy::getInstance();
        
        $new_config = Hippy::config();
        
        $this->assertTrue((count($new_config) === 1));
    }
}

?>